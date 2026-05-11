<?php

namespace App\Enum;

enum DocumentType: string
{
    case DOCUMENT = 'document';
    case IMAGE = 'image';
    case OTHER = 'other';
}
