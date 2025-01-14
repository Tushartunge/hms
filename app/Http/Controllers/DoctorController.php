<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return Doctor::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'specialization' => 'required|string',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string',
        ]);
        
        return Doctor::create($request->all());
    }

    public function show($id)
    {
        return Doctor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->all());
        return $doctor;
    }

    public function destroy($id)
    {
        Doctor::destroy($id);
        return response()->noContent();
    }
}
