<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        // Obtener los pagos con paginación
        $payments = Payment::paginate($size);

        // Cambiar el índice de las páginas de Laravel
        $payments->setPageName('page');
        $payments->appends(['size' => $size]);

        $response = [
            'content' => $payments->items(),
            'currentPage' => $payments->currentPage() - 1,
            'perPage' => $payments->perPage(),
            'totalElements' => $payments->total(),
            'totalPages' => $payments->lastPage() - 1,
        ];

        return response()->json($response);
    }

    // Almacenar un nuevo pago
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'total' => 'required|numeric',
            'bi' => 'required|numeric',
            'igv' => 'required|numeric',
        ]);

        $payment = Payment::create([
            'code' => $request->code,
            'total' => $request->total,
            'bi' => $request->bi,
            'igv' => $request->igv,
        ]);

        return response()->json($payment, 201);  // Retornar el pago creado
    }

    // Mostrar un pago específico
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json($payment);
    }

    // Actualizar un pago existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'total' => 'required|numeric',
            'bi' => 'required|numeric',
            'igv' => 'required|numeric',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'code' => $request->code,
            'total' => $request->total,
            'bi' => $request->bi,
            'igv' => $request->igv,
        ]);

        return response()->json($payment);
    }

    // Eliminar un pago (Soft Delete)
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();  // Esto realiza un soft delete

        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }
}
