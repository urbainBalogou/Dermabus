<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'title',
        'assigned_zone',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CLINICIAN = 'clinician';
    public const ROLE_REGISTRAR = 'registrar';
    public const ROLE_SOCIAL = 'social_worker';

    /**
     * All available roles for a user.
     */
    public static function availableRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrateur·rice',
            self::ROLE_CLINICIAN => 'Clinicien·ne',
            self::ROLE_REGISTRAR => 'Agent d\'enregistrement',
            self::ROLE_SOCIAL => 'Accompagnement social',
        ];
    }

    /**
     * Determine if the user has the given role.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return in_array($this->role, $roles, true);
    }

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Determine if the user is a clinical agent.
     */
    public function isClinician(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_CLINICIAN], true);
    }

    /**
     * Screenings performed by the agent.
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    /**
     * Follow-up visits assigned to the user.
     */
    public function assignedFollowUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'assigned_user_id');
    }

    /**
     * Follow-ups scheduled by the user.
     */
    public function createdFollowUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'created_by');
    }

    /**
     * Case notes authored by the user.
     */
    public function caseNotes(): HasMany
    {
        return $this->hasMany(CaseNote::class);
    }
}
