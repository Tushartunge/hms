<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HealthRecordController extends Controller
{
    public function readFile()
    {
        $contents = Storage::disk('c_drive')->get('filename.txt');
    }

    public function writeFile()
    {
        Storage::disk('c_drive')->put('newfile.txt', 'फ़ाइल सामग्री');
    }

    // Get health records for a specific patient
    public function getHealthRecords($patientId)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $records = HealthRecord::where('patient_id', $patientId)->get();
        return response()->json(['records' => $records]);
    }

    // Create a new health record for a patient, with attachment support
    public function createHealthRecord(Request $request, $patientId)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'description' => 'required|string',
            'date' => 'required|date',
            'medication' => 'required|string',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120', // Validate file
        ]);

        // Handle file upload if an attachment is provided
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Create the new health record
        $record = new HealthRecord();
        $record->patient_id = $patientId;
        $record->description = $validated['description'];
        $record->date = $validated['date'];
        $record->medication = $validated['medication'];
        $record->dosage = $validated['dosage'];
        $record->frequency = $validated['frequency'];
        $record->attachment_path = $attachmentPath; // Store the attachment path
        $record->save();

        return response()->json(['message' => 'Health record created successfully', 'record' => $record]);
    }

    // Update a health record
    public function updateHealthRecord(Request $request, $recordId)
    {
        $record = HealthRecord::find($recordId);

        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $validated = $request->validate([
            'description' => 'string|nullable',
            'date' => 'date|nullable',
            'medication' => 'string|nullable',
            'dosage' => 'string|nullable',
            'frequency' => 'string|nullable',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120', // Validate file
        ]);

        // Handle file upload if a new attachment is provided
        if ($request->hasFile('attachment')) {
            // Delete the old attachment if it exists
            if ($record->attachment_path) {
                Storage::disk('public')->delete($record->attachment_path);
            }

            // Store the new attachment
            $record->attachment_path = $request->file('attachment')->store('attachments', 'public');
        }

        $record->update($validated);

        return response()->json(['message' => 'Health record updated successfully', 'record' => $record]);
    }

    // Delete a health record
    public function deleteHealthRecord($recordId)
    {
        $record = HealthRecord::find($recordId);

        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Delete the attachment if it exists
        if ($record->attachment_path) {
            Storage::disk('public')->delete($record->attachment_path);
        }

        $record->delete();

        return response()->json(['message' => 'Health record deleted successfully']);
    }

    // Get a specific patient's health history (all records)
    public function getPatientHistory($patientId)
    {
        $history = HealthRecord::where('patient_id', $patientId)->orderBy('date', 'desc')->get();

        return response()->json(['history' => $history]);
    }
}
