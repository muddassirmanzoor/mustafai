<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\Self_;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * get all users of specific notifications
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withTimestamps();
    }
}
