<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'patient_id',         // Foreign key to the patients table
        'description',        // Description of the health record
        'date',               // Date of the record
        'medication',         // Medication prescribed
        'dosage',             // Dosage of the medication
        'frequency',          // Frequency of the medication
        'attachment_path',    // Path to the uploaded attachment
    ];

    // Relationship with the Patient model
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
