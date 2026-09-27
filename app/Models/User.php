<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read StudentProfile|null $studentProfile
 * @property-read TeacherProfile|null $teacherProfile
 * @property-read Collection<int, TeacherSubject> $teacherSubjects
 * @property-read Collection<int, Subject> $taughtSubjects
 * @property-read Collection<int, Classroom> $taughtClassrooms
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        ];
    }

    /**
     * Get the student profile associated with the user.
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }

    /**
     * Get the teacher subject assignments.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'teacher_id');
    }

    /**
     * Get the subjects taught by this teacher.
     */
    public function taughtSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id')
            ->withPivot(['id', 'classroom_id'])
            ->withTimestamps();
    }

    /**
     * Get the classrooms assigned to this teacher.
     */
    public function taughtClassrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'teacher_subjects', 'teacher_id', 'classroom_id')
            ->withPivot(['id', 'subject_id'])
            ->withTimestamps();
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->studentProfile !== null;
    }

    /**
     * Check if the user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->teacherProfile !== null;
    }
}
