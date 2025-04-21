<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Municipalidad_Descripcion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MunicipalidadDescripcionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $descripciones = Municipalidad_Descripcion::all();
        return $this->successResponse($descripciones, 'Descripciones obtenidas exitosamente');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $municipalidadId)
    {
        $descripcion = Municipalidad_Descripcion::where('municipalidad_id', $municipalidadId)->first();
        if ($descripcion) {
            return $this->errorResponse('Ya existe una descripción para esta municipalidad.', 400);
        }
        $validated = $request->validate([
            'logo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ruc' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'nombre_alcalde' => 'required|string|max:255',
            'anio_gestion' => 'required|string|max:4',
        ]);
        $validated['municipalidad_id'] = $municipalidadId;
        $newDescripcion = Municipalidad_Descripcion::create($validated);

        return $this->successResponse($newDescripcion, 'Descripción de municipalidad creada exitosamente', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return $this->errorResponse('Descripción de municipio no encontrada', 404);
        }

        return $this->successResponse($descripcion, 'Descripción de municipio encontrada');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return $this->errorResponse('Descripción de municipio no encontrada', 404);
        }

        // Validar los datos
        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id', // Asegurarse que la municipalidad existe
            'logo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ruc' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'nombre_alcalde' => 'required|string|max:255',
            'anio_gestion' => 'required|string|max:4',
        ]);

        // Actualizar la descripción
        $descripcion->update($validated);

        return $this->successResponse($descripcion, 'Descripción de municipio actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return $this->errorResponse('Descripción de municipio no encontrada', 404);
        }

        // Eliminar la descripción
        $descripcion->delete();

        return $this->successResponse([], 'Descripción de municipio eliminada exitosamente');
    }
}
