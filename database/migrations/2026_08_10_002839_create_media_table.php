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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by_user_id')->constrained('user_profiles', 'id')->onDelete('cascade');
            $table->string('source_name');
            $table->integer('source_id')->unsigned();
            $table->enum('media_type', ['image', 'video', 'pdf', 'document']);
            $table->string('file_url');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['source_name', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
