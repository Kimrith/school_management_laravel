<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $subject_id
 * @property int $classroom_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $teacher
 * @property-read Subject $subject
 * @property-read Classroom $classroom
 */
class TeacherSubject extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teacher_subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'classroom_id',
    ];

    /**
     * Get the teacher (user) assigned to this subject and classroom.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the subject assigned.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the classroom assigned.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
