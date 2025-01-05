<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TrigerEmail extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'details',
        'is_sent',
    ];
}
