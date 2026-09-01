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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->foreignId('receiver_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->text('invitation_message');
            $table->enum('invitation_status', AppConstants::INVITATION_STATUS)->default(AppConstants::INVITATION_STATUS['PENDING']);
            $table->date('expires_at');
            $table->timestamps();
            $table->foreignId('position_id')->constrained('positions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
