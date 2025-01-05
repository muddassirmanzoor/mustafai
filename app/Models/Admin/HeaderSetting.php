<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getChilds()
    {
        return $this->hasMany(HeaderSetting::class, 'parent_id'); 
    }
}
