<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryAlbum extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function libraryType()
    {
        return $this->belongsTo(LibraryType::class, 'type_id', 'id');
    }

    public function libraryAlbumDetails()
    {
        return $this->hasMany(LibraryAlbumDetails::class, 'library_album_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('LibraryAlbum', 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(LibraryAlbum::class, 'parent_id');
    }

    public function parents()
    {
        return $this->hasMany(LibraryAlbum::class, 'parent_id', 'id');
    }

    public function employeSections()
    {
        return $this->belongsToMany(EmployeeSection::class, 'employee_section_library_albums');
    }
    public function getCounter(){

        return LibraryAlbum::where('parent_id',$this->parent_id)->count();

    }


}
