<?php

namespace App\Models\Traits;

use App\Models\Admin\Notification;
use App\Models\Chat\Chat;

trait HasNotifications
{
    /**
     * notifications that belong to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(Notification::class, 'notification_user', 'user_id', 'notification_id')
            ->withPivot('is_read', 'from_id', 'notification_type')
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

    public function unreadChat()
    {
        return Chat::where(['to_id' => $this->id,'status'=>0])->get();
    }
}
