<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagazineCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function magzines()
    {
        return $this->hasMany(Magazine::class);
    }
}
