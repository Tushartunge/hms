<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'medications', 'instructions', 'patient_id',
        'doctor_id',
        'prescription_details',];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

