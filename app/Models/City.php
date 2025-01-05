<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'province_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];

    public function province()
    {
        return $this->belongsTo('App\Models\Admin\Province');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Province','province_id','id');
    }
}
