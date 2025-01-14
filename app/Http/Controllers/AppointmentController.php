<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use App\Models\WaitingRoom;


class AppointmentController extends Controller
{
    public function index()
    {
        // Fetch all appointments with related patient and doctor details
    $appointments = Appointment::with(['patient', 'doctor'])->get();

    return response()->json([
        'message' => 'Appointments retrieved successfully.',
        'appointments' => $appointments,
    ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'mode' => 'required|in:offline,online', // Validate mode
        ]);
    
        // Check if the patient already has an appointment
        $patient = Patient::findOrFail($request->patient_id);
    
        // Determine type of appointment (new or follow-up)
        $type = $patient->appointments()->count() > 0 ? 'follow-up' : 'new';
        $request->merge(['type' => $type]);
    
        // Check for appointment conflicts
        $existingAppointmentForPatient = Appointment::where('appointment_date', $request->appointment_date)->exists();
        if ($existingAppointmentForPatient) {
            return response()->json(['message' => 'An appointment already exists at this date and time.'], 400);
        }
    
        $existingAppointmentForDoctor = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->exists();
    
        if ($existingAppointmentForDoctor) {
            return response()->json(['message' => 'The doctor already has an appointment at this date and time.'], 400);
        }
    
        DB::beginTransaction(); // Start transaction
        try {
            // Create the appointment
            $appointment = Appointment::create($request->all());
    
            // Increment the visit count for the patient
            $patient->increment('visit_count');

            $doctor = Doctor::findOrFail($request->doctor_id); // Fetch doctor by ID
    
            // Automatically create a prescription for the appointment
            $prescription = \App\Models\Prescription::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'doctor_id' => $request->doctor_id,
                'prescription_details' => json_encode([
                    'Patient ID' => $patient->id,
                    'Patient Name' => $patient->name,
                    'Patient Phone' => $patient->phone,
                    'Patient Address' => $patient->address,
                    'Visit Count' => $patient->visit_count,
                    'Doctor Name' => $doctor->name,
                    'Appointment ID' => $appointment->id,
                    'Appointment Date' => $appointment->appointment_date,
                ]),
                'medications' => 'No medications prescribed',
                'instructions' => 'No instructions prescribed',
            ]);

             // Add the patient to the waiting room
        $this->addToWaitingRoom($appointment);

        // Retrieve the waiting room entry for this appointment
$waitingRoomEntry = WaitingRoom::where('appointment_id', $appointment->id)->first();
    
            DB::commit(); // Commit transaction
    
            // Return response with appointment and prescription details
            return response()->json([
                'message' => 'Appointment booked successfully, and prescription created.',
                'appointment' => $appointment,
                'prescription' => json_decode($prescription->prescription_details), // Return as JSON
                'waiting_room' => $waitingRoomEntry, // Include waiting room details
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if something fails
            return response()->json(['message' => 'Failed to book appointment.', 'error' => $e->getMessage()], 500);
        }
    }

    private function addToWaitingRoom($appointment)
    {
        // Define priority logic (example: based on appointment type or time)
        $priority = $this->calculatePriority($appointment);

        // Add the patient to the waiting room with the appointment ID
        WaitingRoom::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'priority' => $priority,
        ]);
    }

    private function calculatePriority($appointment)
    {
        // Example: Patients with earlier appointments have higher priority
        $currentTime = now();
        $appointmentTime = $appointment->appointment_date;

        return $currentTime->diffInMinutes($appointmentTime) <= 30 ? 1 : 2; // Priority 1 for <30 mins, else 2
    }

    

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);

    return response()->json([
        'message' => 'Appointment details retrieved successfully.',
        'appointment' => $appointment,
    ]);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());
        return $appointment;
    }

    public function destroy($id)
    {
        Appointment::destroy($id);
        return response()->noContent();
    }
}

