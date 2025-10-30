<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'screening_id',
        'prescribed_by',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
    ];

    /**
     * Attributes casting configuration.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screening_id' => 'integer',
        'prescribed_by' => 'integer',
    ];

    /**
     * The screening associated with the prescription.
     */
    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    /**
     * The user who prescribed the medication.
     */
    public function prescriber()
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }
}
