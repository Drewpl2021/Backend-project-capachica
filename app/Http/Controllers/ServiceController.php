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
        $category = $request->get('category');

        $query = Service::with(['emprendedorServices.emprendedor', 'imgservices']);

        if ($category) {
            $query->where('category', $category);
        }

        $services = $query->paginate($size);

        // Transformar usando collect y map para tener control total
        $response = collect($services->items())->map(function ($service) {
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
            'currentPage' => $services->currentPage(),
            'totalElements' => $services->total(),
            'totalPages' => $services->lastPage(),
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

    public function emprendedoresPorServicio(Request $request)
    {
        $serviceId = $request->input('service_id');
        $category = $request->input('category');
        $size = $request->input('size', 10);

        $query = Service::query();

        if ($serviceId) {
            $query->where('id', $serviceId);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $services = $query->with(['emprendedorServices.emprendedor'])->paginate($size);

        if ($services->total() === 0) {
            return response()->json([
                'message' => 'No se encontraron servicios con esos filtros.',
                'content' => [],
                'totalElements' => 0,
                'currentPage' => 0,
                'totalPages' => 0,
                'perPage' => $size,
            ], 404);
        }

        $response = collect($services->items())->map(function ($service) {
            return [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'category' => $service->category,
                'emprendedores' => $service->emprendedorServices->map(function ($es) {
                    return [
                        'emprendedor_id' => $es->emprendedor->id ?? null,
                        'razon_social' => $es->emprendedor->razon_social ?? null,
                        'address' => $es->emprendedor->address ?? null,
                        'code' => $es->code,
                        'status' => $es->status,
                        'cantidad' => $es->cantidad,
                        'costo' => $es->costo,
                        'description' => $es->description,
                    ];
                }),
            ];
        });

        return response()->json([
            'content' => $response,
            'totalElements' => $services->total(),
            'currentPage' => $services->currentPage(),
            'totalPages' => $services->lastPage(),
        ]);
    }
}
