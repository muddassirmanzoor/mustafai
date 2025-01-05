<?php

namespace App\Http\Resources;

use App\Models\Admin\Occupation;
use App\Models\Chat\Contact;
use Illuminate\Http\Resources\Json\JsonResource;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;
use DB;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $role = \App\Models\Admin\Role::where('id', auth()->user()->role_id)->first();
        $cabinetUser = \App\Models\Admin\CabinetUser::where('designation_id', auth()->user()->login_role_id)->where('user_id',auth()->user()->id)->first();
        $friend = auth()->user()->id != $request->user_id ?$request->user_id : '';

            $friendStatus = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $friend
            ])->orWhere('user_1', $friend)->where('user_2', auth()->user()->id)->first();
        return [
            'id' => $this->id,
            'username' => $this->user_orignal_name,
            // 'full_name' => $this->full_name,
            'full_name' => availableField($this->user_name, $this->user_name_english, $this->user_name_urdu, $this->user_name_arabic),
            'username_english' =>$this->user_name_english,
            'username_urdu' => $this->user_name_urdu,
            'profileImage' => getS3File($this->profile_image),
            'coverImage' => getS3File($this->banner),
            'resume' => isset($this->resume) ? getS3File($this->resume): null,
            'tag_line' => $this->tagline== "null" ? '' :$this->tagline,
            'tagline_english' => $this->tagline_english== "null" ? '' :$this->tagline_english,
            'tagline_urdu' => $this->tagline_urdu== "null" ? '' :$this->tagline_urdu,
            'address' => $this->address,
            'blood_group' => $this->blood_group== "null" ? '' :$this->blood_group_english,
            'phone_number' => $this->phone_number,
            'fcm_token' => $this->fcm_token,
            'country_code_id' => $this->country_code_id,
            'full_phone_number' => optional($this->countryCode)->phonecode .  $this->phone_number,
            'email' => $this->email,
            'is_completed_profile' => $this->is_completed_profile,
            'is_public' => $this->is_public,
            'same_address' => $this->same_address,
            'about' => $this->about== "null" ? '' :$this->about,
            'about_english' => $this->about_english== "null" ? '' :$this->about_english,
            'about_urdu' => $this->about_urdu== "null" ? '' :$this->about_urdu,
            'experiences' => ExperienceResource::collection($this->experience()->latest()->get()),
            'education' => EducationResource::collection($this->education()->latest()->get()),
            // 'myPosts' => $this->posts,
            'occupation' => $this->occupation_id !== null ? Occupation::find($this->occupation_id) : null,
            'skills' => ['skills_english' => !empty($this->skills_english) ? explode(',', $this->skills_english) :[], 'skills_urdu' =>!empty($this->skills_urdu) ?  explode(',', $this->skills_urdu):[], 'skills_arabic' =>!empty($this->skills_arabic) ?  explode(',', $this->skills_arabic):[]],
            'cnic' => $this->cnic== "null" ? '' :$this->cnic,
            'subscription_fallback_role_id' => $this->subscription_fallback_role_id,
            'login_role_id' => $this->login_role_id,
            'rights'=>$this->getRights($this->login_role_id,$this->subscription_fallback_role_id),
            'role'=>isset($role->name_english) ? $role->name_english : '',
            'cabinet_name'=>isset($cabinetUser) ? $cabinetUser->cabinet->name_english : '',
            'friendStatus'=>$friendStatus,
            'country_id' => $this->country_id,
            'province_id' => $this->province_id,
            'division_id' => $this->division_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'tehsil_id' => $this->tehsil_id,
            'zone_id' => $this->zone_id,
            'union_council' => (!empty($this->union_council)) && isset($this->union_council)? $this->union_council->name_english :'',
            'postcode' => $this->postcode,
            'address_english' => $this->address_english,
            'address_urdu' => $this->address_urdu,
            'lang' => $this->lang,
            'permanentAddress' => (!empty($this->permanentAddress)) && isset($this->permanentAddress) ? $this->permanentAddress: '',
            'permanent_union_council' =>(!empty($this->permanentAddress)) && isset($this->permanentAddress->union_council_permanent_relation)? $this->permanentAddress->union_council_permanent_relation->name_english : '',
            'isBloodDonor' => empty(auth()->user()->donor) ? 0 : 1
        ];
    }
    public function with($request)
    {
        return [
            'status' => 1,
            'message' => 'profile information retrieved'
        ];
    }
    //function get rights
    public function getRights($login_role_id, $fallback_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $subscriptionFallBackRoleId = (int) $fallback_id;

            // Determine the primary role to check
            $authUserRole = empty($subscriptionFallBackRoleId) ? (int) $login_role_id : $subscriptionFallBackRoleId;

            // If the user has a designation, fetch rights from the designation
            if(empty($subscriptionFallBackRoleId)) {
                if (!empty($user->designation_id)) {
                    $designationRights = DB::table('designations')
                        ->where('id', $user->designation_id)
                        ->pluck('right_ids')
                        ->first();

                    if (!empty($designationRights)) {
                        return $designationRights;
                    }
                }
            }
            // If no designation rights, fallback to role-based rights
            $roleRights = DB::table('roles')
                ->where('id', $authUserRole)
                ->pluck('right_ids')
                ->first();

            return $roleRights ?: ''; // Return role rights or an empty string
        }

        return ''; // Return empty for unauthenticated users
    }

}
