<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->get('size', 10);

        $services = Service::with(['emprendedorServices.emprendedor', 'imgservices'])
            ->paginate($size);

        $response = $services->getCollection()->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'code' => $service->code,
                'category' => $service->category,
                'status' => $service->status,
                'emprendedores' => $service->emprendedorServices->map(function ($es) {
                    return [
                        'id' => $es->emprendedor->id ?? null,
                        'razon_social' => $es->emprendedor->razon_social ?? null,
                        'address' => $es->emprendedor->address ?? null,
                    ];
                }),
                'images' => $service->imgservices->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'imagen_url' => $img->imagen_url,
                        'description' => $img->description,
                        'code' => $img->code,
                    ];
                }),
            ];
        });

        return response()->json([
            'content' => $response,
            'currentPage' => $services->currentPage() - 1,
            'totalElements' => $services->total(),
            'totalPages' => $services->lastPage() - 1,
        ]);
    }

    // Crear nuevo servicio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:255', Rule::unique('services')],
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    // Mostrar un servicio específico con emprendedores
    public function show($id)
    {
        try {
            $service = Service::with(['emprendedorServices.emprendedor'])->findOrFail($id);

            $response = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'code' => $service->code,
                'category' => $service->category,
                'status' => $service->status,
                'emprendedores' => $service->emprendedorServices->map(function ($es) {
                    return [
                        'id' => $es->emprendedor->id ?? null,
                        'razon_social' => $es->emprendedor->razon_social ?? null,
                        'address' => $es->emprendedor->address ?? null,
                    ];
                }),
            ];

            return response()->json($response);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
    }

    // Actualizar un servicio
    public function update(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => ['required', 'string', 'max:255', Rule::unique('services')->ignore($service->id)],
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'status' => 'nullable|boolean',
            ]);

            $service->update($validated);

            return response()->json($service);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
    }

    // Soft delete un servicio
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return response()->json(['message' => 'Servicio eliminado correctamente'], 200);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
    }
}
