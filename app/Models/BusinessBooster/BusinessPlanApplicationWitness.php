<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinesPlan;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanApplicationWitness extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessPlanApplication::class, 'application_id');
    }

}
