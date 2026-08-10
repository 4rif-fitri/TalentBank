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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('ssm_number')->unique();
            $table->foreignId('industry_sector_id')->constrained('industry_sectors')->onDelete('restrict');
            $table->string('address');
            $table->string('postcode');
            $table->string('city');
            $table->string('state');
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('company_email');
            $table->string('company_phone');
            $table->foreignId('industry_category_id')->constrained('industry_categories')->onDelete('restrict');
            $table->foreignId('organization_type_id')->constrained('organization_types')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
