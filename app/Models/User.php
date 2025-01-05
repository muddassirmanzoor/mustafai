<?php



namespace App\Models;



use App\Models\Admin\CabinetUser;

use App\Models\Admin\Country;

use App\Models\Admin\CountryCode;

use App\Models\Admin\District;

use App\Models\Admin\Division;

use App\Models\Admin\DonationReceipt;

use App\Models\Admin\Donor;

use App\Models\Admin\Occupation;

use App\Models\Admin\Role;
use App\Models\Admin\Designation;

use App\Models\Admin\Notification;

use App\Models\Admin\Province;

use App\Models\Admin\Tehsil;

use App\Models\Admin\UnionCouncil;

use App\Models\Admin\UserSubscription;

use App\Models\BusinessBooster\BusinessPlanApplication;

use App\Models\Chat\Chat;

use App\Models\Chat\Contact;

use App\Models\Chat\Group;

use App\Models\Chat\GroupUser;

use App\Models\Posts\Post\Post;

use App\Models\Store\Order;

use App\Models\Traits\HasNotifications;

use App\Models\Traits\ResetMailNotification;

use App\Models\User\Activity;

use App\Models\User\PermanentAddress;

use App\Models\User\SecondaryPhone;

use App\Models\User\UserEducation;

use App\Models\User\UserExperience;

use App\Models\User\UserOccupation;

use App\Models\Admin\UserAccount;

use App\Models\Admin\Zone;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\App;

use Laravel\Sanctum\HasApiTokens;



// use Illuminate\Notifications\Notifiable;

use PhpParser\Node\Stmt\GroupUse;

use DB;

use Session;

use Illuminate\Notifications\Notifiable;



class User extends Authenticatable

{

    use HasApiTokens, HasFactory, HasNotifications,ResetMailNotification;

    // use Notifiable;





    /**

     * The "booted" method of the model.

     *

     * @return void

     */

    protected static function booted()

    {

        static::deleted(function ($user) {

            $user->posts()->delete();

            $user->notifications()->delete();

            // $user->getFriendRequests()->delete();

            $user->experience()->delete();

            $user->education()->delete();

            $user->activities()->delete();

            $user->cabinetUsers()->delete();

            $user->donor()->delete();

            Order::where('user_id', $user->id)->delete();

            UserSubscription::where('user_id', $user->id)->delete();

            DonationReceipt::where('user_id', $user->id)->delete();

            BusinessPlanApplication::where('applicant_id', $user->id)->delete();

        });

    }





    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $guarded = [];



    /**

     * The attributes that should be hidden for serialization.

     *

     * @var array<int, string>

     */

    protected $hidden = [

        'password',

        'original_password',

        'remember_token',

    ];

    protected $rules = [

        'email' => 'unique:users,email',

    ];



    protected $fillable = [



        'email',

        'user_name',

        'full_name',

        'user_name_english',

        'user_name_urdu',

        'phone_number',

        'user_name_arabic',

        'role_id',

        'designation_id',


        'country_code_id',

        'occupation_id',

        'subscription_amount',

        'subscription_amount_cycles',

        'phone_number',

        'skills_english',

        'skills_urdu',

        'skills_arabic',

        'tagline_english',

        'tagline_urdu',

        'tagline_arabic',

        'address_english',

        'address_urdu',

        'address_arabic',

        'blood_group_english',

        'blood_group_urdu',

        'blood_group_arabic',

        'about_english',

        'about_urdu',

        'about_arabic',

        'login_role_id',

        'cnic',

        'password',

        'original_password',

        'subscription_fallback_role_id',

        'fcm_token',

        'is_completed_profile',

        'country_id',

        'province_id',

        'division_id',

        'city_id',

        'district_id',

        'tehsil_id',

        'zone_id',

        'union_council_id',

        'postcode',

        'status',

        'is_public',

        'location_city',

        'location_country',

        'ip_address',

        'last_cron_notification_time',

        'lang'





    ];

    /**

     * The attributes that should be cast.

     *

     * @var array<string, string>

     */

    protected $casts = [

        'email_verified_at' => 'datetime',

    ];







    public function cabinetLoginRole()

    {

        if (\Session::has("role_id")) {

            return Session::get("role_id");

        } else {

            return 2;

            //calculate and save role in session

        }

    }



    /*relations*/



    public function role()

    {

        return $this->belongsTo(Role::class);

    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }



    public function city()

    {

        return $this->belongsTo(City::class);

    }

    public function countryCode()

    {

        return $this->belongsTo(CountryCode::class, 'country_code_id', 'id');

    }



    public function posts()

    {

        return $this->hasMany(Post::class, 'user_id');

    }



    public function userSubscriptions()

    {

        return $this->hasMany(UserSubscription::class, 'user_id');

    }



    public function userOccupation()

    {

        return $this->hasMany(UserOccupation::class, 'user_id');

    }



    public function userOccupations()

    {

        return $this->hasMany(UserOccupation::class, 'user_id', 'id');

    }

    public function mainOccupation()

    {

        return $this->userOccupation->occupation->whereNull('parent_id');



    }





    public function experience()

    {

        return $this->hasMany(UserExperience::class, 'user_id');

    }

    public function education()

    {

        return $this->hasMany(UserEducation::class, 'user_id');

    }



    public function permanentAddress()

    {

        return $this->hasOne(PermanentAddress::class, 'user_id', 'id');

    }



    public function secondaryPhones()

    {

        return $this->hasMany(SecondaryPhone::class, 'user_id');

    }



    /*scopes*/



    public function scopeActive($query)

    {

        return $query->where('status', 1);

    }



    public function getFriends()

    {



        $myID = $this->id;

        return User::select('users.id', 'users.profile_image', 'users.user_name', 'users.user_name', 'users.tagline_english', 'contacts.status')->join('contacts', function ($join) {

            $join->on('contacts.user_1', '=', 'users.id')

                ->orOn('contacts.user_2', '=', 'users.id');

        })

            ->withCount('unreadMessages')

            ->whereRaw('(`contacts`.`user_1` = ' . $myID . ' and (`contacts`.`status` = 1 or  `contacts`.`status` = 2)) OR (`contacts`.`user_2` = ' . $myID . ' and (`contacts`.`status` = 1 or `contacts`.`status` = 2))')

            ->where('users.id', '!=', $myID)

            ->get();

    }



    public function getExisingFriends()

    {



        $myID = $this->id;

        return User::select('users.id', 'users.profile_image', 'users.user_name', 'users.user_name', 'users.tagline_english')->join('contacts', function ($join) {

            $join->on('contacts.user_1', '=', 'users.id')

                ->orOn('contacts.user_2', '=', 'users.id');

        })

            ->withCount('unreadMessages')

            ->whereRaw('(`contacts`.`user_1` = ' . $myID . ' and (`contacts`.`status` = 1)) OR (`contacts`.`user_2` = ' . $myID . ' and (`contacts`.`status` = 1))')

            ->where('users.id', '!=', $myID)

            ->get();

    }



    public function getFriendRequests()

    {

        $myID = $this->id;

        return User::select('users.id', 'users.user_name', 'contacts.id as conId', 'users.profile_image')

            ->join('contacts', function ($join) {

                $join->on('contacts.user_1', '=', 'users.id')

                    ->orOn('contacts.user_2', '=', 'users.id');

            })

            ->where('contacts.user_2', $myID)

            ->where('contacts.status', 0)

            ->where('users.id', '!=', $myID)

            ->get();

    }





    public function getFriendRequestApi($lastId, $limit = 100)

    {

        $myID = $this->id;

        return User::select('users.id', 'users.user_name', 'users.full_name', 'contacts.id as conId', 'users.profile_image')

            ->join('contacts', function ($join) {

                $join->on('contacts.user_1', '=', 'users.id')

                    ->orOn('contacts.user_2', '=', 'users.id');

            })

            ->where('contacts.user_2', $myID)

            ->where('contacts.status', 0)

            ->where('users.id', '!=', $myID)

            ->where('contacts.id', '>', $lastId)

            ->limit($limit)

            ->get();

    }



    public function getYourSentRequests()

    {

        $myID = $this->id;

        $contactIds=[];

        $contacts = Contact::where(function ($query) use ($myID) {

            $query->where('user_1', $myID)

                ->orWhere('user_2', $myID);

        })

        ->where('status', 0)

        ->get(['user_1', 'user_2']);

        // $contacts=Contact::where('user_1',$myID)->orWhere('user_2',$myID)->get(['user_1', 'user_2']);

        foreach($contacts as $contact){

            if($contact->user_1==$myID){

                $contactIds[]=$contact->user_2;

            }

            else{

                $contactIds[]=$contact->user_1;

            }

        }

       return $contactIds;

        // return User::select('users.id', 'users.user_name', 'contacts.id as conId')->join('contacts', function ($join) {

        //     $join->on('contacts.user_1', '=', 'users.id')

        //         ->orOn('contacts.user_2', '=', 'users.id');

        // })

        //     ->where(function ($query) use ($myID) {

        //         $query->where([

        //             'contacts.user_1' => $myID,

        //             'contacts.status' => 0,

        //         ])

        //             ->orWhere([

        //                 'contacts.user_2' => $myID,

        //                 'contacts.status' => 0,

        //             ]);

        //     })

        //     ->where('users.id', '!=', $myID)

        //     ->get();

    }



    public function myCreatedGroups()

    {

        return $this->hasMany(Group::class, 'created_by');

    }



    public function myGroups()

    {

        return $this->hasMany(GroupUser::class, 'user_id');

    }



    public function donor()

    {

        return $this->hasOne(Donor::class, 'user_id');

    }



    public function userAccount()

    {

        return $this->hasOne(UserAccount::class, 'user_id');

    }

    public function getAvailableUsersForFR()

    {

        $friends = $this->getExisingFriends()->pluck('id');

        $friendRequests = $this->getYourSentRequests();

        // dd($friendRequests);

        return self::where(['status' => 1, 'is_public' => 1])->whereNotIn('id', $friends)->whereNotIn('id', $friendRequests)->get()->except($this->id);

    }



    public function apiGetAvailableUsersForFR($lastID, $limit = 100)

    {

        $friends = $this->getExisingFriends()->pluck('id');

        $friendRequests = $this->getYourSentRequests();

        return self::where(['status' => 1, 'is_public' => 1])->whereNotIn('id', $friends)->where('id', '>', $lastID)->limit($limit)->get()->except($this->id);
//        return self::where(['status' => 1, 'is_public' => 1])->whereNotIn('id', $friends)->whereNotIn('id', $friendRequests)->where('id', '>', $lastID)->limit($limit)->get()->except($this->id);

    }



    // image accessor

    public function getProfileImageAttribute($profile)

    {

        return is_null($profile) || $profile == '' ? 'images/avatar.png' : $profile;

    }



    // banner_image accessor

    public function getBannerAttribute($banner)

    {

        return is_null($banner) || $banner == '' ? 'images/cover-image.png' : $banner;

    }

    public function occupation()

    {

        return $this->hasOne(Occupation::class, 'id', 'occupation_id');

    }

    public function occupationUser()

    {

        return $this->belongsTo(Occupation::class,'occupation_id');

    }

    public function country()

    {

        return $this->hasOne(Country::class, 'id', 'country_id');

    }

    public function province()

    {

        return $this->hasOne(Province::class, 'id', 'province_id');

    }

    public function division()

    {

        return $this->hasOne(Division::class, 'id', 'division_id');

    }

    public function district()

    {

        return $this->hasOne(District::class, 'id', 'district_id');

    }

    public function tehsil()

    {

        return $this->hasOne(Tehsil::class, 'id', 'tehsil_id');

    }

    public function zone()

    {

        return $this->hasOne(Zone::class, 'id', 'zone_id');

    }





    //_______ accessor for__ userName_________//



    // public function getUserNameAttribute()

    // {

    //     // return $colum_name = 'user_name_' . App::getLocale();

    //     return $this->attributes['user_name_' . App::getLocale()];



    // }



    /**

     * get user activites

     * @return \Illuminate\Database\Eloquent\Relations\HasMany

     */

    public function activities()

    {

        return $this->hasMany(Activity::class, 'user_id');

    }



    /**

     * log user activity

     * @param String|null $body

     * @param String|null $link

     * @return void

     */

    public function log(string $body = null, string $link = null): void

    {

        $activity = new Activity();



        $activity->create([

            'user_id' => $this->id,

            'body' => $body,

            'link' => $link,

        ]);

    }



    //___________set user name value____///



    // public function setUserNameEnglishAttribute($value)

    // {

    //     $this->attributes['user_name'] = $value;

    //     $this->attributes['user_name_english'] = $value;

    // }

    public function unreadMessages()

    {

        return $this->hasMany(Chat::class, 'from_id')->where('status', 0);

    }



    //user has many  cabinets

    public function cabinetUsers()

    {

        return $this->hasMany(CabinetUser::class, 'user_id');

    }



    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany

    {

        return $this->hasMany(BusinessPlanApplication::class, 'applicant_id', 'id');

    }



    function getContactCounters()

    {

        \DB::statement("SET SQL_MODE=''");



        $myID = $this->id;



        $counters = Chat::selectRaw('count(id) as unread_messages_count,from_id as id,to_id')->where(['to_id' => $myID, 'status' => 0])->groupBy('to_id')->get()->toArray();



        return $counters;

    }



    function updateUserSubscriptionStatus()

    {

        $date = time();

        $subscription = $this->userSubscriptions()->where('subscription_start_date', '<=', $date)->where('subscription_end_date', '>=', $date)->first();



        if (!empty($subscription)) {

            if ($subscription->is_paid == 0) {

                $role_id = settingValue('fall_back_role_id');

                $this->update(['subscription_fallback_role_id' => $role_id]);

                return false;

            } else {

                $this->update(['subscription_fallback_role_id' => null]);

                return true;

            }

        }

    }

    function manyOccupations()

    {

        return $this->belongsToMany(Occupation::class, 'user_occupations');

    }



    public function union_council()

    {

        return $this->hasOne(UnionCouncil::class, 'id', 'union_council_id');

    }

}

