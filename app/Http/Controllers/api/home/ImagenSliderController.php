<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Imagen_Slider;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ImagenSliderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las imágenes de sliders
        $imagenes = Imagen_Slider::all();
        return $this->successResponse($imagenes, 'Imágenes obtenidas exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'slider_id' => 'required|uuid|exists:sliders,id', // Asegurarse de que el slider exista
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean', // Estado de la imagen (activa o no)
            'codigo' => 'required|integer',
        ]);

        // Crear una nueva imagen asociada a un slider
        $imagen = Imagen_Slider::create($validated);

        return $this->successResponse($imagen, 'Imagen del slider creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Imagen_Slider $imagen_Slider)
    {
        return $this->successResponse($imagen_Slider, 'Imagen del slider encontrada');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imagen_Slider $imagen_Slider)
    {
        // Validación de los datos
        $validated = $request->validate([
            'slider_id' => 'required|uuid|exists:sliders,id', // Asegurarse de que el slider exista
            'url_image' => 'required|string|max:255',
            'estado' => 'required|boolean', // Estado de la imagen
            'codigo' => 'required|integer',
        ]);

        // Actualizar la imagen con los nuevos datos
        $imagen_Slider->update($validated);

        return $this->successResponse($imagen_Slider, 'Imagen del slider actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imagen_Slider $imagen_Slider)
    {
        // Eliminar la imagen del slider
        $imagen_Slider->delete();

        return $this->successResponse([], 'Imagen del slider eliminada exitosamente');
    }
}
