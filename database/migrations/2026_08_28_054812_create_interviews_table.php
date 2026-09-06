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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->dateTime('scheduled_at');
            $table->enum('interview_mode', AppConstants::INTERVIEW_MODES);
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            $table->enum('interview_status', AppConstants::INTERVIEW_STATUS)->default(AppConstants::INTERVIEW_STATUS['SCHEDULED']);
            $table->enum('interview_result', AppConstants::INTERVIEW_RESULTS)->default(AppConstants::INTERVIEW_RESULTS['PENDING']);
            $table->text('recruiter_comment')->nullable();
            $table->foreignId('interviewer_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->foreignId('interviewee_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
