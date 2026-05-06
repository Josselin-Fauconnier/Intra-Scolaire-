<?php

namespace App\Enum;

enum UserRole: string
{
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case ADMIN = 'admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::STUDENT => 'Élève',
            self::TEACHER => 'Enseignant',
            self::ADMIN => 'Administrateur',
        };
    }
}
