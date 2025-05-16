<?php

namespace App\Http\Controllers\API\home;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\emprendedor;
use App\Models\EmprendedorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmprendedorController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $name = $request->input('name');

        // Cargamos relación asociacion, servicios y imágenes
        $query = Emprendedor::with(['asociacion', 'services', 'imgEmprendedores']);

        if ($name) {
            $query->where('razon_social', 'like', "%$name%");
        }

        $emprendedores = $query->paginate($size);

        $response = collect($emprendedores->items())->map(function ($emprendedor) {
            return [
                'id' => $emprendedor->id,
                'razonSocial' => $emprendedor->razon_social,
                'asociacionId' => $emprendedor->asociacion_id,
                'nombre_asociacion' => $emprendedor->asociacion->nombre ?? null,
                'createdAt' => $emprendedor->created_at,
                'updatedAt' => $emprendedor->updated_at,
                'deletedAt' => $emprendedor->deleted_at,
                'imagenes' => $emprendedor->imgEmprendedores->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'url_image' => $img->url_image, // Incluyendo la URL de la imagen
                        'estado' => (bool) $img->estado, // Convertir a booleano
                        'code' => $img->code, // Código asociado (si existe)
                    ];
                }),
                'services' => $emprendedor->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name ?? null,
                        'description' => $service->description ?? null,
                        'code' => $service->pivot->code ?? null,
                        'status' => $service->pivot->status ?? null,
                    ];
                }),
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $emprendedores->total(),
            'currentPage' => $emprendedores->currentPage(), // sin restar 1
            'totalPages' => $emprendedores->lastPage(),
            'perPage' => $emprendedores->perPage(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id', // Relación con la asociación
            'razon_social' => 'required|string|max:255',
        ]);

        $emprendedor = Emprendedor::create($validated);
        return $this->successResponse($emprendedor, 'Emprendedor creado exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $emprendedor = Emprendedor::find($id);

        if (!$emprendedor) {
            return $this->errorResponse('Emprendedor no encontrado', 404);
        }

        return response()->json($emprendedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $emprendedor = Emprendedor::find($id);

        if (!$emprendedor) {
            return $this->errorResponse('Emprendedor no encontrado', 404);
        }

        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id',
            'razon_social' => 'required|string|max:255',
            'new_column' => 'nullable|integer',
        ]);

        $emprendedor->update($validated);
        return $this->successResponse($emprendedor, 'Emprendedor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $emprendedor = Emprendedor::find($id);

        if (!$emprendedor) {
            return $this->errorResponse('Emprendedor no encontrado', 404);
        }

        $emprendedor->delete();
        return $this->successResponse([], 'Emprendedor eliminado exitosamente');
    }


    public function asignarServicios(Request $request, $id)
    {
        // Normalizar inputs a arrays si vienen individuales
        if ($request->has('service_id') && !is_array($request->input('service_id'))) {
            $request->merge([
                'service_id' => [$request->input('service_id')],
                'cantidad' => $request->has('cantidad') && !is_array($request->input('cantidad')) ? [$request->input('cantidad')] : $request->input('cantidad'),
                'costo' => $request->has('costo') && !is_array($request->input('costo')) ? [$request->input('costo')] : $request->input('costo'),
                'costo_unidad' => $request->has('costo_unidad') && !is_array($request->input('costo_unidad')) ? [$request->input('costo_unidad')] : $request->input('costo_unidad'),
                'name' => $request->has('name') && !is_array($request->input('name')) ? [$request->input('name')] : $request->input('name'),
                'description' => $request->has('description') && !is_array($request->input('description')) ? [$request->input('description')] : $request->input('description'),
            ]);
        }

        // Validaciones
        $validated = $request->validate([
            'service_id' => 'required|array|min:1',
            'service_id.*' => 'required|uuid|exists:services,id',
            'costo' => 'sometimes|array',
            'costo.*' => 'numeric|min:0',
            'costo_unidad' => 'sometimes|array',
            'costo_unidad.*' => 'numeric|min:0',
            'name' => 'sometimes|array',
            'name.*' => 'string|max:255',
            'description' => 'sometimes|array',
            'description.*' => 'nullable|string|max:500',
        ]);

        $emprendedor = Emprendedor::find($id);
        if (!$emprendedor) {
            return response()->json(['error' => 'Emprendedor no encontrado'], 404);
        }

        $insertedItems = [];

        foreach ($validated['service_id'] as $index => $serviceId) {
            $name = $validated['name'][$index] ?? null;
            $description = $validated['description'][$index] ?? null;

            // Verificar si ya existe un registro con el mismo emprendedor, servicio, name y description
            $exists = EmprendedorService::where('emprendedor_id', $emprendedor->id)
                ->where('service_id', $serviceId)
                ->where('name', $name)
                ->where('description', $description)
                ->exists();

            if ($exists) {
                // Ya existe, no creamos y saltamos
                continue;
            }

            // Crear nuevo registro único
            $item = EmprendedorService::create([
                'id' => (string) Str::uuid(),
                'service_id' => $serviceId,
                'emprendedor_id' => $emprendedor->id,
                'code' => 'CODE-' . strtoupper(Str::random(5)),
                'status' => true,
                'cantidad' => $validated['cantidad'][$index] ?? null,
                'costo' => $validated['costo'][$index] ?? 0,
                'costo_unidad' => $validated['costo_unidad'][$index] ?? null,
                'name' => $name,
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $insertedItems[] = $item;
        }

        return response()->json([
            'message' => 'Servicios asignados exitosamente',
            'emprendedor_id' => $emprendedor->id,
            'assigned_services' => $insertedItems,
        ]);
    }

    public function getByUserId($userId)
    {
        // Buscar el emprendedor_id en la tabla emprendedor_user por user_id
        $emprendedorUser = DB::table('emprendedor_user')->where('user_id', $userId)->first();

        if (!$emprendedorUser) {
            return $this->errorResponse('No se encontró emprendedor asociado a este usuario', 404);
        }

        // Buscar el emprendedor con relaciones
        $emprendedor = Emprendedor::with(['asociacion', 'services', 'imgEmprendedores'])
            ->find($emprendedorUser->emprendedor_id);

        if (!$emprendedor) {
            return $this->errorResponse('Emprendedor no encontrado', 404);
        }

        // Preparar la respuesta como en index()
        $response = [
            'id' => $emprendedor->id,
            'razonSocial' => $emprendedor->razon_social,
            'asociacionId' => $emprendedor->asociacion_id,
            'nombre_asociacion' => $emprendedor->asociacion->nombre ?? null,
            'createdAt' => $emprendedor->created_at,
            'updatedAt' => $emprendedor->updated_at,
            'deletedAt' => $emprendedor->deleted_at,
            'imagenes' => $emprendedor->imgEmprendedores->map(function ($img) {
                return [
                    'id' => $img->id,
                    'url_image' => $img->url_image,
                    'estado' => (bool) $img->estado,
                    'code' => $img->code,
                ];
            }),
            'services' => $emprendedor->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name ?? null,
                    'description' => $service->description ?? null,
                    'code' => $service->pivot->code ?? null,
                    'status' => $service->pivot->status ?? null,
                ];
            }),
        ];

        return response()->json($response);
    }

    public function reporteVentas($emprendedorId, Request $request)
    {
        $size = $request->input('size', 10);

        // Validar que el emprendedor exista
        $emprendedor = Emprendedor::find($emprendedorId);
        if (!$emprendedor) {
            return response()->json(['message' => 'Emprendedor no encontrado'], 404);
        }

        // Consultar ventas con sus detalles y relaciones
        $ventas = Sale::with(['saleDetails.emprendimientoService.service', 'payment', 'reserva'])
            ->where('emprendedor_id', $emprendedorId)
            ->orderBy('created_at', 'desc')
            ->paginate($size);

        $response = collect($ventas->items())->map(function ($venta) {
            return [
                'id' => $venta->id,
                'code' => $venta->code,
                'IGV' => $venta->IGV,
                'BI' => $venta->BI,
                'total' => $venta->total,
                'createdAt' => $venta->created_at,
                'updatedAt' => $venta->updated_at,
                'reserva' => [
                    'id' => $venta->reserva->id ?? null,
                    'code' => $venta->reserva->code ?? null,
                    'status' => $venta->reserva->status ?? null,
                ],
                'payment' => [
                    'id' => $venta->payment->id ?? null,
                    'code' => $venta->payment->code ?? null,
                    'total' => $venta->payment->total ?? null,
                ],
                'detalles' => $venta->saleDetails->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'description' => $detalle->description,
                        'costo' => $detalle->costo,
                        'IGV' => $detalle->IGV,
                        'BI' => $detalle->BI,
                        'total' => $detalle->total,
                        'lugar' => $detalle->lugar,
                        'emprendimiento_service' => [
                            'id' => $detalle->emprendimientoService->id ?? null,
                            'name' => $detalle->emprendimientoService->name ?? null,
                            'service' => [
                                'id' => $detalle->emprendimientoService->service->id ?? null,
                                'name' => $detalle->emprendimientoService->service->name ?? null,
                            ],
                        ],
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'Reporte de ventas por emprendedor',
            'emprendedorNombre' => $emprendedor->razon_social,
            'nombre_familia' => $emprendedor->name_family,
            'content' => $response,
            'totalElements' => $ventas->total(),
            'currentPage' => $ventas->currentPage(),
            'totalPages' => $ventas->lastPage(),
        ]);
    }

    public function reservasPorEmprendedor($emprendedorId)
    {
        $emprendedor = Emprendedor::find($emprendedorId);
        if (!$emprendedor) {
            return response()->json(['message' => 'Emprendedor no encontrado'], 404);
        }

        // IDs de servicios del emprendedor
        $emprendedorServiceIds = $emprendedor->emprendedorServices()->pluck('id');

        // Obtener todos los detalles relacionados a esos emprendedor_service_id con su reserva y servicio
        $reserveDetails = \App\Models\ReserveDetail::with(['reserva', 'emprendimientoService'])
            ->whereIn('emprendedor_service_id', $emprendedorServiceIds)
            ->get();

        // Agrupar por emprendimiento_service_id para la estructura deseada
        $agrupado = $reserveDetails->groupBy('emprendedor_service_id')->map(function ($detalles, $serviceId) {
            $primerDetalle = $detalles->first();
            $servicio = $primerDetalle->emprendimientoService;

            return [
                'emprendimiento_service' => [
                    'id' => $servicio->id,
                    'name' => $servicio->name,
                    'code' => $servicio->code,
                    'costo' => $servicio->costo,
                ],
                'emprendimiento_service_detalle' => $detalles->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'cantidad' => $detalle->cantidad,
                        'lugar' => $detalle->lugar,
                        'description' => $detalle->description,
                        'reserva' => [
                            'id' => $detalle->reserva->id ?? null,
                            'code' => $detalle->reserva->code ?? null,
                            'status' => $detalle->reserva->status ?? null,
                            'total' => $detalle->reserva->total ?? null,
                        ],
                    ];
                }),
            ];
        })->values();

        return response()->json([
            'emprendedor_id' => $emprendedor->id,
            'razon_social' => $emprendedor->razon_social,
            'reservas' => $agrupado,
        ]);
    }






}
