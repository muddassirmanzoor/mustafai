<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Page extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'admin_id',
        'title_english',
        'title_urdu',
        'title_arabic',
        'url',
        'short_description_english',
        'short_description_urdu',
        'short_description_arabic',
        'description_english',
        'description_urdu',
        'description_arabic',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'in_header',
        'in_footer',
        'image'
    ];
}
