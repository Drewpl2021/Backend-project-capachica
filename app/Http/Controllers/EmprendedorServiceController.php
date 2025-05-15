<?php

namespace App\Http\Controllers;

use App\Models\EmprendedorService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmprendedorServiceController extends Controller
{
    /**
     * Listar registros con paginación y búsqueda por 'code' y 'name'.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $search = $request->input('search');

        $query = EmprendedorService::query();

        // Filtro de búsqueda en 'code' o 'name'
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            });
        }

        // Carga relaciones para mostrar info asociada si quieres
        $query->with(['emprendedor', 'service']);

        $results = $query->paginate($size);

        return response()->json([
            'content' => $results->items(),
            'totalElements' => $results->total(),
            'currentPage' => $results->currentPage(),
            'totalPages' => $results->lastPage(),
        ]);
    }

    /**
     * Crear un nuevo registro.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'emprendedor_id' => ['required', 'uuid', 'exists:emprendedors,id'],
            'code' => ['required', 'string', 'unique:emprendedor_service,code'],
            'status' => ['boolean'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'costo' => ['required', 'numeric', 'min:0'],
            'costo_unidad' => ['nullable', 'numeric', 'min:0'],
        ]);

        $emprendedorService = EmprendedorService::create($validated);

        return response()->json($emprendedorService, 201);
    }

    /**
     * Mostrar un registro específico por ID.
     */
    public function show($id)
    {
        $record = EmprendedorService::with(['emprendedor', 'service'])->find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        return response()->json($record);
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $id)
    {
        $record = EmprendedorService::find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $validated = $request->validate([
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'emprendedor_id' => ['required', 'uuid', 'exists:emprendedors,id'],
            'code' => ['required', 'string', Rule::unique('emprendedor_service', 'code')->ignore($id)],
            'status' => ['boolean'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'costo' => ['required', 'numeric', 'min:0'],
            'costo_unidad' => ['nullable', 'numeric', 'min:0'],
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    /**
     * Eliminar (soft delete) un registro.
     */
    public function destroy($id)
    {
        $record = EmprendedorService::find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }

    /**
     * Restaurar un registro eliminado (opcional).
     */
    public function restore($id)
    {
        $record = EmprendedorService::onlyTrashed()->find($id);

        if (!$record) {
            return response()->json(['message' => 'Registro no encontrado o no está eliminado'], 404);
        }

        $record->restore();

        return response()->json(['message' => 'Registro restaurado correctamente']);
    }
}
