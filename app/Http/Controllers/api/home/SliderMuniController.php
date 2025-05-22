<?php

namespace App\Http\Controllers\api\home;

use App\Http\Controllers\Controller;
use App\Models\Slider_Muni;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SliderMuniController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider_Muni::all();  // Obtener todos los sliders
        return $this->successResponse($sliders, 'Sliders obtenidos exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'municipio_descrip_id' => 'required|uuid|exists:municipio_descrips,id', // Clave foránea
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        // Crear un nuevo slider
        $slider = Slider_Muni::create($validated);

        return $this->successResponse($slider, 'Slider creado exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider_Muni $sliderMuni)
    {
        return $this->successResponse($sliderMuni, 'Slider encontrado');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider_Muni $sliderMuni)
    {
        // Validación de los datos
        $validated = $request->validate([
            'municipio_descrip_id' => 'required|uuid|exists:municipio_descrips,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        // Actualizar el slider con los nuevos datos
        $sliderMuni->update($validated);

        return $this->successResponse($sliderMuni, 'Slider actualizado exitosamente');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider_Muni $sliderMuni)
    {
        // Eliminar el slider
        $sliderMuni->delete();

        return $this->successResponse([], 'Slider eliminado exitosamente');
    }
}
