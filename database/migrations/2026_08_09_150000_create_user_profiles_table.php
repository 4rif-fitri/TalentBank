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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->text('about')->nullable();
            $table->string('headline')->nullable();
            $table->string('location')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('profile_visibility', ['Public', 'Recruiter', 'Private'])->default('Public');
            $table->enum('employment_status', ['Open to Work', 'Open to Internship', 'Employed', 'Not Looking'])->default('Open to Work');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
