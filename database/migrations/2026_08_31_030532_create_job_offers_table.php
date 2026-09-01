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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->decimal('salary_amount', 10, 2)->default(0);
            $table->string('salary_period');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('benefits')->nullable();
            $table->enum('offer_status', AppConstants::JOB_OFFER_STATUS)->default(AppConstants::JOB_OFFER_STATUS['PENDING']);
            $table->timestamps();
            $table->datetime('expires_at');
            $table->foreignId('invitation_id')->constrained('invitations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
