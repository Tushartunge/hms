<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('patient_id');
        $table->unsignedBigInteger('doctor_id');
        $table->dateTime('appointment_date');
        $table->string('status')->default('Pending'); // Pending, Confirmed, Cancelled
        $table->timestamps();

        // Add foreign key constraints
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
    });

    // Ensure InnoDB engine for the appointments table
    DB::statement('ALTER TABLE appointments ENGINE = InnoDB');
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
