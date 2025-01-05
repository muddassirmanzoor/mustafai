<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function divisions()
    {
        return $this->hasMany(Division::class, 'province_id', 'id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Admin\Country');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Country','country_id','id');
    }
}
