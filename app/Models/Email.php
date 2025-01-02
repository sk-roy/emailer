<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'email', 'message', 'attachment', 'attachment_filename', 'status'];
    protected $table = 'mail-logs';

    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_IN_QUEUE = 'in-queue';

    public static function statuses(): array
    {
        return [
            self::STATUS_SENT,
            self::STATUS_FAILED,
            self::STATUS_IN_QUEUE,
        ];
    }
}
