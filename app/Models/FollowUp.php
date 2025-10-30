<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'screening_id',
        'assigned_user_id',
        'created_by',
        'scheduled_for',
        'type',
        'status',
        'location',
        'contact_mode',
        'notes',
        'outcome',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_for' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public const STATUS_PLANNED = 'planned';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_MISSED = 'missed';

    public const TYPE_MEDICAL = 'medical_visit';
    public const TYPE_SOCIAL = 'social_visit';
    public const TYPE_PHONE = 'phone_checkin';

    /**
     * The patient concerned by the follow-up.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The screening that triggered the follow-up.
     */
    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }

    /**
     * The team member assigned to the follow-up.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * The staff member who scheduled the follow-up.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
