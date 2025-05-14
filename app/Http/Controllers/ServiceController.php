<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Mostrar todos los servicios con paginación
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        // Obtener los servicios con paginación
        $services = Service::paginate($size);

        // Cambiar el índice de las páginas de Laravel
        $services->setPageName('page');
        $services->appends(['size' => $size]);

        $response = [
            'content' => $services->items(),
            'currentPage' => $services->currentPage() - 1,
            'perPage' => $services->perPage(),
            'totalElements' => $services->total(),
            'totalPages' => $services->lastPage() - 1,
        ];

        return response()->json($response);
    }

    // Almacenar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ]);

        $service = Service::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response()->json($service, 201);  // Retornar el servicio creado
    }

    // Mostrar un servicio específico
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    // Actualizar un servicio existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response()->json($service);
    }

    // Eliminar un servicio (Soft Delete)
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();  // Esto realiza un soft delete

        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
