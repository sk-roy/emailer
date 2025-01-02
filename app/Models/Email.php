<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'email', 'message', 'attachment', 'attachment_filename', 'status'];
    protected $table = 'mail-logs';
    
}
