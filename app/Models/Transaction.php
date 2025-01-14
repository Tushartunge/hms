<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['billing_id', 'amount', 'payment_method'];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
