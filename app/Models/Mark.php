<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $exam_id
 * @property int $student_id
 * @property string $marks_obtained
 * @property string|null $grade_letter
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Exam $exam
 * @property-read StudentProfile $student
 */
class Mark extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'marks_obtained',
        'grade_letter',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'marks_obtained' => 'decimal:2',
        ];
    }

    /**
     * Get the exam associated with this mark.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Get the student profile this mark belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
