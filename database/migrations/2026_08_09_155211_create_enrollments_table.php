<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('student_email')->unique();
            $table->string('matric_number')->unique();
            $table->year('intake_year');
            $table->year('graduation_year');
            $table->decimal('cgpa', 3, 2)->unsigned();
            $table->enum('enrollment_status', ['Active', 'Graduated', 'Deferred', 'Withdrawn']);
            $table->enum('verification_status', ['Pending', 'Verified', 'Rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
