<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Listar pagos asociados a reservas del usuario autenticado con paginación y filtro opcional por código.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $size = $request->input('size', 10);
        $codeFilter = $request->input('code');

        // Query para obtener pagos asociados a reservas del usuario
        $query = Payment::whereHas('sales.reserva', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        // Filtro por código si existe
        if ($codeFilter) {
            $query->where('code', 'like', "%{$codeFilter}%");
        }

        // Ordenar por fecha creación descendente (últimos primero)
        $query->orderBy('created_at', 'desc');

        // Obtener paginación
        $payments = $query->paginate($size);

        return response()->json([
            'content' => $payments->items(),
            'currentPage' => $payments->currentPage() - 1,
            'perPage' => $payments->perPage(),
            'totalElements' => $payments->total(),
            'totalPages' => $payments->lastPage() - 1,
        ]);
    }

    /**
     * Crear un nuevo pago.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'bi' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
        ]);

        $payment = Payment::create($request->only('code', 'total', 'bi', 'igv'));

        return response()->json($payment, 201);
    }

    /**
     * Mostrar un pago específico, solo si pertenece a una reserva del usuario autenticado.
     */
    public function show($id)
    {
        $userId = Auth::id();

        $payment = Payment::where('id', $id)
            ->whereHas('sales.reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'Pago no encontrado o no autorizado'], 404);
        }

        return response()->json($payment);
    }

    /**
     * Actualizar un pago existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'bi' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->only('code', 'total', 'bi', 'igv'));

        return response()->json($payment);
    }

    /**
     * Eliminar un pago (soft delete).
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Pago eliminado correctamente'], 200);
    }

    public function pagarVentaConYape(Request $request, $saleId)
    {
        $userId = Auth::id();

        // Validar la entrada
        $request->validate([
            'monto' => 'required|numeric|min:0',                // Monto del pago
            'codigo_pago_yape' => 'required|string',            // Código de pago (referencia de Yape o QR)
        ]);

        // Buscar la venta y verificar que pertenece al usuario
        $sale = Sale::where('id', $saleId)
            ->whereHas('reserva', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada o no pertenece al usuario'], 404);
        }

        DB::beginTransaction();

        try {
            // Crear el pago con Yape
            $payment = Payment::create([
                'code' => $request->codigo_pago_yape,       // Código de pago de Yape
                'total' => $request->monto,                 // Monto del pago
                'bi' => $sale->BI,                          // Base imponible de la venta
                'igv' => $sale->IGV,                        // IGV de la venta
                'codigo_pago_yape' => $request->codigo_pago_yape, // Guardamos el código de pago de Yape
            ]);

            // Actualizar la venta con el payment_id
            $sale->update([
                'payment_id' => $payment->id,               // Vinculamos el pago con la venta
            ]);

            // Actualizar el estado de la reserva y venta
            $sale->reserva->update(['status' => 'pagada']);
            $sale->update(['status' => 'pagada']); // Cambiar estado de la venta

            DB::commit();

            return response()->json([
                'message' => 'Pago registrado correctamente con Yape',
                'payment' => $payment,
                'sale' => $sale,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error registrando pago: ' . $e->getMessage()], 500);
        }
    }
}
