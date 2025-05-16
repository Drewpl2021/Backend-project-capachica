<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ImgEmprendedor;
use Illuminate\Http\Request;

class ImgEmprendedorController extends Controller
{
    /**
     * Listar imágenes con paginación
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);

        $images = ImgEmprendedor::paginate($size);

        $images->getCollection()->transform(function ($image) {
            $image->estado = (bool) $image->estado;
            return $image;
        });

        return response()->json([
            'content' => $images->items(),
            'currentPage' => $images->currentPage(),
            'totalPages' => $images->lastPage(),
            'totalElements' => $images->total(),
        ]);
    }

    /**
     * Crear imagen de emprendedor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'emprendedor_id' => 'required|uuid|exists:emprendedors,id',
            'url_image' => 'required|string|max:255', // coincide con la migración
            'estado' => 'required|boolean',
            'code' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $image = ImgEmprendedor::create($validated);
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de emprendedor creada exitosamente'
        ], 201);
    }


    /**
     * Mostrar imagen por ID
     */
    public function show($id)
    {
        $image = ImgEmprendedor::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de emprendedor no encontrada'
            ], 404);
        }

        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de emprendedor encontrada'
        ]);
    }

    /**
     * Actualizar imagen por ID
     */
    public function update(Request $request, $id)
    {
        $image = ImgEmprendedor::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de emprendedor no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'emprendedor_id' => 'required|uuid|exists:emprendedors,id',
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'code' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $image->update($validated);
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de emprendedor actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar imagen por ID (soft delete)
     */
    public function destroy($id)
    {
        $image = ImgEmprendedor::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de emprendedor no encontrada'
            ], 404);
        }

        $image->delete();

        return response()->json([
            'message' => 'Imagen de emprendedor eliminada exitosamente'
        ]);
    }

    /**
     * Listar imágenes por emprendedor con paginación
     */
    public function getImagesByEmprendedorId($emprendedorId, Request $request)
    {
        $size = $request->input('size', 10);

        $images = ImgEmprendedor::where('emprendedor_id', $emprendedorId)->paginate($size);

        if ($images->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron imágenes para este emprendedor'
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
