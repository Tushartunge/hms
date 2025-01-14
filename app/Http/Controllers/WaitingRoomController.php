<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use App\Models\Appointment;
use Illuminate\Http\Request;

class WaitingRoomController extends Controller
{
    /**
     * Display a listing of the waiting room patients.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $waitingRooms = WaitingRoom::with('patient', 'doctor', 'appointment')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($waitingRooms);
    }

    /**
     * Store a newly created entry in the waiting room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'status' => 'required|in:waiting,in_consultation,completed',
            'priority' => 'required|in:low,medium,high', // Validate priority
        ]);

        $waitingRoom = WaitingRoom::create($validated);

        return response()->json([
            'message' => 'Waiting room entry created successfully.',
            'data' => $waitingRoom,
        ], 201);
    }

    /**
     * Display the specified waiting room entry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $waitingRoom = WaitingRoom::with('patient', 'doctor', 'appointment')->findOrFail($id);

        return response()->json($waitingRoom);
    }

    /**
     * Update the specified waiting room entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $waitingRoom = WaitingRoom::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'required|in:waiting,in_consultation,completed',
            'priority' => 'nullable|in:low,medium,high', // Allow updating priority
        ]);

        $waitingRoom->update($validated);

        return response()->json([
            'message' => 'Waiting room entry updated successfully.',
            'data' => $waitingRoom,
        ]);
    }

    /**
     * Remove the specified waiting room entry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $waitingRoom = WaitingRoom::findOrFail($id);
        $waitingRoom->delete();

        return response()->json([
            'message' => 'Waiting room entry deleted successfully.',
        ]);
    }

    public function startConsultation($waitingRoomId)
{
    // Find the waiting room entry
    $waitingRoom = WaitingRoom::findOrFail($waitingRoomId);

    // Ensure that the patient is in the "waiting" status before starting the consultation
    if ($waitingRoom->status !== 'waiting') {
        return response()->json(['message' => 'Patient is not in waiting status.'], 400);
    }

    // Update the status to "in_consultation"
    $waitingRoom->update(['status' => 'in_consultation']);

    return response()->json([
        'message' => 'Consultation started. Status updated to "in consultation".',
        'data' => $waitingRoom,
    ]);
}

public function completeConsultation($waitingRoomId)
{
    // Find the waiting room entry
    $waitingRoom = WaitingRoom::findOrFail($waitingRoomId);

    // Ensure that the patient is in the "in_consultation" status before completing
    if ($waitingRoom->status !== 'waiting') {
        return response()->json(['message' => 'Patient is not in waiting room.'], 400);
    }

    // Update the status of the appointment (if exists) to "completed"
    if ($waitingRoom->appointment_id) {
        $appointment = Appointment::find($waitingRoom->appointment_id);

        if ($appointment) {
            $appointment->update(['status' => 'completed']);
        }
    }

    // Delete the waiting room entry
    $waitingRoom->delete();

    return response()->json([
        'message' => 'Consultation completed. Waiting room entry deleted and appointment status updated to "completed".',
    ]);
}




}

