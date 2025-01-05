<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinessPlan;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanPaidInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'business_plan_paid_invoices';

    protected $fillable = ['plan_id','application_id','user_id','for_date','amount_required','amount','user_account_id','invoice'];

}
