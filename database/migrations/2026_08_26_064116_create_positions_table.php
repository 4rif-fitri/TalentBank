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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('restrict');
            $table->foreignId('user_profile_id')->constrained('user_profiles')->onDelete('restrict');
            $table->string('position_title');
            $table->enum('employment_type', AppConstants::EMPLOYMENT_TYPES);
            $table->string('department');
            $table->string('work_location');
            $table->integer('vacancies')->unsigned();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
