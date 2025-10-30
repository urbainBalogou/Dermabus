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
        Schema::table('screenings', function (Blueprint $table) {
            $table->enum('treatment_status', ['pending', 'in_progress', 'completed', 'not_required'])->default('pending')->after('referral_status');
            $table->text('treatment_plan')->nullable()->after('treatment_status');
            $table->timestamp('treatment_started_at')->nullable()->after('treatment_plan');
            $table->timestamp('treatment_completed_at')->nullable()->after('treatment_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropColumn([
                'treatment_status',
                'treatment_plan',
                'treatment_started_at',
                'treatment_completed_at',
            ]);
        });
    }
};
