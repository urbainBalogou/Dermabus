<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->unique()->after('external_id');
            $table->string('portal_code')->nullable()->unique()->after('reference_code');
            $table->boolean('portal_enabled')->default(true)->after('portal_code');
            $table->timestamp('portal_last_access_at')->nullable()->after('portal_enabled');
            $table->foreignId('primary_agent_id')->nullable()->after('portal_last_access_at')->constrained('users')->nullOnDelete();
            $table->text('care_plan')->nullable()->after('psychosocial_notes');
        });

        DB::table('patients')->select('id')->orderBy('id')->chunkById(50, function ($patients): void {
            foreach ($patients as $patient) {
                $reference = $this->generateReferenceCode($patient->id);
                $portal = $this->generatePortalCode();

                DB::table('patients')
                    ->where('id', $patient->id)
                    ->update([
                        'reference_code' => $reference,
                        'portal_code' => $portal,
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('primary_agent_id');
            $table->dropColumn([
                'reference_code',
                'portal_code',
                'portal_enabled',
                'portal_last_access_at',
                'care_plan',
            ]);
        });
    }

    private function generateReferenceCode(int $id): string
    {
        return 'DB' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    private function generatePortalCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (DB::table('patients')->where('portal_code', $code)->exists());

        return $code;
    }
};
