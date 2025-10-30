<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'reference_code',
        'portal_code',
        'portal_enabled',
        'portal_last_access_at',
        'primary_agent_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address_line',
        'village',
        'district',
        'region',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'psychosocial_notes',
        'care_plan',
        'is_reintegrated',
        'reintegrated_at',
        'gps_latitude',
        'gps_longitude',
        'status',
        'registration_channel',
        'consent_signed_at',
        'preferred_language',
        'self_report_notes',
        'is_self_registered',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_reintegrated' => 'boolean',
        'reintegrated_at' => 'datetime',
        'gps_latitude' => 'float',
        'gps_longitude' => 'float',
        'consent_signed_at' => 'datetime',
        'is_self_registered' => 'boolean',
        'portal_enabled' => 'boolean',
        'portal_last_access_at' => 'datetime',
    ];

    /**
     * Boot the model and assign a UUID when creating a patient.
     */
    protected static function booted(): void
    {
        static::creating(function (self $patient) {
            if (empty($patient->external_id)) {
                $patient->external_id = (string) Str::uuid();
            }

            if (empty($patient->reference_code)) {
                $patient->reference_code = static::generateReferenceCode();
            }

            if (empty($patient->portal_code)) {
                $patient->portal_code = static::generatePortalCode();
            }
        });

        static::updating(function (self $patient) {
            if (empty($patient->reference_code)) {
                $patient->reference_code = static::generateReferenceCode();
            }

            if (empty($patient->portal_code)) {
                $patient->portal_code = static::generatePortalCode();
            }
        });
    }

    /**
     * Get screenings performed for the patient.
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    /**
     * The primary agent following the patient.
     */
    public function primaryAgent()
    {
        return $this->belongsTo(User::class, 'primary_agent_id');
    }

    /**
     * All prescriptions linked to the patient's screenings.
     */
    public function prescriptions(): HasManyThrough
    {
        return $this->hasManyThrough(Prescription::class, Screening::class);
    }

    /**
     * Follow-up appointments scheduled for the patient.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * Case notes recorded for the patient.
     */
    public function caseNotes(): HasMany
    {
        return $this->hasMany(CaseNote::class)->latest('noted_on');
    }

    /**
     * Generate a short reference code for the patient.
     */
    protected static function generateReferenceCode(): string
    {
        do {
            $code = 'DB-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (static::where('reference_code', $code)->exists());

        return $code;
    }

    /**
     * Generate a secure portal code for the patient.
     */
    protected static function generatePortalCode(): string
    {
        do {
            $code = Str::upper(Str::random(10));
        } while (static::where('portal_code', $code)->exists());

        return $code;
    }
}
