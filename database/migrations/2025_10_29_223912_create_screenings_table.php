<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('screened_on');
            $table->string('screening_location')->nullable();
            $table->decimal('gps_latitude', 10, 7)->nullable();
            $table->decimal('gps_longitude', 10, 7)->nullable();
            $table->json('symptoms')->nullable();
            $table->string('suspected_condition')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            $table->string('risk_score')->nullable();
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_on')->nullable();
            $table->string('referral_facility')->nullable();
            $table->enum('referral_status', ['pending', 'in_progress', 'completed', 'declined'])->default('pending');
            $table->text('clinical_notes')->nullable();
            $table->text('community_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
