<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return Document::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'file_name' => 'required|string',
            'file_path' => 'required|string',
        ]);
        
        return Document::create($request->all());
    }

    public function show($id)
    {
        return Document::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->update($request->all());
        return $document;
    }

    public function destroy($id)
    {
        Document::destroy($id);
        return response()->noContent();
    }
}
