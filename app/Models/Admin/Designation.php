<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'name_english','name_urdu','name_arabic' ,'right_ids', 'type', 'status'
    ];

    public function admins()
    {
        return $this->hasMany('App\Models\Admin\Admin');
    }
}
