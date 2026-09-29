<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Classroom> $classrooms
 * @property-read Collection<int, Subject> $subjects
 */
class Level extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get subjects belonging to this academic level.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'level_id');
    }

    /**
     * Get classrooms associated with this academic level.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'level_id');
    }
}
