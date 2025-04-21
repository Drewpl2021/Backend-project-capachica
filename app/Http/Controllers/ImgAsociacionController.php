<?php

namespace App\Http\Controllers;

use App\Models\img_asociacion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ImgAsociacionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Img_Asociacion::all();
        return $this->successResponse($images, 'Imágenes obtenidas exitosamente');
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
            'codigo' => 'nullable|integer',
        ]);

        $image = Img_Asociacion::create($validated);
        return $this->successResponse($image, 'Imagen de asociación creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return $this->errorResponse('Imagen de asociación no encontrada', 404);
        }

        return $this->successResponse($image, 'Imagen de asociación encontrada');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return $this->errorResponse('Imagen de asociación no encontrada', 404);
        }

        $validated = $request->validate([
            'asociacion_id' => 'required|uuid|exists:asociacions,id',
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'codigo' => 'nullable|integer',
        ]);

        $image->update($validated);
        return $this->successResponse($image, 'Imagen de asociación actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = Img_Asociacion::find($id);

        if (!$image) {
            return $this->errorResponse('Imagen de asociación no encontrada', 404);
        }

        $image->delete();
        return $this->successResponse([], 'Imagen de asociación eliminada exitosamente');
    }
}
