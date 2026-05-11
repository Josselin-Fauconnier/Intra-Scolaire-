<?php

namespace App\Enum;

enum UserRole: string
{
    case STUDENT = 'ROLE_STUDENT';
    case TEACHER = 'ROLE_TEACHER';
    case ADMIN = 'ROLE_ADMIN';

    public function getLabel(): string
    {
        return match ($this) {
            self::STUDENT => 'Élève',
            self::TEACHER => 'Enseignant',
            self::ADMIN => 'Administrateur',
        };
    }
}
