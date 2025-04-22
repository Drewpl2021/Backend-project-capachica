<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Municipalidad;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MunicipalidadController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $name = $request->input('name');

        $query = Municipalidad::query();

        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        $data = $query->paginate($size);

        // Realizar la paginación de la consulta
        $data = $query->paginate($size);
        $municipalidades = Municipalidad::all();
        return $this->successResponse($municipalidades, 'Municipalidades obtenidas exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos entrantes
        $validated = $request->validate([
            'distrito' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',

        ]);

        // Crear la municipalidad y asignar automáticamente el UUID
        $municipalidad = Municipalidad::create($validated);

        // Devolver la respuesta de éxito
        return $this->successResponse($municipalidad, 'Municipalidad creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $municipalidad = Municipalidad::find($id);

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        return $this->successResponse($municipalidad, 'Municipalidad encontrada');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $municipalidad = Municipalidad::find($id);

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        $validated = $request->validate([
            'distrito' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        $municipalidad->update($validated);
        return $this->successResponse($municipalidad, 'Municipalidad actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Eliminar una municipalidad
    public function destroy($id)
    {
        $municipalidad = Municipalidad::find($id);

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        $municipalidad->delete();
        return $this->successResponse([], 'Municipalidad eliminada exitosamente');
    }

    public function searchByCode($codigo)
    {
        // Buscar la municipalidad por el campo 'codigo'
        $municipalidad = Municipalidad::where('codigo', $codigo)->first(); // Encuentra el primer registro con el código

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        // Devolver todos los campos de la municipalidad encontrada
        return $this->successResponse($municipalidad, 'Municipalidad encontrada');
    }
}
