<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryAlbumDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function libraryAlbum()
    {
        return $this->belongsTo(LibraryAlbum::class);
    }
    public function libraryAlbumWithType()
    {
        return $this->belongsTo(LibraryAlbum::class)->where('type_id',1);
    }

    public function albumDetailsWithType(){
        // dd("ok");
        dd($this->libraryAlbumWithType()->get());
        LibraryAlbum::all();
    }

    // public function gettitleEnglishAttribute($data)
    // {
    //     return strtoupper($data);
    // }
    // public function gettitleUrduAttribute($data)
    // {
    //     return strtoupper($data);
    // }
    // public function getcontentUrduAttribute($data)
    // {
    //     return strtoupper($data);
    // }
    // public function getcontentEnglishAttribute($data)
    // {
    //     return strtoupper($data);
    // }

}
