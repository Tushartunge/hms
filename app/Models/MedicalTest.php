<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTest extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'test_name', 'result'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

