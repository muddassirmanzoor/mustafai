<?php

namespace App\Models\Posts\Post;

use App\Models\Admin\Admin;
use App\Models\City;
use App\Models\Posts\Comment\Comment;
use App\Models\Posts\Like\Like;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User;
use Illuminate\Http\Request;

trait Relations
{
    public function images()
    {
        return $this->hasMany(PostFile::class, 'post_id');
    }

    public function profile_images()
    {
        return $this->hasOne(PostFile::class, 'post_id');
    }
    public function files()
    {
        return $this->hasMany(PostFile::class, 'post_id');
    }

    public function file()
    {
        return $this->hasOne(PostFile::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class,'post_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shareUser()
    {
        return $this->belongsTo(User::class, 'share_id', 'id')->select(['id', 'user_name','user_name_english','user_name_urdu',
        'profile_image','is_public']);
    }

    public function like($foo)
    {
        dd($foo);
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applied()
    {
        return $this->hasMany(User\ApplyJob::class, 'job_post_id');
    }

    public function citi()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
}
