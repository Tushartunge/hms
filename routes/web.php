<?php

use Illuminate\Support\Facades\Route;
use App\Models\Prescription;

Route::get('/prescription/{id}/print', function ($id) {
    $prescription = Prescription::with('appointment', 'appointment.patient')->findOrFail($id);

    return view('prescription.print', compact('prescription'));
})->name('prescription.print');

Route::get('/', function () {
    return view('index');
});


Route::get('/pre', function () {
    return view('prescription');
});


Route::get('/wait', function () {
    return view('waiting');
});

Route::get('/print', function () {
    return view('print');
});

Route::get('/health', function () {
    return view('health');
});



