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
    public function index()
    {
        $emprendedores = Emprendedor::all();
        return $this->successResponse($emprendedores, 'Emprendedores obtenidos exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id', // Relación con la asociación
            'razon_social' => 'required|string|max:255',
            'new_column' => 'nullable|integer',
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

        return $this->successResponse($emprendedor, 'Emprendedor encontrado');
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
}
