<?php

namespace App\Enums;

/**
 * The mostly based users roles
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case MODERATOR = 'moderator';
    case USER = 'user';

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
