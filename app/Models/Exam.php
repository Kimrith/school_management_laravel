<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property int $subject_id
 * @property int $classroom_id
 * @property Carbon $exam_date
 * @property string $total_marks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Subject $subject
 * @property-read Classroom $classroom
 * @property-read Collection<int, Mark> $marks
 */
class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'subject_id',
        'classroom_id',
        'exam_date',
        'total_marks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'total_marks' => 'decimal:2',
        ];
    }

    /**
     * Get the subject for this exam.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the classroom where this exam takes place.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Get all marks obtained in this exam.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'exam_id');
    }
}
