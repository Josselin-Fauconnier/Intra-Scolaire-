<?php

namespace App\Enum;

enum GradeStatus: string
{
    case PENDING = "en_attente";
    case GRADED = "note";
}
