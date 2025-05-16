<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'details.*.cantidad' => 'required|numeric|min:1',
            'details' => 'required|array|min:1',
            'details.*.emprendedor_service_id' => 'required|uuid|exists:emprendedor_service,id',
            'details.*.lugar' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Crear reserva principal
            $reserva = Reserva::create([
                'user_id' => $userId,
                'code' => $validated['code'] ?? null,
                'total' => $validated['total'],
                'bi' => $validated['bi'] ?? 0,
                'igv' => $validated['igv'] ?? 0,
            ]);

            // Mapear detalles con datos obtenidos desde emprendedor_service
            $detailsData = collect($validated['details'])->map(function ($detail) use ($reserva) {
                // Buscar emprendedor_service para el detalle
                $emprendedorService = \App\Models\EmprendedorService::find($detail['emprendedor_service_id']);

                if (!$emprendedorService) {
                    throw new \Exception("El servicio con ID {$detail['emprendedor_service_id']} no existe.");
                }

                return [
                    'id' => (string) Str::uuid(),
                    'emprendedor_service_id' => $emprendedorService->id,
                    'reserva_id' => $reserva->id,
                    'description' => $emprendedorService->description,  // toma la descripción de la tabla emprendedor_service
                    'cantidad' => $detail['cantidad'],
                    'costo' => $emprendedorService->costo, // toma el costo actual del servicio
                    'igv' => 0,  // si quieres lo calculas después o lo pones en 0
                    'bi' => 0,   // igual
                    'total' => 0, // igual, puedes calcular o ajustar aquí
                    'lugar' => $detail['lugar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Insertar detalles
            $reserva->reserveDetails()->insert($detailsData);

            DB::commit();

            $reserva->load('reserveDetails');

            return response()->json([
                'message' => 'Reserva y detalles creados exitosamente',
                'reserva' => $reserva,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error creando reserva: ' . $e->getMessage(),
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
