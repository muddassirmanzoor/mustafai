<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleted(function ($cabinet) {
            $cabinet->cabinetUsers()->delete();
        });
    }

    protected $guarded = [];

    public function childs() {
        return $this->hasMany($this,'parent_id','id') ;
    }

    public function cabinetUsers()
    {
        return $this->hasMany(CabinetUser::class,'cabinet_id');
    }
}
