<?php

namespace App\Http\Controllers\API\home;

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

        // Cargamos relación asociacion y services (con campos pivote)
        $query = Emprendedor::with(['asociacion', 'services']);

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

                // Agregamos servicios con sus datos y pivot
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
            'currentPage' => $emprendedores->currentPage() - 1,
            'totalPages' => $emprendedores->lastPage(),
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
}
