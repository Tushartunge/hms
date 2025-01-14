<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_id' => 'required|exists:billings,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);
        
        return Transaction::create($request->all());
    }

    public function show($id)
    {
        return Transaction::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());
        return $transaction;
    }

    public function destroy($id)
    {
        Transaction::destroy($id);
        return response()->noContent();
    }
}
