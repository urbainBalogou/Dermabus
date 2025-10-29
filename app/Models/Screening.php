<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * The patient that was screened.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The agent who performed the screening.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
