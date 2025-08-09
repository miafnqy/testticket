<?php

namespace App\Enums;

/**
 * The mostly based users roles
 */
enum UserRole: int
{
    case ADMIN = 1;
    case MANAGER = 2;
    case MODERATOR = 3;
    case USER = 4;

    public function name(): string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::MANAGER => 'manager',
            self::MODERATOR => 'moderator',
            self::USER => 'user',
        };
    }

    public function priority(): int
    {
        return match ($this) {
            self::ADMIN => 0,
            self::MANAGER => 1,
            self::MODERATOR => 2,
            self::USER => 3,
        };
    }
}
