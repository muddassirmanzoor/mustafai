<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinessPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * a business plan application belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessPlanApplication::class, 'application_id');
    }

}
