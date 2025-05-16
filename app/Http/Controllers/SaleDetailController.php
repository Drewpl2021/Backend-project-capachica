<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SaleDetailController extends Controller
{
    /**
     * Listar detalles de venta para el usuario autenticado, con paginación.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $size = $request->input('size', 10);

        // Traemos solo detalles de ventas que pertenecen a ventas del usuario
        $query = SaleDetail::whereHas('sale', function ($query) use ($userId) {
            $query->whereHas('reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        })->with(['emprendimientoService', 'sale']);

        $details = $query->paginate($size);

        return response()->json([
            'content' => $details->items(),
            'totalElements' => $details->total(),
            'currentPage' => $details->currentPage(),
            'totalPages' => $details->lastPage(),
        ]);
    }

    /**
     * Mostrar un detalle específico, asegurando que pertenezca al usuario autenticado.
     */
    public function show($id)
    {
        $userId = Auth::id();

        $detail = SaleDetail::where('id', $id)
            ->whereHas('sale.reserva', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['emprendimientoService', 'sale'])
            ->first();

        if (!$detail) {
            return response()->json(['message' => 'Detalle no encontrado o no autorizado'], 404);
        }

        return response()->json($detail);
    }

    /**
     * Crear un detalle de venta vinculado a una venta existente.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'sale_id' => 'required|uuid|exists:sales,id',
            'emprendedor_service_id' => 'required|uuid|exists:emprendedor_service,id',
            'description' => 'required|string|max:500',
            'costo' => 'required|numeric|min:0',
            'IGV' => 'nullable|numeric|min:0',
            'BI' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'lugar' => 'required|string|max:255',
        ]);

        // Validar que la venta pertenece al usuario autenticado
        $saleBelongsToUser = \App\Models\Sale::where('id', $validated['sale_id'])
            ->whereHas('reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->exists();

        if (!$saleBelongsToUser) {
            return response()->json(['message' => 'Venta no encontrada o no pertenece al usuario'], 403);
        }

        $detail = SaleDetail::create([
            'id' => (string) Str::uuid(),
            'sale_id' => $validated['sale_id'],
            'emprendedor_service_id' => $validated['emprendedor_service_id'],
            'description' => $validated['description'],
            'costo' => $validated['costo'],
            'IGV' => $validated['IGV'] ?? 0,
            'BI' => $validated['BI'] ?? 0,
            'total' => $validated['total'],
            'lugar' => $validated['lugar'],
        ]);

        return response()->json([
            'message' => 'Detalle de venta creado exitosamente',
            'detail' => $detail,
        ], 201);
    }

    /**
     * Actualizar un detalle de venta.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $detail = SaleDetail::where('id', $id)
            ->whereHas('sale.reserva', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if (!$detail) {
            return response()->json(['message' => 'Detalle no encontrado o no autorizado'], 404);
        }

        $validated = $request->validate([
            'description' => 'string|max:500',
            'costo' => 'numeric|min:0',
            'IGV' => 'nullable|numeric|min:0',
            'BI' => 'nullable|numeric|min:0',
            'total' => 'numeric|min:0',
            'lugar' => 'string|max:255',
        ]);

        $detail->update($validated);

        return response()->json($detail);
    }

    /**
     * Eliminar un detalle (soft delete).
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $detail = SaleDetail::where('id', $id)
            ->whereHas('sale.reserva', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if (!$detail) {
            return response()->json(['message' => 'Detalle no encontrado o no autorizado'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Detalle eliminado correctamente']);
    }
}
