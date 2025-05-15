<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\asociacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsociacionController extends Controller
{
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

        // Obtener las asociaciones con sus imágenes relacionadas y paginación
        $asociaciones = $query->with('imgAsociacions') // Aquí estamos cargando las imágenes
            ->paginate($size);

        // Depuración: Verificar si se encontraron asociaciones
        if ($asociaciones->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron asociaciones.',
                'content' => [],
                'totalElements' => 0,
                'currentPage' => 0,
                'totalPages' => 0,
                'perPage' => $size
            ], 404);
        }

        // Formatear los datos antes de enviarlos
        $response = collect($asociaciones->items())->map(function ($asociacion) {
            return [
                'id' => $asociacion->id,
                'nombre' => $asociacion->nombre,
                'descripcion' => $asociacion->descripcion,
                'lugar' => $asociacion->lugar,
                'estado' => (bool) $asociacion->estado,  // Convertir a booleano
                'municipalidadId' => $asociacion->municipalidad_id,
                'imagenes' => $asociacion->imgAsociacions->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'url_image' => $img->url_image, // Incluyendo la URL de la imagen
                        'estado' => (bool) $img->estado, // Convertir a booleano
                        'codigo' => $img->codigo, // Código asociado (si existe)
                    ];
                }),
                'createdAt' => $asociacion->created_at,
                'updatedAt' => $asociacion->updated_at,
                'deletedAt' => $asociacion->deleted_at,
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $asociaciones->total(),
            'currentPage' => $asociaciones->currentPage(),
            'totalPages' => $asociaciones->lastPage(),
            'perPage' => $asociaciones->perPage(),
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
        return response()->json($asociacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        return response()->json($asociacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        // Depuración: Mostrar los datos antes de la actualización
        Log::info("Actualizando Asociación con ID: $id");
        Log::info("Datos antes de la actualización: ", $asociacion->toArray());
        Log::info("Datos enviados al servidor: ", $validated);

        $asociacion->update($validated);

        // Depuración: Confirmar que los datos se actualizaron correctamente
        log::info("Datos después de la actualización: ", $asociacion->toArray());

        return response()->json($asociacion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        $asociacion->delete();
        return response()->json([
            'message' => 'Asociación eliminada exitosamente'
        ]);
    }

    /**
     * Método auxiliar para responder con un error.
     */
    private function errorResponse($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'error' => 'Ocurrió un error al procesar la solicitud'
        ], $statusCode);
    }

    /**
     * Método auxiliar para responder con éxito.
     */
    private function successResponse($data, $message)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], 200);
    }

    // En el archivo AsociacionController.php

    // En el archivo AsociacionController.php

    public function emprendedoresByAsociacion($id, Request $request)
    {
        // Número de elementos por página (por defecto 10)
        $size = $request->input('size', 10);

        // Buscar la asociación por ID
        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        // Obtener los emprendedores asociados a esta asociación con paginación
        $emprendedores = $asociacion->emprendedores() // Relación de emprendedores de la asociación
            ->paginate($size);

        // Verificar si se encontraron emprendedores
        if ($emprendedores->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron emprendedores asociados a esta asociación.',
                'content' => [],
                'totalElements' => 0,
                'currentPage' => 0,
                'totalPages' => 0,
                'perPage' => $size
            ], 404);
        }

        // Formatear los datos antes de enviarlos
        $response = collect($emprendedores->items())->map(function ($emprendedor) {
            return [
                'id' => $emprendedor->id,
                'razon_social' => $emprendedor->razon_social,
                'address' => $emprendedor->address,
                'user_id' => $emprendedor->user_id,
                'code' => $emprendedor->code,
                'ruc' => $emprendedor->ruc,
                'description' => $emprendedor->description,
                'lugar' => $emprendedor->lugar,
                'img_logo' => $emprendedor->img_logo,
                'name_family' => $emprendedor->name_family,
                'estado' => (bool) $emprendedor->estado, // Convertir a booleano
                'asociacionId' => $emprendedor->asociacion_id,
                'createdAt' => $emprendedor->created_at,
                'updatedAt' => $emprendedor->updated_at,
                'deletedAt' => $emprendedor->deleted_at,
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $emprendedores->total(),
            'currentPage' => $emprendedores->currentPage(),
            'totalPages' => $emprendedores->lastPage(),
            'perPage' => $emprendedores->perPage(),
        ]);
    }
}
