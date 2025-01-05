<?php

namespace App\Models\Posts\Post;
trait Scope
{
    public function scopeActive($query)
    {
        return $query->where('posts.status', 1);
    }

    public function scopeAnnouncement($query)
    {
        return $query->where('post_type', 'announcement');
    }

    public function scopeJob($query)
    {
        return $query->where('post_type', '2');
    }

    public function scopeSimple($query)
    {
        return $query->where('post_type', '1');
    }

    public function scopeAdmin($query)
    {
        return $query->where('admin_id', '<>', '');
    }

    public function scopeSeekers($query)
    {
        return $query->where('job_type', 2);
    }

    public function scopeHiring($query)
    {
        return $query->where('job_type', 1);
    }

    public function scopeUser($query)
    {
        return $query->where('admin_id', '')->orWhere('admin_id', null);
    }
}
