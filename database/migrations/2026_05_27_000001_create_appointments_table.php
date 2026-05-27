<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();          // APT-20260527-0001
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_id')->nullable()->constrained()->nullOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent double-booking same doctor at same slot
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'doctor_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
