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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('registration_channel')->default('field_agent');
            $table->timestamp('consent_signed_at')->nullable();
            $table->string('preferred_language')->nullable();
            $table->text('self_report_notes')->nullable();
            $table->boolean('is_self_registered')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'registration_channel',
                'consent_signed_at',
                'preferred_language',
                'self_report_notes',
                'is_self_registered',
            ]);
        });
    }
};
