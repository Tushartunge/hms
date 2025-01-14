<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('waiting_rooms', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('patient_id');
        $table->unsignedBigInteger('doctor_id')->nullable();
        $table->unsignedBigInteger('appointment_id')->nullable();
        $table->enum('status', ['waiting', 'in_consultation', 'completed'])->default('waiting');
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // New field for priority
        $table->timestamps();

        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_rooms');
    }
};
