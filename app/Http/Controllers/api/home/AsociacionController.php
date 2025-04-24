<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\asociacion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AsociacionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Número de elementos por página (por defecto 10)
        $size = $request->input('size', 10);
        $name = $request->input('name');

        // Crear la consulta base
        $query = Asociacion::query();

        // Si se pasa un nombre, aplicar filtro
        if ($name) {
            $query->where('nombre', 'like', "%$name%");
        }

        // Obtener los resultados paginados
        $asociaciones = $query->paginate($size);

        // Formatear los datos antes de enviarlos
        $response = $asociaciones->map(function ($asociacion) {
            return [
                'id' => $asociacion->id,
                'nombre' => $asociacion->nombre,
                'descripcion' => $asociacion->descripcion,
                'lugar' => $asociacion->lugar,
                'estado' => $asociacion->estado,
                'municipalidadId' => $asociacion->municipalidad_id,
                'createdAt' => $asociacion->created_at,
                'updatedAt' => $asociacion->updated_at,
                'deletedAt' => $asociacion->deleted_at,
            ];
        });

        return $this->successResponse([
            'content' => $response,
            'totalElements' => $asociaciones->total(),
            'currentPage' => $asociaciones->currentPage() - 1, // Restamos 1 para mantener la numeración desde 0
            'totalPages' => $asociaciones->lastPage(),
        ]);
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
            return $this->errorResponse('Asociación no encontradas', 404);
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
