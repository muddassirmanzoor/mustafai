<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinessPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDateChangeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $fillable = ['application_id','user_id','date','status'];

    public function application()
    {
        return $this->hasOne(BusinessPlanApplication::class,'id','application_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
