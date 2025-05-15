<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\emprendedor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmprendedorController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el número de elementos por página, por defecto 10
        $size = $request->input('size', 10);
        $name = $request->input('name');

        // Crear la consulta base
        $query = Emprendedor::with('asociacion');  // Cargar la relación con la asociación

        // Si se pasa un nombre, aplicar filtro
        if ($name) {
            $query->where('razon_social', 'like', "%$name%");
        }

        // Obtener los emprendedores paginados
        $emprendedores = $query->paginate($size);

        // Formatear los datos antes de enviarlos
        $response = collect($emprendedores->items())->map(function ($emprendedor) {
            return [
                'id' => $emprendedor->id,
                'razonSocial' => $emprendedor->razon_social,
                'asociacionId' => $emprendedor->asociacion_id,
                'nombre_asociacion' => $emprendedor->asociacion->nombre, // Obtener el nombre de la asociación
                'newColumn' => $emprendedor->new_column,
                'createdAt' => $emprendedor->created_at,
                'updatedAt' => $emprendedor->updated_at,
                'deletedAt' => $emprendedor->deleted_at,
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $emprendedores->total(),
            'currentPage' => $emprendedores->currentPage() - 1,
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
        $validated = $request->validate([
            'service_id' => 'required|array|min:1',
            'service_id.*' => 'required|uuid|exists:services,id',
        ]);

        $emprendedor = Emprendedor::find($id);
        if (!$emprendedor) {
            return response()->json(['error' => 'Emprendedor no encontrado'], 404);
        }

        // Para cada service_id solo asignar con valores por defecto en pivote
        $syncData = [];
        foreach ($validated['service_id'] as $serviceId) {
            $syncData[$serviceId] = [
                'id' => \Illuminate\Support\Str::uuid(),
                'cantidad' => 1,
                'code' => null,
                'name' => null,
                'description' => null,
            ];
        }

        $emprendedor->services()->sync($syncData);

        return response()->json([
            'message' => 'Servicios asignados exitosamente',
            'emprendedor_id' => $emprendedor->id,
            'services' => $emprendedor->services()->withPivot()->get(),
        ]);
    }
}
