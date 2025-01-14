<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'total_amount', 'paid_amount', 'status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

