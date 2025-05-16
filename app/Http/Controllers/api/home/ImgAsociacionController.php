<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Img_Asociacion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ImgAsociacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Img_Asociacion::paginate(10); // Paginación de 10 imágenes por página

        // Transformar los valores de 'estado' de 1/0 a true/false en los items de la página
        $images->getCollection()->transform(function ($image) {
            $image->estado = (bool) $image->estado; // Convertir a booleano
            return $image;
        });

        return response()->json([
            'content' => $images->items(),
            'currentpage' => $images->currentPage(),
            'totalpages' => $images->lastPage(),
            'totalElements' => $images->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id', // Relación con la asociación
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'codigo' => 'nullable|string',
        ]);

        $image = Img_Asociacion::create($validated);

        // Convertir 'estado' a true/false antes de devolver la respuesta
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de asociación creada exitosamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Imagen de asociación no encontrada'
            ], 404);
        }

        // Convertir 'estado' a true/false antes de devolver la respuesta
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de asociación encontrada'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Imagen de asociación no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id',
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'codigo' => 'nullable|string',
        ]);

        $image->update($validated);

        // Convertir 'estado' a true/false antes de devolver la respuesta
        $image->estado = (bool) $image->estado;

        return response()->json([
            'content' => $image,
            'message' => 'Imagen de asociación actualizada exitosamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return response()->json([
                'message' => 'Imagen de asociación no encontrada'
            ], 404);
        }

        $image->delete();
        return response()->json([
            'message' => 'Imagen de asociación eliminada exitosamente'
        ]);
    }

    /**
     * Get all images associated with a specific asociacion_id with pagination.
     */
    public function getImagesByAsociacionId($asociacionId, Request $request)
    {
        // Paginación de 10 imágenes por página
        $images = Img_Asociacion::where('asociacion_id', $asociacionId)->paginate(10);

        if ($images->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron imágenes para esta asociación'
            ], 404);
        }

        // Transformar los valores de 'estado' de 1/0 a true/false en los items de la página
        $images->getCollection()->transform(function ($image) {
            $image->estado = (bool) $image->estado; // Convertir a booleano
            return $image;
        });

        return response()->json([
            'content' => $images->items(), // Usar items() en la paginación
            'currentpage' => $images->currentPage(),
            'totalpages' => $images->lastPage(),
            'totalElements' => $images->total(),
        ]);
    }
}
