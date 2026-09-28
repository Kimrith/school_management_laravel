<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Student = 'student';
    case Teacher = 'teacher';

    /**
     * Get the display label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Student => 'Student',
            self::Teacher => 'Teacher',
        };
    }

    /**
     * Get all role values.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
