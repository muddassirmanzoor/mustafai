<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryExtention extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function libraryType()
    {
        return $this->belongsTo(LibraryType::class);
    }
}
