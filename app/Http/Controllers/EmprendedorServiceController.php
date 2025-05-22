<?php

namespace App\Http\Controllers;

use App\Models\EmprendedorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmprendedorServiceController extends Controller
{
    /**
     * Listar registros con paginación y búsqueda por 'code' y 'name'.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $search = $request->input('search');
        $emprendedorId = $request->input('emprendedor_id');

        $query = EmprendedorService::query();

        // Filtro por emprendedor_id si viene en la petición
        if ($emprendedorId) {
            $query->where('emprendedor_id', $emprendedorId);
        }

        // Filtro de búsqueda en 'code' o 'name'
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            });
        }

        // Carga relaciones para mostrar info asociada
        $query->with(['emprendedor', 'service', 'imgEmprendedorServices']);

        $results = $query->paginate($size);

        return response()->json([
            'content' => $results->items(),
            'totalElements' => $results->total(),
            'currentPage' => $results->currentPage(),
            'totalPages' => $results->lastPage(),
        ]);
    }



    /**
     * Crear un nuevo registro.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'emprendedor_id' => ['required', 'uuid', 'exists:emprendedors,id'],
            'code' => ['required', 'string', 'unique:emprendedor_service,code'],
            'status' => ['boolean'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'costo' => ['required', 'numeric', 'min:0'],
            'costo_unidad' => ['nullable', 'numeric', 'min:0'],
            'imagenes' => 'array',  // Validación para las imágenes
            'imagenes.*.url_image' => 'required|string|max:255',
            'imagenes.*.description' => 'nullable|string',
            'imagenes.*.estado' => 'required|boolean',
            'imagenes.*.code' => 'nullable|string',
        ]);

        // Iniciar transacción
        DB::beginTransaction();
        try {
            $emprendedorService = EmprendedorService::create($validated);

            // Guardar imágenes si existen en el request
            if (!empty($validated['imagenes'])) {
                foreach ($validated['imagenes'] as $img) {
                    // Asociar las imágenes al 'EmprendedorService' recién creado
                    $emprendedorService->imgEmprendedorServices()->create($img);
                }
            }

            DB::commit();
            return response()->json($emprendedorService, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear emprendedor servicio con imágenes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un registro específico por ID.
     */
    public function show($id)
    {
        $record = EmprendedorService::with(['emprendedor', 'service', 'imgEmprendedorServices'])->find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        return response()->json($record);
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $id)
    {
        $record = EmprendedorService::find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $validated = $request->validate([
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'emprendedor_id' => ['required', 'uuid', 'exists:emprendedors,id'],
            'code' => ['required', 'string', Rule::unique('emprendedor_service', 'code')->ignore($id)],
            'status' => ['boolean'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'costo' => ['required', 'numeric', 'min:0'],
            'costo_unidad' => ['nullable', 'numeric', 'min:0'],
            'imagenes' => 'array',
            'imagenes.*.id' => 'nullable|uuid|exists:img_emprenpedor_service,id',
            'imagenes.*.url_image' => 'required|string|max:255',
            'imagenes.*.estado' => 'required|boolean',
            'imagenes.*.code' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $record->update($validated);

            // Manejar las imágenes
            $imagenesEnviadasIds = collect($validated['imagenes'] ?? [])
                ->pluck('id')
                ->filter()
                ->all();

            // Eliminar imágenes que no están en el request
            $record->imgEmprendedorServices()
                ->whereNotIn('id', $imagenesEnviadasIds)
                ->delete();

            // Crear o actualizar imágenes
            foreach ($validated['imagenes'] as $imgData) {
                if (isset($imgData['id'])) {
                    $image = $record->imgEmprendedorServices()->withTrashed()->find($imgData['id']);
                    if ($image) {
                        if ($image->trashed()) {
                            $image->restore();
                        }
                        $image->update($imgData);
                    }
                } else {
                    $record->imgEmprendedorServices()->create($imgData);
                }
            }

            DB::commit();
            return response()->json($record);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar emprendedor servicio con imágenes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los registros de emprendedor_service filtrados por service_id.
     */

    public function getByService(Request $request)
    {
        $serviceId = $request->input('service_id');
        $size = $request->input('size', 10);

        // Log para mostrar el parámetro recibido
        Log::debug("service_id recibido: $serviceId");

        if (!$serviceId) {
            Log::error("El parámetro service_id es requerido.");
            return response()->json(['message' => 'El parámetro service_id es requerido.'], 422);
        }

        // Verifica si el service_id existe en la base de datos
        $serviceExists = \App\Models\Service::find($serviceId);
        if (!$serviceExists) {
            Log::error("El service_id '$serviceId' no existe en la base de datos.");
            return response()->json(['message' => 'El service_id no existe en la base de datos.'], 404);
        }
        Log::debug("El service_id '$serviceId' existe en la base de datos.");

        // Consulta para traer registros de la tabla 'emprendedor_service'
        $query = EmprendedorService::with(['emprendedor', 'imgEmprendedorServices'])
            ->where('service_id', $serviceId);

        // Log para ver la consulta generada
        Log::debug("Consulta SQL generada: " . $query->toSql(), ['bindings' => $query->getBindings()]);

        // Paginación
        $results = $query->paginate($size);

        Log::debug("Resultados obtenidos: Total de registros: " . $results->total());

        if ($results->total() == 0) {
            Log::info("No se encontraron registros para el service_id '$serviceId'.");
            return response()->json([
                'message' => 'No se encontraron registros para el service_id dado.',
                'content' => [],
                'totalElements' => 0,
                'currentPage' => 1,
                'totalPages' => 0,
                'perPage' => $size,
            ], 404);
        }

        // Mapeo de la respuesta
        $response = collect($results->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'service_id' => $item->service_id,
                'emprendedor_id' => $item->emprendedor_id,
                'emprendedor_razon_social' => $item->emprendedor->razon_social ?? null,
                'emprendedor_address' => $item->emprendedor->address ?? null,
                'code' => $item->code,
                'status' => (bool)$item->status,
                'cantidad' => $item->cantidad,
                'name' => $item->name,
                'description' => $item->description,
                'costo' => $item->costo,
                'costo_unidad' => $item->costo_unidad,
                'createdAt' => $item->created_at,
                'updatedAt' => $item->updated_at,
                'deletedAt' => $item->deleted_at,
                'imagenes' => $item->imgEmprendedorServices->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'url_image' => $img->url_image,
                        'description' => $img->description,
                        'code' => $img->code,
                        'estado' => (bool)$img->estado,
                    ];
                }),
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $results->total(),
            'currentPage' => $results->currentPage(),
            'totalPages' => $results->lastPage(),
            'perPage' => $results->perPage(),
        ]);
    }
}
