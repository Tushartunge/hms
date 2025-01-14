<?php

namespace App\Http\Controllers;

use App\Models\MedicalTest;
use Illuminate\Http\Request;

class MedicalTestController extends Controller
{
    public function index()
    {
        return MedicalTest::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string',
            'result' => 'nullable|string',
        ]);
        
        return MedicalTest::create($request->all());
    }

    public function show($id)
    {
        return MedicalTest::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $medicalTest = MedicalTest::findOrFail($id);
        $medicalTest->update($request->all());
        return $medicalTest;
    }

    public function destroy($id)
    {
        MedicalTest::destroy($id);
        return response()->noContent();
    }
}
