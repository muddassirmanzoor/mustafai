<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'sliders';

    protected $fillable = [
        'admin_id',
        'image',
        'last_name',
        'content_english',
        'content_urdu',
        'content_arabic',
        'status',
        'title',
        'btn_text_english',
        'btn_text_urdu',
        'btn_text_arabic',
        'btn_link',
        'mobile_content_english',
        'mobile_content_urdu',
        'mobile_content_arabic'

    ];
}
