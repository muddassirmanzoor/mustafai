<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Occupation;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserListingResource;

class UserFiltrationController extends Controller
{
    /**
     *get user filter address api back up
    */
    public function getUsersFilterContent(Request $request)
    {
        $cols = ['id', 'name_english', 'name_urdu', 'name_arabic'];
        $countries = Country::select($cols)->where('status', 1)->get();
        $provinces = Province::select($cols)->where('status', 1)->get();
        $divisions = Division::select($cols)->where('status', 1)->get();
        $districts = District::select($cols)->where('status', 1)->get();
        $tehsils = Tehsil::select($cols)->where('status', 1)->get();
        $zones = Zone::select($cols)->where('status', 1)->get();
        $councils = UnionCouncil::select($cols)->where('status', 1)->get();

        return response()->json(['status' => 1, 'data' => compact('countries', 'provinces', 'divisions', 'districts', 'tehsils', 'zones', 'councils')]);
    }
    
    /**
     *get user list api back up
    */
    public function getUsersListing(Request $request)
    {
        // $lang=$request->lang;
        $userCols = array_merge(getQuery($request->lang, ['user_name', 'address']), ['id', 'email', 'is_public', 'full_name', 'phone_number', 'occupation_id']);
        $users = User::query()->select($userCols)
        ->with(['occupationUser' => function ($query)use($request) {
            $occupationCols = array_merge(getQuery($request->lang, ['title']));
            $query->select( $occupationCols);
        }])
            ->when($request->country_id, fn ($q) => $q->where('country_id', $request->country_id))
            ->when($request->province_id, fn ($q) => $q->where('province_id', $request->province_id))
            ->when($request->division_id, fn ($q) => $q->where('division_id', $request->division_id))
            ->when($request->district_id, fn ($q) => $q->where('district_id', $request->district_id))
            ->when($request->tehsil_id, fn ($q) => $q->where('tehsil_id', $request->tehsil_id))
            ->when($request->zone_id, fn ($q) => $q->where('zone_id', $request->zone_id))
            ->when($request->union_council_id, fn ($q) => $q->where('union_council_id', $request->union_council_id))
            ->where('id', '>', $request->lastUserId ?? 0)
            ->limit($request->limit ?? 8)
            ->get();

        if (!$users->isEmpty()) {
            $users->extra = (object) ['from' => 'blah'];
            $data = UserListingResource::collection($users);
            return response()->json([
                'status'  => 1,
                'message' => "success",
                'data' => $data,
            ]);
        } else {

            return response()
                ->json([
                    'status' => 0,
                    'data' => [],
                    'message' => trans('api.No record found', array(), $request->lang)
                ]);
        }
    }
}
