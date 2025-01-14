<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return Patient::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'email'=> 'required|string',
             'address'=>'required|string',
        ]);
        
        return Patient::create($request->all());
    }

    public function show($id)
    {
        return Patient::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->all());
        return $patient;
    }

    public function destroy($id)
    {
        Patient::destroy($id);
        return response()->noContent();
    }
}
