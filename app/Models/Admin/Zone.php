<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = [
        'tehsil_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function unionCouncils()
    {
        return $this->hasMany(UnionCouncil::class, 'tehsil_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Tehsil','tehsil_id','id');
    }
}
