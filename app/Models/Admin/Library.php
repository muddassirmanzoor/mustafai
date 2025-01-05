<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function libraryType()
    {
        return $this->belongsTo(LibraryType::class);
    }
    public function parent()
    {
        return $this->belongsTo('Library', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Library', 'parent_id');
    }
}
