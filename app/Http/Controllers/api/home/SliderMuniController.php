<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Slider_Muni;
use Illuminate\Http\Request;

class SliderMuniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider_Muni::all();

        return response()->json([
            'data' => $sliders,
            'message' => 'Sliders obtenidos exitosamente',
            'status' => true
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url_images' => 'required|string|max:255',
        ]);

        $slider = Slider_Muni::create($validated);

        return response()->json([
            'data' => $slider->toArray(),
            'message' => 'Slider creado exitosamente',
            'status' => true
        ], 201);
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
{
    $slider = Slider_Muni::findOrFail($id); // Asegura que el slider exista
    return response()->json([
        'data' => $slider, // Incluye el slider en la respuesta
        'message' => 'Slider encontrado',
        'status' => true
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    // En el controlador:
public function update(Request $request, $id) // Cambia a $id para mayor claridad
{
    $slider = Slider_Muni::findOrFail($id); // Busca manualmente

    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
        // ... otros campos
    ]);

    $slider->update($validated);

    return response()->json([
        'data' => $slider->fresh(), // Devuelve los datos actualizados
        'message' => 'Slider actualizado exitosamente',
        'status' => true
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider_Muni::withTrashed()->findOrFail($id);
        $slider->forceDelete();

        return response()->json([
            'message' => 'Slider eliminado exitosamente',
            'status' => true
        ], 200);
    }
}