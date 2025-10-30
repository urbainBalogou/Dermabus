<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseNote extends Model
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
        'noted_on',
        'category',
        'visibility',
        'title',
        'summary',
        'next_actions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'noted_on' => 'date',
    ];

    public const CATEGORY_MEDICAL = 'medical';
    public const CATEGORY_SOCIAL = 'social';
    public const CATEGORY_LOGISTICS = 'logistics';

    public const VISIBILITY_TEAM = 'team';
    public const VISIBILITY_HEALTH = 'health_only';
    public const VISIBILITY_SOCIAL = 'social_only';

    /**
     * Patient concerned by the note.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Staff member who created the note.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
