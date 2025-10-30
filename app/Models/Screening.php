<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screening extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'user_id',
        'screened_on',
        'screening_location',
        'gps_latitude',
        'gps_longitude',
        'symptoms',
        'suspected_condition',
        'severity',
        'risk_score',
        'requires_follow_up',
        'follow_up_on',
        'referral_facility',
        'referral_status',
        'treatment_status',
        'treatment_plan',
        'treatment_started_at',
        'treatment_completed_at',
        'clinical_notes',
        'community_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screened_on' => 'date',
        'symptoms' => 'array',
        'requires_follow_up' => 'boolean',
        'follow_up_on' => 'date',
        'gps_latitude' => 'float',
        'gps_longitude' => 'float',
        'treatment_started_at' => 'datetime',
        'treatment_completed_at' => 'datetime',
    ];

    /**
     * The patient that was screened.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The agent who performed the screening.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Prescriptions issued during the screening.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Follow-up tasks linked to this screening.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }
}
