<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        return Billing::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);
        
        return Billing::create($request->all());
    }

    public function show($id)
    {
        return Billing::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);
        $billing->update($request->all());
        return $billing;
    }

    public function destroy($id)
    {
        Billing::destroy($id);
        return response()->noContent();
    }
}
