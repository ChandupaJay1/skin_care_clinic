<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_treatment_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('treatment_id')->constrained('treatments')->cascadeOnDelete();
            $table->string('photo_path');
            $table->date('taken_on');          // date the photo was taken
            $table->text('notes')->nullable(); // optional doctor notes for this photo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_treatment_photos');
    }
};
