<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        // Obtener las ventas con paginación
        $sales = Sale::paginate($size);

        // Cambiar el índice de las páginas de Laravel
        $sales->setPageName('page');
        $sales->appends(['size' => $size]);

        $response = [
            'content' => $sales->items(),
            'currentPage' => $sales->currentPage() - 1,
            'perPage' => $sales->perPage(),
            'totalElements' => $sales->total(),
            'totalPages' => $sales->lastPage() - 1,
        ];

        return response()->json($response);
    }

    // Almacenar una nueva venta
    public function store(Request $request)
    {
        $request->validate([
            'emprendimiento_id' => 'required|uuid|exists:emprendimientos,id',
            'payment_id' => 'required|uuid|exists:payments,id',
            'reserva_id' => 'required|uuid|exists:reservas,id',
            'code' => 'required|string|max:255',
            'IGV' => 'required|numeric',
            'BI' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $sale = Sale::create([
            'emprendimiento_id' => $request->emprendimiento_id,
            'payment_id' => $request->payment_id,
            'reserva_id' => $request->reserva_id,
            'code' => $request->code,
            'IGV' => $request->IGV,
            'BI' => $request->BI,
            'total' => $request->total,
        ]);

        return response()->json($sale, 201);  // Retornar la venta creada
    }

    // Mostrar una venta específica
    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        return response()->json($sale);
    }

    // Actualizar una venta existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'emprendimiento_id' => 'required|uuid|exists:emprendimientos,id',
            'payment_id' => 'required|uuid|exists:payments,id',
            'reserva_id' => 'required|uuid|exists:reservas,id',
            'code' => 'required|string|max:255',
            'IGV' => 'required|numeric',
            'BI' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $sale = Sale::findOrFail($id);
        $sale->update([
            'emprendimiento_id' => $request->emprendimiento_id,
            'payment_id' => $request->payment_id,
            'reserva_id' => $request->reserva_id,
            'code' => $request->code,
            'IGV' => $request->IGV,
            'BI' => $request->BI,
            'total' => $request->total,
        ]);

        return response()->json($sale);
    }

    // Eliminar una venta (Soft Delete)
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();  // Esto realiza un soft delete

        return response()->json(['message' => 'Sale deleted successfully'], 200);
    }
}
