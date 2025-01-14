<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->string('description');
            $table->date('date');
            $table->string('medication');  // Medication field
            $table->string('dosage');      // Dosage field
            $table->string('frequency');   // Frequency field
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_records');
    }
}