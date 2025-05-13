<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Municipalidad;
use Illuminate\Http\Request;

class MunicipalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $name = $request->input('name');

        $query = Municipalidad::query();

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->where('distrito', 'like', "%$name%")
                    ->orWhere('provincia', 'like', "%$name%")
                    ->orWhere('region', 'like', "%$name%")
                    ->orWhere('codigo', 'like', "%$name%");
            });
        }

        // Obtener los resultados paginados
        $municipalidades = $query->with('slider_munis')  // Cargar la relación con sliders
            ->paginate($size);

        // Mapear los resultados de la paginación
        $response = $municipalidades->items();  // Obtienes los items de la paginación directamente

        // Formatear la respuesta
        $response = collect($response)->map(function ($municipalidad) {
            // Aquí agregamos los sliders a la respuesta de cada municipalidad
            $sliders = $municipalidad->slider_munis->map(function ($slider) {
                return [
                    'id' => $slider->id,
                    'titulo' => $slider->titulo,
                    'descripcion' => $slider->descripcion,
                    'url_images' => $slider->url_images,
                ];
            });

            return [
                'id' => $municipalidad->id,
                'distrito' => $municipalidad->distrito,
                'provincia' => $municipalidad->provincia,
                'region' => $municipalidad->region,
                'codigo' => $municipalidad->codigo,
                'createdAt' => $municipalidad->created_at,
                'updatedAt' => $municipalidad->updated_at,
                'deletedAt' => $municipalidad->deleted_at,
                'sliders' => $sliders, // Incluir los sliders en la respuesta
            ];
        });

        // Retornar la respuesta con los datos paginados
        return response()->json([
            'content' => $response,
            'totalElements' => $municipalidades->total(),
            'currentPage' => $municipalidades->currentPage(),
            'totalPages' => $municipalidades->lastPage(),
            'perPage' => $municipalidades->perPage(),
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

        // Validar los datos entrantes
        $validated = $request->validate([
            'distrito' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
        ]);

        // Actualizar el modelo con los nuevos valores
        $municipalidad->update($validated);

        // Devolver la respuesta con los datos actualizados
        return response()->json([
            'id' => $municipalidad->id,
            'distrito' => $municipalidad->distrito,
            'provincia' => $municipalidad->provincia,
            'region' => $municipalidad->region,
            'codigo' => $municipalidad->codigo,
        ]);
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

    public function asociacionesByMunicipalidad($id)
    {
        $municipalidad = Municipalidad::with('asociaciones')->find($id);

        if (!$municipalidad) {
            return $this->errorResponse('Municipalidad no encontrada', 404);
        }

        return response()->json([
            'municipalidad' => [
                'id' => $municipalidad->id,
                'distrito' => $municipalidad->distrito,
                'provincia' => $municipalidad->provincia,
                'region' => $municipalidad->region,
                'codigo' => $municipalidad->codigo,
            ],
            'asociaciones' => $municipalidad->asociaciones,
        ]);
    }
}
