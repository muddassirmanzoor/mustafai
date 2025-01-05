<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'status',
    ];
}
