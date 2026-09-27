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
 * @property int $user_id
 * @property int|null $classroom_id
 * @property string $student_code
 * @property Carbon|null $date_of_birth
 * @property string $gender
 * @property string|null $parent_name
 * @property string|null $parent_phone
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Classroom|null $classroom
 * @property-read Collection<int, Attendance> $attendances
 * @property-read Collection<int, Mark> $marks
 * @property-read Collection<int, FeeInvoice> $feeInvoices
 */
class StudentProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'classroom_id',
        'student_code',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the user account for this student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the classroom this student is enrolled in.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Get all attendance records for this student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Get all exam marks for this student.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'student_id');
    }

    /**
     * Get all fee invoices for this student.
     */
    public function feeInvoices(): HasMany
    {
        return $this->hasMany(FeeInvoice::class, 'student_id');
    }
}
