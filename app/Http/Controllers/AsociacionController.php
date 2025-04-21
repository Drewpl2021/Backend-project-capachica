<?php

namespace App\Http\Controllers;

use App\Models\asociacion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AsociacionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asociaciones = Asociacion::all();
        return $this->successResponse($asociaciones, 'Asociaciones obtenidas exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id', // Relación con la municipalidad
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        $asociacion = Asociacion::create($validated);
        return $this->successResponse($asociacion, 'Asociación creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return $this->errorResponse('Asociación no encontrada', 404);
        }

        return $this->successResponse($asociacion, 'Asociación encontrada');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return $this->errorResponse('Asociación no encontrada', 404);
        }

        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        $asociacion->update($validated);
        return $this->successResponse($asociacion, 'Asociación actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return $this->errorResponse('Asociación no encontrada', 404);
        }

        $asociacion->delete();
        return $this->successResponse([], 'Asociación eliminada exitosamente');
    }
}
