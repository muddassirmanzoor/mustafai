<?php

namespace App\Models\Admin;

use App\Models\Admin\MagazineCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Magazine extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
       
        return $this->belongsTo(MagazineCategory::class,'magazine_category_id','id');
    }
}
