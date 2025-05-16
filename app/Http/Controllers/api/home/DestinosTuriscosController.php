<?php

namespace App\Http\Controllers\API\home;

use App\Models\DestinosTuriscos;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DestinosTuriscosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el número de elementos por página, por defecto 10
        $size = $request->input('size', 10);
        $name = $request->input('name');

        // Crear la consulta base
        $query = DestinosTuriscos::query();

        // Si se pasa un nombre, aplicar filtro
        if ($name) {
            $query->where('nombre', 'like', "%$name%");
        }

        // Obtener los destinos turísticos paginados
        $destinos = $query->paginate($size);


        // Formatear los datos antes de enviarlos
        $response = collect($destinos->items())->map(function ($destino) {
            return [
                'id' => $destino->id,
                'nombre' => $destino->nombre,
                'descripcion' => $destino->descripcion,
                'lugar' => $destino->lugar,
                'emprendedorId' => $destino->emprendedor_id,
                'createdAt' => $destino->created_at,
                'updatedAt' => $destino->updated_at,
                'deletedAt' => $destino->deleted_at,
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $destinos->total(),
            'currentPage' => $destinos->currentPage() - 1,
            'totalPages' => $destinos->lastPage(),
        ]);
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

        return response()->json($destino);
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
