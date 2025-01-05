<?php

namespace App\Models\User;

use App\Models\Posts\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    // function post()
    // {
    //     return $this->belongsToMany(Post::class, '');
    // }

}
