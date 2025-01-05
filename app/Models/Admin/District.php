<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = [
        'division_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function tehsils()
    {
        return $this->hasMany(Tehsil::class, 'district_id', 'id');
    }
    public function division()
    {
        return $this->belongsTo('App\Models\Admin\Division');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Division','division_id','id');
    }
}
