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
        Schema::create('resume_contents', function (Blueprint $table) {
            $table->id();
            $table->string('source_type');
            $table->unsignedInteger('source_id');
            $table->foreignId('resume_id')->constrained('resumes')->onDelete('cascade');

            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_contents');
    }
};
