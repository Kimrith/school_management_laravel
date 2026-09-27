<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $grade_level
 * @property string $academic_year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, StudentProfile> $studentProfiles
 * @property-read Collection<int, Attendance> $attendances
 * @property-read Collection<int, Exam> $exams
 * @property-read Collection<int, TeacherSubject> $teacherSubjects
 * @property-read Collection<int, User> $teachers
 * @property-read Collection<int, Subject> $subjects
 */
class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'grade_level',
        'academic_year',
    ];

    /**
     * Get the student profiles enrolled in this classroom.
     */
    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'classroom_id');
    }

    /**
     * Get the attendances recorded for this classroom.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'classroom_id');
    }

    /**
     * Get the exams scheduled for this classroom.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'classroom_id');
    }

    /**
     * Get all teacher-subject assignments for this classroom.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'classroom_id');
    }

    /**
     * Get the teachers assigned to this classroom.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'classroom_id', 'teacher_id')
            ->withPivot(['id', 'subject_id'])
            ->withTimestamps();
    }

    /**
     * Get the subjects taught in this classroom.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'classroom_id', 'subject_id')
            ->withPivot(['id', 'teacher_id'])
            ->withTimestamps();
    }
}
