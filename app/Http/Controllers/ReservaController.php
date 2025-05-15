<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Listar todas las reservas del usuario autenticado con detalles.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $userId = Auth::id();

        $reservas = Reserva::where('user_id', $userId)
            ->with([
                'reserveDetails.emprendimientoService.service',
                'user'
            ])
            ->paginate($size);

        return response()->json([
            'content' => $reservas->items(),
            'totalElements' => $reservas->total(),
            'currentPage' => $reservas->currentPage(),
            'totalPages' => $reservas->lastPage(),
        ]);
    }


    /**
     * Crear reserva + detalles (en transacción para evitar inconsistencias).
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'code' => 'nullable|string',
            'total' => 'required|numeric',
            'bi' => 'nullable|numeric',
            'igv' => 'nullable|numeric',
            'details' => 'required|array|min:1',
            'details.*.emprendedor_service_id' => 'required|uuid|exists:emprendedor_service,id',
            'details.*.description' => 'required|string',
            'details.*.costo' => 'required|numeric',
            'details.*.IGV' => 'nullable|numeric',
            'details.*.BI' => 'nullable|numeric',
            'details.*.total' => 'required|numeric',
            'details.*.lugar' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Crear la reserva principal
            $reserva = Reserva::create([
                'user_id' => $userId,
                'code' => $validated['code'] ?? null,
                'total' => $validated['total'],
                'bi' => $validated['bi'] ?? 0,
                'igv' => $validated['igv'] ?? 0,
            ]);

            // Crear cada detalle asociado
            foreach ($validated['details'] as $detail) {
                $reserva->reserveDetails()->create([
                    'emprendedor_service_id' => $detail['emprendedor_service_id'],
                    'description' => $detail['description'],
                    'costo' => $detail['costo'],
                    'IGV' => $detail['IGV'] ?? 0,
                    'BI' => $detail['BI'] ?? 0,
                    'total' => $detail['total'],
                    'lugar' => $detail['lugar'],
                ]);
            }

            DB::commit();

            return response()->json($reserva->load('reserveDetails'), 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Error creando reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una reserva con sus detalles, solo del usuario autenticado.
     */
    public function show($id)
    {
        $userId = Auth::id();

        $reserva = Reserva::with('reserveDetails.emprendimientoService.service')
            ->where('user_id', $userId)
            ->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        return response()->json($reserva);
    }

    /**
     * Actualizar datos generales de la reserva (sin detalles).
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $reserva = Reserva::where('user_id', $userId)->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $validated = $request->validate([
            'code' => 'nullable|string',
            'total' => 'nullable|numeric',
            'bi' => 'nullable|numeric',
            'igv' => 'nullable|numeric',
        ]);

        $reserva->update($validated);

        return response()->json($reserva);
    }

    /**
     * Eliminar una reserva junto con sus detalles (por cascade).
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $reserva = Reserva::where('user_id', $userId)->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $reserva->delete();

        return response()->json(['message' => 'Reserva eliminada correctamente']);
    }
}
