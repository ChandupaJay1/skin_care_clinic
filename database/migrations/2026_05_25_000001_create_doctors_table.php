<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->unique(); // e.g. DOC-0001
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->string('email')->nullable()->unique();
            $table->string('nic')->unique();
            $table->string('specialization');
            $table->string('qualification');          // e.g. MBBS, MD
            $table->string('registration_number')->unique(); // Medical council reg no.
            $table->integer('experience_years')->default(0);
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
