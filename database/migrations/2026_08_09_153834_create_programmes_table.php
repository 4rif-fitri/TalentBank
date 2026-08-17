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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('restrict');
            $table->string('programme_name');
            $table->string('programme_code');
            $table->enum('programme_level', ['Diploma', 'Bachelor', 'Master', 'Doctor of Philosophy']);
            $table->integer('duration_years', false, true);
            $table->boolean('status');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
