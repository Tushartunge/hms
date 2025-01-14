<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
       // Include patient and appointment details
    $prescriptions = Prescription::with(['appointment.patient'])->get();

    return response()->json([
        'message' => 'Prescriptions retrieved successfully.',
        'prescriptions' => $prescriptions,
    ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'medications' => 'required|string',
            'instructions' => 'required|string',
        ]);
        
        return Prescription::create($request->all());
    }

    public function show($id)
    {
       // Include patient and appointment details
    $prescription = Prescription::with(['appointment.patient'])->findOrFail($id);

    return response()->json([
        'message' => 'Prescription retrieved successfully.',
        'prescription' => $prescription,
    ]);
    }

    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->update($request->all());
        return $prescription;
    }

    public function destroy($id)
    {
        Prescription::destroy($id);
        return response()->noContent();
    }
}
