<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reserva;
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
        $query = Payment::whereHas('sales.reserva.user', function ($q) use ($userId) {
            $q->where('users.id', $userId); // Especifica correctamente la relación con la tabla 'users'
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
        $userId = Auth::id();

        // Validamos la reserva
        $validated = $request->validate([
            'reserva_id' => 'required|uuid|exists:reservas,id',
        ]);

        // Obtener la reserva
        $reserva = Reserva::where('id', $validated['reserva_id'])
            ->where('user_id', $userId)
            ->with('payments') // si tienes relación
            ->first();

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada o no pertenece al usuario'], 404);
        }

        // Validar si ya se pagó
        if ($reserva->status === 'pagada') {
            return response()->json(['message' => 'La reserva ya está pagada'], 400);
        }

        // TODO: soporte de pagos parciales más adelante
        if ($reserva->payments()->exists()) {
            return response()->json(['message' => 'Ya existe un pago asociado a esta reserva'], 400);
        }

        // Generar el código de pago
        $lastPayment = Payment::where('code', 'like', 'PAY_%')
            ->orderByDesc('created_at')
            ->first();

        $nextPaymentCode = 'PAY_001';
        if ($lastPayment) {
            $lastCodeNumber = (int)str_replace('PAY_', '', $lastPayment->code);
            $nextPaymentCode = 'PAY_' . str_pad($lastCodeNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        DB::beginTransaction();

        try {
            // Crear el pago con datos exactos de la reserva
            $payment = Payment::create([
                'code' => $nextPaymentCode,
                'total' => $reserva->total,
                'bi' => $reserva->bi,
                'igv' => $reserva->igv,
                'reserva_id' => $reserva->id, // ✅ ESTA LÍNEA ES CLAVE
            ]);


            // (opcional) asociar a venta o reserva si hay relación directa
            // $reserva->update(['payment_id' => $payment->id]);

            DB::commit();

            return response()->json([
                'message' => 'Pago creado correctamente',
                'payment' => $payment,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error creando pago: ' . $e->getMessage(),
            ], 500);
        }
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
