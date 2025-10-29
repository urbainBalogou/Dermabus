<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'is_reintegrated',
        'reintegrated_at',
        'gps_latitude',
        'gps_longitude',
        'status',
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
        });
    }

    /**
     * Get screenings performed for the patient.
     */
    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
