<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tehsil extends Model
{
    use HasFactory;
    protected $fillable = [
        'district_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function unionCouncils()
    {
        return $this->hasMany(UnionCouncil::class, 'tehsil_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo('App\Models\Admin\District');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\District','district_id','id');
    }
}
