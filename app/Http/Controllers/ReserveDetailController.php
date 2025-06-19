<?php

namespace App\Http\Controllers;

use App\Models\ReserveDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReserveDetailController extends Controller
{
    /**
     * Listar todos los detalles de reserva del usuario autenticado.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $userId = Auth::id();

        // Obtener detalles solo de reservas que pertenecen al usuario
        $query = ReserveDetail::whereHas('reserva', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['emprendimientoService.service', 'reserva']);  // Relacionar con servicio y reserva

        // Filtros adicionales
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%$search%");
        }

        $details = $query->paginate($size);

        return response()->json([
            'content' => $details->items(),
            'totalElements' => $details->total(),
            'currentPage' => $details->currentPage(),
            'totalPages' => $details->lastPage(),
        ]);
    }

    /**
     * Mostrar un detalle de reserva específico solo si pertenece al usuario autenticado.
     */
    public function show($id)
    {
        $userId = Auth::id();

        // Buscar el detalle de la reserva con la validación de usuario propietario
        $detail = ReserveDetail::with(['emprendimientoService.service', 'reserva'])
            ->where('id', $id)
            ->whereHas('reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        // Si no se encuentra el detalle, retornar error 404
        if (!$detail) {
            return response()->json(['message' => 'Detalle de reserva no encontrado o no autorizado'], 404);
        }

        return response()->json($detail);
    }

    /**
     * Actualizar un detalle de reserva.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        // Buscar el detalle de la reserva con la validación de usuario propietario
        $detail = ReserveDetail::where('id', $id)
            ->whereHas('reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->first();

        // Si no se encuentra el detalle, retornar error 404
        if (!$detail) {
            return response()->json(['message' => 'Detalle de reserva no encontrado o no autorizado'], 404);
        }

        // Validaciones de los campos
        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'cantidad' => 'nullable|numeric|min:1',
            'costo' => 'nullable|numeric|min:0',
            'igv' => 'nullable|numeric|min:0',
            'bi' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'lugar' => 'nullable|string|max:255',
        ]);

        // Actualizar los datos del detalle
        $detail->update($validated);

        return response()->json([
            'message' => 'Detalle de reserva actualizado correctamente',
            'detail' => $detail,
        ]);
    }

    /**
     * Eliminar un detalle de reserva (Soft Delete).
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        // Buscar el detalle de la reserva con la validación de usuario propietario
        $detail = ReserveDetail::where('id', $id)
            ->whereHas('reserva', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        // Si no se encuentra el detalle, retornar error 404
        if (!$detail) {
            return response()->json(['message' => 'Detalle de reserva no encontrado o no autorizado'], 404);
        }

        // Eliminar el detalle (Soft Delete)
        $detail->delete();

        return response()->json(['message' => 'Detalle de reserva eliminado correctamente']);
    }
}
