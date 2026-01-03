<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'stage',
        'message',
        'status',
        'sent_at',
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
