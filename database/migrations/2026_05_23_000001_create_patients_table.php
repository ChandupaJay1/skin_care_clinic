<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique(); // e.g. MSC-0001
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->string('email')->nullable()->unique();
            $table->text('address');
            $table->string('nic')->unique();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->enum('skin_type', ['normal', 'dry', 'oily', 'combination', 'sensitive']);
            $table->text('known_allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
