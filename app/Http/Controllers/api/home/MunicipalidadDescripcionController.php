<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\Municipalidad_Descripcion;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MunicipalidadDescripcionController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);  // Tamaño de la página
        $descripciones = Municipalidad_Descripcion::paginate($size);

        return response()->json([
            'content' => $descripciones->items(),
            'totalElements' => $descripciones->total(),
            'currentPage' => $descripciones->currentPage(),
            'totalPages' => $descripciones->lastPage(),
            'perPage' => $descripciones->perPage(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $municipalidadId)
    {
        // Verificar si ya existe una descripción para esa municipalidad
        $descripcion = Municipalidad_Descripcion::where('municipalidad_id', $municipalidadId)->first();
        if ($descripcion) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe una descripción para esta municipalidad.'
            ], 400);
        }

        // Validación de los datos
        $validated = $request->validate([
            'logo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ruc' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'nombre_alcalde' => 'required|string|max:255',
            'anio_gestion' => 'required|string|max:4',
        ]);

        // Asignar el ID de la municipalidad
        $validated['municipalidad_id'] = $municipalidadId;

        // Crear la nueva descripción
        $newDescripcion = Municipalidad_Descripcion::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Descripción de municipalidad creada exitosamente',
            'data' => $newDescripcion,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return response()->json([
                'status' => false,
                'message' => 'Descripción de municipio no encontrada',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Descripción de municipio encontrada',
            'data' => $descripcion,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return response()->json([
                'status' => false,
                'message' => 'Descripción de municipio no encontrada',
            ], 404);
        }

        // Validación de los datos
        $validated = $request->validate([
            'municipalidad_id' => 'required|uuid|exists:municipalidads,id', // Asegurarse que la municipalidad existe
            'logo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ruc' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'nombre_alcalde' => 'required|string|max:255',
            'anio_gestion' => 'required|string|max:4',
        ]);

        // Actualizar la descripción
        $descripcion->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Descripción de municipio actualizada exitosamente',
            'data' => $descripcion,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $descripcion = Municipalidad_Descripcion::find($id);

        if (!$descripcion) {
            return response()->json([
                'status' => false,
                'message' => 'Descripción de municipio no encontrada',
            ], 404);
        }

        // Eliminar la descripción
        $descripcion->delete();

        return response()->json([
            'status' => true,
            'message' => 'Descripción de municipio eliminada exitosamente',
        ]);
    }
}
