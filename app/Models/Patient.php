<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dob', 'gender', 'phone', 'email', 'address', 'visit_count'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function medicalTests()
    {
        return $this->hasMany(MedicalTest::class);
    }
}
