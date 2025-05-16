<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reserva;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    // Listar ventas del usuario autenticado
    public function index(Request $request)
    {
        $userId = Auth::id();
        $size = $request->input('size', 10);

        $sales = Sale::whereHas('reserva', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['emprendimiento', 'payment', 'reserva'])
            ->orderBy('created_at', 'desc')
            ->paginate($size);

        return response()->json([
            'content' => $sales->items(),
            'totalElements' => $sales->total(),
            'currentPage' => $sales->currentPage(),
            'totalPages' => $sales->lastPage(),
        ]);
    }

    // Mostrar venta con sus relaciones (para detalle de usuario)
    public function show($id)
    {
        $userId = Auth::id();

        $sale = Sale::where('id', $id)
            ->whereHas('reserva', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['emprendimiento', 'payment', 'reserva'])
            ->first();

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada o no autorizada'], 404);
        }

        return response()->json($sale);
    }

    // Convertir una reserva en venta (crear venta)
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'reserva_id' => 'required|uuid|exists:reservas,id',
            'payment_id' => 'required|uuid|exists:payments,id',
            'ventas' => 'required|array|min:1',
            'ventas.*.emprendedor_id' => 'required|uuid|exists:emprendedors,id',
            'ventas.*.code' => 'required|string|max:255',
            'ventas.*.IGV' => 'required|numeric|min:0',
            'ventas.*.BI' => 'required|numeric|min:0',
            'ventas.*.total' => 'required|numeric|min:0',
        ]);

        $reserva = Reserva::where('id', $validated['reserva_id'])
            ->where('user_id', $userId)
            ->first();

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada o no pertenece al usuario'], 403);
        }

        $payment = Payment::find($validated['payment_id']);
        if (!$payment) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $reserveDetails = $reserva->reserveDetails()->with('emprendimientoService')->get();

        DB::beginTransaction();

        try {
            $ventasCreadas = [];

            foreach ($validated['ventas'] as $ventaData) {
                $emprendedorId = $ventaData['emprendedor_id'];

                // Verificar que el emprendedor_id esté en los detalles
                $emprendedoresEnReserva = $reserveDetails->map(function ($detail) {
                    return $detail->emprendimientoService->emprendedor_id;
                })->unique()->toArray();

                if (!in_array($emprendedorId, $emprendedoresEnReserva)) {
                    DB::rollBack();
                    return response()->json(['message' => "El emprendedor_id {$emprendedorId} no corresponde a la reserva"], 422);
                }

                $saleId = (string) \Illuminate\Support\Str::uuid();

                $sale = Sale::create([
                    'id' => $saleId,
                    'emprendedor_id' => $emprendedorId,
                    'payment_id' => $validated['payment_id'],
                    'reserva_id' => $validated['reserva_id'],
                    'code' => $ventaData['code'],
                    'IGV' => $ventaData['IGV'],
                    'BI' => $ventaData['BI'],
                    'total' => $ventaData['total'],
                ]);

                // Crear detalles solo para ese emprendedor
                foreach ($reserveDetails as $detail) {
                    if ($detail->emprendimientoService->emprendedor_id === $emprendedorId) {
                        $sale->saleDetails()->create([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'emprendedor_service_id' => $detail->emprendedor_service_id,
                            'description' => $detail->description,
                            'costo' => $detail->costo,
                            'IGV' => $detail->IGV,
                            'BI' => $detail->BI,
                            'total' => $detail->total,
                            'lugar' => $detail->lugar,
                        ]);
                    }
                }

                $ventasCreadas[] = $sale;
            }

            // Opcional: Cambiar estado reserva a 'pagada' o 'vendida' cuando todas las ventas creadas
            $reserva->update(['status' => 'pagada']);

            DB::commit();

            return response()->json([
                'message' => 'Ventas creadas exitosamente',
                'ventas' => $ventasCreadas,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error creando ventas: ' . $e->getMessage()], 500);
        }
    }



    // Actualizar datos de la venta (opcional)
    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $validated = $request->validate([
            'code' => 'string|max:255',
            'IGV' => 'numeric|min:0',
            'BI' => 'numeric|min:0',
            'total' => 'numeric|min:0',
        ]);

        $sale->update($validated);

        return response()->json($sale);
    }

    // Soft delete
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json(['message' => 'Venta eliminada correctamente']);
    }
}
