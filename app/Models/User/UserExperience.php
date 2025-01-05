<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'title_english',
        'title_urdu',
        'title_arabic',
        'experience_company_english',
        'experience_company_urdu',
        'experience_company_arabic',
        'experience_location_english',
        'experience_location_urdu',
        'experience_location_arabic',
        'experience_start_date',
        'experience_end_date',
        'is_currently_working',
    ];
    public $timestamps = true;
}
