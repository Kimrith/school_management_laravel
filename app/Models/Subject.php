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
 * @property string $name
 * @property string $code
 * @property int|null $level_id
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Level|null $level
 * @property-read Collection<int, Exam> $exams
 * @property-read Collection<int, TeacherSubject> $teacherSubjects
 * @property-read Collection<int, User> $teachers
 * @property-read Collection<int, Classroom> $classrooms
 */
class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'level_id',
        'description',
    ];

    /**
     * Get the academic level that this subject belongs to.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * Get all exams conducted for this subject.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }

    /**
     * Get all teacher-subject assignments for this subject.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'subject_id');
    }

    /**
     * Get teachers who teach this subject.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'subject_id', 'teacher_id')
            ->withPivot(['id', 'classroom_id'])
            ->withTimestamps();
    }

    /**
     * Get classrooms where this subject is taught.
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'teacher_subjects', 'subject_id', 'classroom_id')
            ->withPivot(['id', 'teacher_id'])
            ->withTimestamps()
            ->distinct();
    }
}
