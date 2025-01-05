<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_id', 'id');
    }
}
