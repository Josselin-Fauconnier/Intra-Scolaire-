<?php

namespace App\Enum;

enum AttendanceType: string
{
    case PRESENT   = "present";
    case ABSENT = "absent";
    case LATE = "retard";
}
