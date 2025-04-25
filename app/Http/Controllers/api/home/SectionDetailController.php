<?php

namespace App\Http\Controllers\api\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionDetail;

class SectionDetailController extends Controller
{
    // Método para listar todos los SectionDetails
    public function index()
    {
        $sectionDetails = SectionDetail::all();  // Obtiene todos los detalles
        return response()->json($sectionDetails); // Devuelve los detalles en formato JSON
    }

    // Método para listar SectionDetails por Section ID (FK)
    public function getBySectionId($section_id)
    {
        // Busca los detalles que corresponden a un Section específico
        $sectionDetails = SectionDetail::where('section_id', $section_id)->get();

        if ($sectionDetails->isEmpty()) {
            return response()->json(['message' => 'No details found for this section'], 404);
        }

        return response()->json($sectionDetails); // Devuelve los detalles en formato JSON
    }

    // Método para obtener un SectionDetail por su ID
    public function show($id)
    {
        // Busca el SectionDetail por ID (UUID)
        $sectionDetail = SectionDetail::find($id);

        if (!$sectionDetail) {
            return response()->json(['message' => 'SectionDetail not found'], 404);
        }

        return response()->json($sectionDetail);  // Devuelve el detalle en formato JSON
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada, ahora los campos son opcionales (nullable)
        $validated = $request->validate([
            'status' => 'nullable|boolean',  // 'status' es opcional, y si está presente debe ser un booleano
            'code' => 'nullable|string',  // 'code' es opcional, y si está presente debe ser un string
            'title' => 'nullable|string',  // 'title' es opcional, y si está presente debe ser un string
            'description' => 'nullable|string',  // 'description' es opcional, y si está presente debe ser un string
            'section_id' => 'nullable|uuid'  // 'section_id' es opcional, y si está presente debe ser un UUID
        ]);

        // Busca el SectionDetail por su ID (UUID)
        $sectionDetail = SectionDetail::find($id);

        if (!$sectionDetail) {
            return response()->json(['message' => 'SectionDetail not found'], 404);
        }

        // Si el campo está presente, lo actualizamos
        if (isset($validated['status'])) {
            $sectionDetail->status = $validated['status'];
        }
        if (isset($validated['code'])) {
            $sectionDetail->code = $validated['code'];
        }
        if (isset($validated['title'])) {
            $sectionDetail->title = $validated['title'];
        }
        if (isset($validated['description'])) {
            $sectionDetail->description = $validated['description'];
        }
        if (isset($validated['section_id'])) {
            $sectionDetail->section_id = $validated['section_id'];
        }

        // Guardamos los cambios
        $sectionDetail->save();

        return response()->json(['message' => 'SectionDetail updated successfully', 'sectionDetail' => $sectionDetail]);
    }

    // Método para eliminar un SectionDetail
    public function destroy($id)
    {
        // Busca el SectionDetail por su ID (UUID)
        $sectionDetail = SectionDetail::find($id);

        if (!$sectionDetail) {
            return response()->json(['message' => 'SectionDetail not found'], 404);
        }

        // Elimina el SectionDetail
        $sectionDetail->delete();

        return response()->json(['message' => 'SectionDetail deleted successfully']);
    }
}
