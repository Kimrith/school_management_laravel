<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $qualification
 * @property string|null $specialization
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Collection<int, TeacherSubject> $teacherSubjects
 * @property-read Collection<int, Subject> $taughtSubjects
 * @property-read Collection<int, Classroom> $taughtClassrooms
 */
class TeacherProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'qualification',
        'specialization',
        'address',
    ];

    /**
     * Get the user account for this teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the teacher-subject assignments for this teacher profile.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'teacher_id', 'user_id');
    }

    /**
     * Get subjects taught by this teacher through their user account.
     */
    public function taughtSubjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'teacher_subjects',
            'teacher_id',
            'subject_id',
            'user_id',
            'id'
        )->withPivot(['id', 'classroom_id'])->withTimestamps();
    }

    /**
     * Get classrooms assigned to this teacher through their user account.
     */
    public function taughtClassrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'teacher_subjects',
            'teacher_id',
            'classroom_id',
            'user_id',
            'id'
        )->withPivot(['id', 'subject_id'])->withTimestamps()->distinct();
    }
}
