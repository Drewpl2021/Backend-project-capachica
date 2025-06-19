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
        $size = $request->input('size', 10);  // Número de resultados por página
        $userId = Auth::id();  // ID del usuario autenticado
        $search = $request->input('search');  // Parámetro de búsqueda para `code`

        // Comenzamos la consulta para obtener las reservas del usuario
        $query = Reserva::where('user_id', $userId)
            ->with([
                'reserveDetails.emprendimientoService.service',
                'user'
            ]);

        // Si se ha pasado el parámetro de búsqueda, filtramos por `code`
        if ($search) {
            $query->where('code', 'like', "%$search%");
        }

        // Ejecutamos la consulta con paginación
        $reservas = $query->paginate($size);

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
    /*public function store(Request $request)
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
            'details.*.igv' => 'nullable|numeric|min:0',
            'details.*.bi' => 'nullable|numeric|min:0',
            'details.*.total' => 'nullable|numeric|min:0',
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
                    'igv' => $detail['igv'] ?? 0,  // toma del input
                    'bi' => $detail['bi'] ?? 0,    // toma del input
                    'total' => $detail['total'] ?? 0, // toma del input
                    'lugar' => $detail['lugar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Insertar detalles
            $reserva->reserveDetails()->insert($detailsData);

            DB::commit();

            // Carga la relación para la respuesta
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
    }*/



    public function store(Request $request)
    {
        $userId = Auth::id(); // ID del usuario autenticado

        $validated = $request->validate([
            'details.*.cantidad' => 'required|numeric|min:1',
            'details' => 'required|array|min:1',
            'details.*.emprendedor_service_id' => 'required|uuid|exists:emprendedor_service,id',
            'details.*.lugar' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Generar el código de la reserva
            $lastCode = Reserva::where('code', 'like', 'CO_%')
                ->orderByDesc('created_at')
                ->first();

            $nextCode = 'CO_001'; // Valor por defecto si no hay códigos previos

            if ($lastCode) {
                $lastCodeNumber = (int)str_replace('CO_', '', $lastCode->code);
                $nextCode = 'CO_' . str_pad($lastCodeNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Calcular el total, bi, y igv a partir de los detalles
            $total = 0;
            $bi = 0;
            $igv = 0;

            // Mapeamos los detalles de la reserva
            $detailsData = collect($validated['details'])->map(function ($detail) use ($userId, &$total, &$bi, &$igv) {
                $emprendedorService = \App\Models\EmprendedorService::find($detail['emprendedor_service_id']);

                if (!$emprendedorService) {
                    throw new \Exception("El servicio con ID {$detail['emprendedor_service_id']} no existe.");
                }

                if ($emprendedorService->cantidad < $detail['cantidad']) {
                    throw new \Exception("No hay suficiente cantidad disponible para el servicio con ID {$detail['emprendedor_service_id']}.");
                }

                $emprendedorService->cantidad -= $detail['cantidad'];
                $emprendedorService->save();

                // Calcular el total, bi, y igv para este detalle
                $detalleTotal = $emprendedorService->costo * $detail['cantidad'];
                $detalleBi = $detalleTotal / (1 + (18 / 100)); // Base imponible
                $detalleIgv = $detalleTotal - $detalleBi; // IGV

                $total += $detalleTotal;
                $bi += $detalleBi;
                $igv += $detalleIgv;

                return [
                    'id' => (string) Str::uuid(),
                    'emprendedor_service_id' => $emprendedorService->id,
                    'description' => $emprendedorService->description,
                    'cantidad' => $detail['cantidad'],
                    'costo' => $emprendedorService->costo,
                    'total' => $detalleTotal,
                    'bi' => $detalleBi,
                    'igv' => $detalleIgv,
                    'lugar' => $detail['lugar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Crear la reserva principal con los valores calculados
            $reserva = Reserva::create([
                'user_id' => $userId,
                'code' => $nextCode,
                'total' => $total,
                'bi' => $bi,
                'igv' => $igv,
            ]);

            // Insertar los detalles de la reserva
            $reserva->reserveDetails()->insert($detailsData);

            DB::commit();

            // Cargar las relaciones para la respuesta
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
     * Actualizar datos generales de la reserva (sin detalles).
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id(); // ID del usuario autenticado

        $reserva = Reserva::where('user_id', $userId)->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar que la reserva esté en estado 'pendiente' para permitir modificaciones
        if ($reserva->status !== 'pendiente') {
            return response()->json(['message' => 'No se puede editar una reserva que ya está pagada o cancelada'], 400);
        }

        $validated = $request->validate([
            'code' => 'nullable|string',
            'total' => 'nullable|numeric',
            'bi' => 'nullable|numeric',
            'igv' => 'nullable|numeric',
            'details' => 'nullable|array', // Permite detalles opcionales
            'details.*.id' => 'nullable|uuid|exists:reserve_details,id', // Si vamos a editar detalles existentes
            'details.*.cantidad' => 'nullable|numeric|min:1',
            'details.*.emprendedor_service_id' => 'nullable|uuid|exists:emprendedor_service,id', // Si modificamos el servicio
            'details.*.lugar' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar los datos generales de la reserva
            $reserva->update($validated);

            // Si hay detalles que actualizar o agregar
            if (isset($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    if (isset($detail['id'])) {
                        $reserveDetail = $reserva->reserveDetails()->find($detail['id']);

                        if ($reserveDetail) {
                            // Actualizar los campos del detalle
                            $reserveDetail->update([
                                'cantidad' => $detail['cantidad'],
                                'emprendedor_service_id' => $detail['emprendedor_service_id'],
                                'lugar' => $detail['lugar'],
                            ]);
                        }
                    } else {
                        // Si no existe un ID, significa que estamos creando un nuevo detalle
                        $reserva->reserveDetails()->create([
                            'emprendedor_service_id' => $detail['emprendedor_service_id'],
                            'cantidad' => $detail['cantidad'],
                            'lugar' => $detail['lugar'],
                        ]);
                    }
                }
            }

            DB::commit(); // Confirmar la transacción

            return response()->json($reserva);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return response()->json([
                'error' => 'Error actualizando reserva: ' . $e->getMessage(),
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
