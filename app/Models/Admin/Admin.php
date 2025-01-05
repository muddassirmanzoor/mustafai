<?php

namespace App\Models\Admin;

use App\Models\Posts\Post\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory;
    protected $fillable = [
        'role_id',
        'country_code_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'origional_password',
        'remember_token',
        'dob',
        'profile',
        'status'
    ];

    protected $guard = 'admin';

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($admin) {
            AdminNotification::where('admin_id', $admin->id)->delete();
            Post::where('admin_id', $admin->id)->delete();
            NotificationUser::where('notification_type', 0)->where('from_id', $admin->id)->delete();
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function getNameAttribute($value)
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getProfileAttribute($profile)
    {
        return is_null($profile) || $profile == '' ? 'user/images/site-logo.png' : $profile;
    }

    /**
     * get notifications
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(Notification::class, 'admin_notification', 'admin_id', 'notification_id')
            ->withPivot('is_read')
            ->withTimestamps()
            ->latest();
    }
    /**
     * get read notifications
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readNotifications()
    {
        return $this->notifications()->wherePivot('is_read', 1);
    }

    /**
     * get unread notifications
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->wherePivot('is_read', 0);
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'admin_id');
    }
}
