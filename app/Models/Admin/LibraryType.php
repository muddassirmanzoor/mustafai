<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryType extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function libraryAlbum()
    {
        return $this->hasMany(LibraryAlbum::class, 'type_id', 'id');
    }

    public function libraryextentions()
    {
        return $this->hasMany(LibraryExtention::class, 'type_id', 'id');
    }
}
