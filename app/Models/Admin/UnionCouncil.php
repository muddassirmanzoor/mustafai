<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionCouncil extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'tehsil_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'status',
    ];
    public function tehsil()
    {
        return $this->belongsTo('App\Models\Admin\Tehsil','tehsil_id','id');
    }
    public function zone()
    {
        return $this->belongsTo('App\Models\Admin\Zone','zone_id','id');
    }
}
