<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeoMessage extends Model
{
    use HasFactory;
    protected $table = 'ceo_messages';
    protected $fillable = [
        'admin_id',
        'image',
        'message_english',
        'message_urdu',
        'message_arabic',
        'status',
        'message_title',
    ];
}
