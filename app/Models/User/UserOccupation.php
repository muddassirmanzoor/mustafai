<?php

namespace App\Models\User;

use App\Models\Admin\Occupation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOccupation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'user_occupations';


    public function userOccupation()
    {
        return $this->belongsTo(User::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class,'occupation_id','id');
    }
}
