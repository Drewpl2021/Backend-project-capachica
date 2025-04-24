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

        // Crear la consulta base
        $query = Municipalidad::query();

        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        // Obtener los resultados paginados
        $municipalidades = $query->paginate($size);

        // Formatear los datos antes de enviarlos
        $response = collect($municipalidades->items())->map(function ($municipalidad) {
            return [
                'id' => $municipalidad->id,
                'distrito' => $municipalidad->distrito,
                'provincia' => $municipalidad->provincia,
                'region' => $municipalidad->region,
                'codigo' => $municipalidad->codigo,
                'createdAt' => $municipalidad->created_at,
                'updatedAt' => $municipalidad->updated_at,
                'deletedAt' => $municipalidad->deleted_at,
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $response->total(),
            'currentPage' => $response->currentPage() - 1,
            'totalPages' => $response->lastPage(),
            'perPage' => $response->perPage(),
        ]);
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
        return response()->json($municipalidad);
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

        return response()->json($municipalidad);
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
        return response()->json($municipalidad);
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
        return response()->json($municipalidad);
    }

    public function searchByCode($codigo)
    {
        // Buscar la municipalidad por el campo 'codigo'
        $municipalidad = Municipalidad::where('codigo', $codigo)->first(); // Encuentra el primer registro con el código

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        // Devolver todos los campos de la municipalidad encontrada
        return response()->json($municipalidad);
    }
}
