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

        if ($reserveDetails->isEmpty()) {
            return response()->json(['message' => 'La reserva no tiene detalles'], 400);
        }

        DB::beginTransaction();

        try {
            $ventasCreadas = [];

            // Agrupar detalles por emprendedor
            $agrupados = $reserveDetails->groupBy(function ($detail) {
                return $detail->emprendimientoService->emprendedor_id;
            });

            foreach ($agrupados as $emprendedorId => $detallesGrupo) {
                // Calcular totales
                $bi = $detallesGrupo->sum('BI');
                $igv = $detallesGrupo->sum('IGV');
                $total = $detallesGrupo->sum('total');

                // Generar código de venta
                $lastCode = Sale::orderByDesc('created_at')->first();
                $nextCode = 'SALE_001';
                if ($lastCode) {
                    $lastNum = (int)str_replace('SALE_', '', $lastCode->code);
                    $nextCode = 'SALE_' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
                }

                $sale = Sale::create([
                    'id' => (string) Str::uuid(),
                    'emprendedor_id' => $emprendedorId,
                    'payment_id' => $validated['payment_id'],
                    'reserva_id' => $validated['reserva_id'],
                    'code' => $nextCode,
                    'BI' => $bi,
                    'IGV' => $igv,
                    'total' => $total,
                ]);

                foreach ($detallesGrupo as $detail) {
                    $sale->saleDetails()->create([
                        'id' => (string) Str::uuid(),
                        'emprendedor_service_id' => $detail->emprendedor_service_id,
                        'description' => $detail->description,
                        'costo' => $detail->costo,
                        'BI' => $detail->BI,
                        'IGV' => $detail->IGV,
                        'total' => $detail->total,
                        'lugar' => $detail->lugar,
                    ]);
                }

                $ventasCreadas[] = $sale;
            }

            // Marcar la reserva como pagada
            $reserva->update(['status' => 'pagada']);

            DB::commit();

            return response()->json([
                'message' => 'Ventas creadas automáticamente a partir de la reserva',
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
