<?php

namespace App\Enums;

enum StatusEnum: string
{
    case STATUS_SENT = 'sent';
    case STATUS_FAILED = 'failed';
    case STATUS_IN_QUEUE = 'in-queue';
}
