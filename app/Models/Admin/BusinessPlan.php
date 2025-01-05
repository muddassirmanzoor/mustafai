<?php

namespace App\Models\Admin;

use App\Models\BusinessBooster\BusinessPlanApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\BusinessPlanPaymentMethod;
use App;

class BusinessPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($businessPlan) {
            $businessPlan->applications()->delete();
        });
    }

    function availablePlans($lang = '')
    {
        $query = getQuery(!empty($lang) ? $lang : App::getLocale(), ['name','description','term_conditions']);
        $query[] = 'id';
        $query[] = 'type';
        $query[] = 'invoice_amount';
        $query[] = 'total_invoices';
        $query[] = 'total_users';
        $query[] = 'start_date';

        return $this->select($query)->where('status',1)->orderBy('id','DESC')->get();
    }

    public function applications()
    {
        return $this->hasMany(BusinessPlanApplication::class, 'plan_id');
    }

    public function application()
    {
        return $this->hasOne(BusinessPlanApplication::class, 'plan_id');
    }
    public function businessPlanPaymentMethod()
    {
        return $this->hasMany(BusinessPlanPaymentMethod::class,'business_plan_id');
    }
}
