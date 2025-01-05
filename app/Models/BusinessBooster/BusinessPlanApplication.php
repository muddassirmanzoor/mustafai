<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinessPlan;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'business_plan_applications';

    protected $fillable = ['plan_id','applicant_id','selected_date','form_serial_number','form_date','form_contact_number','form_nic_number','form_full_name','form_guardian_name','form_business_coessentiality','form_plan_reason','form_temp_address','form_permanent_address','form_image','status','zaminan_json'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($application) {
            $application->applicationAccounts()->delete();
            $application->applicationPronotes()->delete();
            $application->applicationWitnesses()->delete();
        });
    }

    /**
     * a business plan application belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    /**
     * application belongs to plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessPlan::class, 'plan_id');
    }

    public function applicationAccounts()
    {
        return $this->hasMany(BusinessPlanApplicationAccount::class,'application_id');
    }

    public function applicationPaidInvoice()
    {
        return $this->hasOne(BusinessPlanPaidInvoice::class,'application_id');
    }

    public function applicationWitnesses()
    {
        return $this->hasMany(BusinessPlanApplicationWitness::class,'application_id');
    }

    public function applicationPronotes()
    {
        return $this->hasMany(BusinessPlanApplicationPronote::class,'application_id');
    }

    public function relief()
    {
        return $this->hasMany(BusinessPlanUserReliefDate::class);
    }
}
