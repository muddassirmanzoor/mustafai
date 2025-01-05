<?php

namespace App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Scope, Relations;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($post) {
            $post->files()->delete();
            $post->comments()->delete();
            $post->likes()->delete();
            $post->applied()->delete();
        });
    }

}
