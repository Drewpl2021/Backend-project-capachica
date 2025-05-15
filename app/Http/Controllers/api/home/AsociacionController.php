<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\asociacion;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsociacionController extends Controller
{
    use SoftDeletes;  // Esto activa soft deletes

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
                'url' => $asociacion->url,
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
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'imagenes' => 'array', // Validar que sea arreglo (opcional)
            'imagenes.*.url_image' => 'required|string|max:255',
            'imagenes.*.estado' => 'required|boolean',
            'imagenes.*.codigo' => 'nullable|string',
            'imagenes.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $asociacion = Asociacion::create($validated);

            // Crear imágenes si llegan
            if (!empty($validated['imagenes'])) {
                foreach ($validated['imagenes'] as $img) {
                    $asociacion->imgAsociacions()->create($img);
                }
            }

            DB::commit();

            return response()->json($asociacion, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear asociación con imágenes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource with all related data.
     */
    public function show($id)
    {
        $asociacion = Asociacion::with('imgAsociacions')->find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        // Formatear la respuesta para devolver todos los campos + imágenes (convertir booleanos)
        $response = [
            'id' => $asociacion->id,
            'nombre' => $asociacion->nombre,
            'descripcion' => $asociacion->descripcion,
            'lugar' => $asociacion->lugar,
            'url' => $asociacion->url,
            'estado' => (bool) $asociacion->estado,
            'municipalidadId' => $asociacion->municipalidad_id,
            'imagenes' => $asociacion->imgAsociacions->map(function ($img) {
                return [
                    'id' => $img->id,
                    'url_image' => $img->url_image,
                    'estado' => (bool) $img->estado,
                    'codigo' => $img->codigo,
                    'createdAt' => $img->created_at,
                    'updatedAt' => $img->updated_at,
                    'deletedAt' => $img->deleted_at,
                ];
            }),
            'createdAt' => $asociacion->created_at,
            'updatedAt' => $asociacion->updated_at,
            'deletedAt' => $asociacion->deleted_at,
        ];

        return response()->json([
            'content' => $response,
        ]);
    }


    public function update(Request $request, $id)
    {
        // Validación básica sin restricciones de código único
        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'imagenes' => 'array',
            'imagenes.*.id' => 'nullable|uuid|exists:img_asociacions,id',
            'imagenes.*.url_image' => 'required|string|max:255',
            'imagenes.*.estado' => 'required|boolean',
            'imagenes.*.codigo' => 'nullable|string',
            'imagenes.*.description' => 'nullable|string',
        ]);

        $asociacion = Asociacion::find($id);
        if (!$asociacion) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        DB::beginTransaction();
        try {
            // Actualizar asociación
            $asociacion->update($validated);

            // Manejo de imágenes: agregar o actualizar
            $imagenesEnviadasIds = collect($validated['imagenes'] ?? [])
                ->pluck('id')
                ->filter()
                ->all();

            // Eliminar imágenes que no están en la petición (soft delete)
            $asociacion->imgAsociacions()
                ->whereNotIn('id', $imagenesEnviadasIds)
                ->delete();

            foreach ($validated['imagenes'] as $imgData) {
                if (isset($imgData['id'])) {
                    $image = $asociacion->imgAsociacions()->withTrashed()->find($imgData['id']);
                    if ($image) {
                        if ($image->trashed()) {
                            $image->restore();
                        }
                        $image->update($imgData);
                    }
                } else {
                    $asociacion->imgAsociacions()->create($imgData);
                }
            }

            DB::commit();

            return response()->json($asociacion);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar asociación con imágenes',
                'error' => $e->getMessage()
            ], 500);
        }
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

        $asociacion->delete(); // Aquí solo marca deleted_at

        return response()->json([
            'message' => 'Asociación eliminada exitosamente'
        ]);
    }




    // En el archivo AsociacionController.php

    public function emprendedoresByAsociacion($id, Request $request)
    {
        $size = $request->input('size', 10);

        $asociacion = Asociacion::find($id);

        if (!$asociacion) {
            return response()->json([
                'message' => 'Asociación no encontrada',
                'error' => 'No se pudo encontrar la asociación con el ID proporcionado'
            ], 404);
        }

        $emprendedores = $asociacion->emprendedores()
            ->with(['services', 'asociacion']) // cargo los servicios y la asociación
            ->paginate($size);


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

        $response = collect($emprendedores->items())->map(function ($emprendedor) {
            return [
                'asociacionId' => $emprendedor->asociacion_id,
                'nombreAsociacion' => $emprendedor->asociacion ? $emprendedor->asociacion->nombre : null,
                'id' => $emprendedor->id,
                'razon_social' => $emprendedor->razon_social,
                'address' => $emprendedor->address,
                'code' => $emprendedor->code,
                'ruc' => $emprendedor->ruc,
                'description' => $emprendedor->description,
                'lugar' => $emprendedor->lugar,
                'img_logo' => $emprendedor->img_logo,
                'name_family' => $emprendedor->name_family,
                'status' => $emprendedor->status,
                'createdAt' => $emprendedor->created_at,
                'updatedAt' => $emprendedor->updated_at,

                // Aquí agregamos los servicios con sus campos que quieres exponer
                'servicios' => $emprendedor->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'description' => $service->description,
                        'code' => $service->code,
                        'category' => $service->category,
                        'status' => $service->status,
                    ];
                }),
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
