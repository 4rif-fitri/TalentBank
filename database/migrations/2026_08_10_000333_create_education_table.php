<?php

use App\Constants\AppConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->foreignId('field_of_study_id')->constrained('field_of_studies')->nullable();
            $table->foreignId('qualification_id')->constrained('qualifications')->nullable();
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('enrollment_status', AppConstants::ENROLLMENT_STATUS);
            $table->enum('verification_status', AppConstants::VERIFICATION_STATUS);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
