<?php

namespace App\Enum;

enum NotificationType: string
{
    case INFORMATION = 'information';
    case ALERT = 'alert';
    case EMERGENCY = 'emergency';
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
