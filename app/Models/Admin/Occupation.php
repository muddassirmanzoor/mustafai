<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Occupation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function occupation()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function subProfession() {
        return $this->hasMany($this,'parent_id','id') ;
    }

    public function occupations()
    {
        return $this->hasMany(Occupation::class, 'parent_id');
    }
   
    public function childrenOccupations()
    {
        return $this->hasMany(Occupation::class, 'parent_id')->with('occupations');
    }
}
