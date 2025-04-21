<?php

namespace App\Http\Controllers;

use App\Models\DestinosTuriscos;
use Illuminate\Http\Request;

class DestinosTuriscosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinos = DestinosTuriscos::all();
        return $this->successResponse($destinos, 'Destinos turísticos obtenidos exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'emprendedor_id' => 'required|uuid|exists:emprendedores,id', // Relación con emprendedor
        ]);

        // Crear el destino turístico
        $destino = DestinosTuriscos::create($validated);

        return $this->successResponse($destino, 'Destino turístico creado exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $destino = DestinosTuriscos::find($id);

        if (!$destino) {
            return $this->errorResponse('Destino turístico no encontrado', 404);
        }

        return $this->successResponse($destino, 'Destino turístico encontrado');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $destino = DestinosTuriscos::find($id);

        if (!$destino) {
            return $this->errorResponse('Destino turístico no encontrado', 404);
        }

        // Validación de los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugar' => 'required|string|max:255',
            'emprendedor_id' => 'required|uuid|exists:emprendedores,id', // Relación con emprendedor
        ]);

        // Actualizar el destino turístico
        $destino->update($validated);

        return $this->successResponse($destino, 'Destino turístico actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $destino = DestinosTuriscos::find($id);

        if (!$destino) {
            return $this->errorResponse('Destino turístico no encontrado', 404);
        }

        // Eliminar el destino turístico
        $destino->delete();

        return $this->successResponse([], 'Destino turístico eliminado exitosamente');
    }
}
