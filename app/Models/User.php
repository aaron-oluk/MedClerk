<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Institution;
use App\Models\Department;
use App\Models\Rotation;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_STUDENT = 'student';
    public const ROLE_LECTURER = 'lecturer';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERADMIN = 'superadmin';

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
        'institution_id',
        'department_id',
        'student_number',
        'email_notifications_enabled',
        'is_active',
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
            'email_notifications_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function cohortEnrollments(): HasMany
    {
        return $this->hasMany(CohortEnrollment::class, 'user_id');
    }

    public function currentCohortEnrollment(): ?CohortEnrollment
    {
        return $this->cohortEnrollments()
            ->where('status', 'active')
            ->latest('enrolled_at')
            ->with('cohort.program')
            ->first();
    }

    /**
     * @return array<string, string|null>
     */
    public function profileSummary(?Rotation $activeRotation = null): array
    {
        return [
            'name' => $this->name,
            'registration_number' => $this->student_number,
            'email' => $this->email,
            'institution' => $this->institution?->name,
            'programme' => $this->currentCohortEnrollment()?->cohort?->program?->name,
            'placement' => $activeRotation?->name,
        ];
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isLecturer(): bool
    {
        return $this->role === self::ROLE_LECTURER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperadmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }
}
