<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $student_id
 * @property string $title
 * @property string $amount
 * @property Carbon $due_date
 * @property string $status
 * @property Carbon|null $paid_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read StudentProfile $student
 */
class FeeInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'title',
        'amount',
        'due_date',
        'status',
        'paid_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    /**
     * Get the student profile this fee invoice belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    /**
     * Check if the fee invoice is fully paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the fee invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date->isPast();
    }
}
