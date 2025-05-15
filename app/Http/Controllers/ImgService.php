<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Imgservice;
use Illuminate\Http\Request;

class ImgServiceController extends Controller
{
    /**
     * Listar imágenes con paginación
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);

        $images = Imgservice::paginate($size);

        $images->getCollection()->transform(function ($image) {
            $image->estado = (bool) $image->estado;
            return $image;
        });

        return response()->json([
            'content' => $images->items(),
            'currentPage' => $images->currentPage(),
            'totalPages' => $images->lastPage(),
            'totalElements' => $images->total(),
            'perPage' => $images->perPage(),
        ]);
    }

    /**
     * Crear una imagen de servicio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|uuid|exists:services,id',
            'imagen_url' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:imgservices,code',
        ]);

        $image = Imgservice::create($validated);
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de servicio creada exitosamente'
        ], 201);
    }

    /**
     * Mostrar imagen específica
     */
    public function show($id)
    {
        $image = Imgservice::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de servicio no encontrada'
            ], 404);
        }

        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de servicio encontrada'
        ]);
    }

    /**
     * Actualizar imagen
     */
    public function update(Request $request, $id)
    {
        $image = Imgservice::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de servicio no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'service_id' => 'required|uuid|exists:services,id',
            'imagen_url' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:imgservices,code,' . $id,
        ]);

        $image->update($validated);
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de servicio actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar imagen (soft delete)
     */
    public function destroy($id)
    {
        $image = Imgservice::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de servicio no encontrada'
            ], 404);
        }

        $image->delete();

        return response()->json([
            'message' => 'Imagen de servicio eliminada exitosamente'
        ]);
    }

    /**
     * Listar imágenes por service_id con paginación
     */
    public function getImagesByServiceId($serviceId, Request $request)
    {
        $size = $request->input('size', 10);

        $images = Imgservice::where('service_id', $serviceId)->paginate($size);

        if ($images->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron imágenes para este servicio'
            ], 404);
        }

        $images->getCollection()->transform(function ($image) {
            $image->estado = (bool) $image->estado;
            return $image;
        });

        return response()->json([
            'content' => $images->items(),
            'currentPage' => $images->currentPage(),
            'totalPages' => $images->lastPage(),
            'totalElements' => $images->total(),
            'perPage' => $images->perPage(),
        ]);
    }
}
