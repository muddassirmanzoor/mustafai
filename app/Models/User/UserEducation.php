<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'institute_english',
        'institute_urdu',
        'institute_arabic',
        'degree_name_english',
        'degree_name_urdu',
        'degree_name_arabic',
        'start_date',
        'end_date'
    ];
    public $timestamps = true;
}
