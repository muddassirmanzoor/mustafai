<?php

namespace App\Models\Admin;

use App\Models\EventImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventImage::class, 'event_id');
    }

    public function sessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventSession::class, 'event_id');
    }

    // image accessor
    public function getImageAttribute($profile)
    {
        return is_null($profile) || $profile == '' ? 'images/dummy-images/events_calendar.png' : $profile;
    }
}
