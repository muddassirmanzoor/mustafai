<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\Zone;
use App\Repositories\AddressRepositoryInterface;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     *get country codes data
    */
    public function getCountryCodes(AddressRepositoryInterface $CountryCode)
    {
        return $CountryCode->getCountryCodes();
    }
    /**
     *get address data
    */
    public function getAddresses(Request $request)
    {
        $addressType = $request->addressType;
        switch ($addressType) {
            case "countries":
                $data = Country::where('status', 1)->get();
                break;
            case "provinces":
                $data = Province::where('status', 1)->get();
                break;
            case "divisions":
                $data = Division::where('status', 1)->get();
                break;
            case "districts":
                $data = District::where('status', 1)->get();
                break;
            case "tehsils":
                $data = Tehsil::where('status', 1)->get();
                break;
            case "cities":
                $data = City::where('status', 1)->get();
                break;
            case "branches":
                $data = Zone::where('status', 1)->get();
                break;
            case "union councils":
                $data = UnionCouncil::where('status', 1)->get();
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => "error",
                    'data' => 'please enter proper address type',
                ]);
                break;
        }
        return response()->json([
            'status' => 1,
            'message' => "success",
            'data' => $data->isEmpty() ? 'no record found' : $data,
        ]);
    }

    /**
     * filter all type of Addresses
     *@return \Illuminate\Http\JsonResponse
     */
    public function filterAddresses(Request $request)
    {
        $name_query = getQuery($request->lang, ['name']);
        $name_query[] = 'id';
        if ($request->has('country_id')) {

            $country_ids = explode(",", $request->country_id);

            $provinces = Province::query()->select(array_merge($name_query, ['country_id as countryId']))->whereIn('country_id', $country_ids)->get()
                ->groupBy(fn ($province) => Country::where('id', $province->countryId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => $provinces,
                    'total' => $provinces->count(),
                    'message' => "provinces  Listed sucessfully ",
                ]
            );
        }
        if ($request->has('province_id')) {

            $province_ids = explode(",", $request->province_id);

            $divisions = Division::query()->select(array_merge($name_query, ['province_id as provinceId']))->whereIn('province_id', $province_ids)->get()
                ->groupBy(fn ($division) => Province::where('id', $division->provinceId)->first()->name_english);

            $cities = City::query()->select(array_merge($name_query, ['province_id as provinceId']))->whereIn('province_id', $province_ids)->get()
                ->groupBy(fn ($city) => Province::where('id', $city->provinceId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => [
                        'divisions' => $divisions,
                        'cities' => $cities,
                    ],
                    'total' => $divisions->count(),

                    'city_total' => $cities->count(),
                    'message' => "divisions and cities and Listed sucessfully ",
                ]
            );
        }
        if ($request->has('division_id')) {
            $division_ids = explode(",", $request->division_id);

            $districts = District::query()->select(array_merge($name_query, ['division_id as divisionId']))->whereIn('division_id', $division_ids)->get()
                ->groupBy(fn ($district) => Division::where('id', $district->divisionId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => $districts,
                    'total' => $districts->count(),
                    'message' => "Districts Listed sucessfully ",
                ]
            );
        }
        if ($request->has('district_id')) {
            $district_ids = explode(",", $request->district_id);
            $tehsils = Tehsil::query()->select(array_merge($name_query, ['district_id as districtId']))->whereIn('district_id',  $district_ids)->get()
                ->groupBy(fn ($tehsil) => District::where('id', $tehsil->districtId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => $tehsils,
                    'total' => $tehsils->count(),
                    'message' => "Tehsils Listed sucessfully ",
                ]
            );
        }
        if ($request->has('tehsil_id')) {

            $tehsil_ids = explode(",", $request->tehsil_id);
            $zones = Zone::query()->select(array_merge($name_query, ['tehsil_id as tehsilId']))->whereIn('tehsil_id', $tehsil_ids)->get()
                ->groupBy(fn ($zone) => Tehsil::where('id', $zone->tehsilId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => $zones,
                    'total' => $zones->count(),
                    'message' => "Zones Listed sucessfully ",
                ]
            );
        }
        if ($request->has('zone_id')) {

            $zone_ids = explode(",", $request->tehsil_id);
            $councils = UnionCouncil::query()->select(array_merge($name_query, ['zone_id as zoneId']))->whereIn('zone_id', $zone_ids)->get()
                ->groupBy(fn ($unionCouncil) => Zone::where('id', $unionCouncil->zoneId)->first()->name_english);

            return response()->json(
                [
                    'status' => 200,
                    'data' => $councils,
                    'total' => $councils->count(),
                    'message' => "Councils Listed sucessfully ",
                ]
            );
        } else {
            $countries = Country::select($name_query)->where('status', 1)->get();
            $provinces = Province::select(array_merge($name_query, ['country_id as countryId']))->where('status', 1)->get();
            $divisions = Division::select(array_merge($name_query, ['province_id as provinceId']))->where('status', 1)->get();
            $cities = City::select(array_merge($name_query, ['province_id as provinceId']))->where('status', 1)->get();
            $districts = District::select(array_merge($name_query, ['division_id as divisionId']))->where('status', 1)->get();
            $tehsils = Tehsil::select(array_merge($name_query, ['district_id as districtId']))->where('status', 1)->get();
            $zones = Zone::select(array_merge($name_query, ['tehsil_id as tehsilId']))->where('status', 1)->get();
            $councils = UnionCouncil::select(array_merge($name_query, ['zone_id as zoneId']))->where('status', 1)->get();
            return response()->json(
                [
                    'status' => 200,
                    'message' => "Addresses Listed sucessfully ",
                    'data' => [
                        'countries' => $countries,
                        'provinces' => $provinces,
                        'division' => $divisions,
                        'cities' => $cities,
                        'districts' => $districts,
                        'tehsils' => $tehsils,
                        'zones' => $zones,
                        'councils' => $councils
                    ],
                ]
            );
        }
    }


    /**
     * GET ALL COUNTRIES
     *@return \Illuminate\Http\JsonResponse
     */
    public function getCountries(Request $request)
    {
        $countries = Country::select('id', 'name_english as name')->where('status', 1)->get();
        return response()->json(
            [
                'status' => 200,
                'message' => "Countries Listed sucessfully ",
                'data' => [
                    'countries' => $countries,
                ],
            ]
        );
    }
    /**
     *get filtered address data
    */
    public function filterUserListAddresses(Request $request)
    {
        // dd($request->tehsil_id);
        if(
            (isset($request->country_id) &&  !is_array($request->country_id))
            || (isset($request->province_id) &&  !is_array($request->province_id))
            || (isset($request->division_id) &&  !is_array($request->division_id))
            || (isset($request->district_id) &&  !is_array($request->district_id))
            || (isset($request->tehsil_id) &&  !is_array($request->tehsil_id))
            || (isset($request->zone_id) &&  !is_array($request->zone_id))

        )
        {
           return  $this->filterAddresses($request);
        }
        $name_query = getQuery($request->lang, ['name']);
        $name_query[] = 'id';
        $provinceCols = array_merge(getQuery($request->lang, ['name']), ['id', 'country_id']);
        $divisionCols = array_merge(getQuery($request->lang, ['name']), ['id', 'province_id']);
        $districtCols = array_merge(getQuery($request->lang, ['name']), ['id', 'division_id']);
        $tehsilCols = array_merge(getQuery($request->lang, ['name']), ['id', 'district_id']);
        $zoneCols = array_merge(getQuery($request->lang, ['name']), ['id', 'tehsil_id']);
        $unionCouncilCols = array_merge(getQuery($request->lang, ['name']), ['tehsil_id', 'zone_id']);
        if ($request->has('country_id')) {
            // dd("ok");
            $country_id = is_array($request->country_id)?$request->country_id:(array)$request->country_id;
            $provinces = Province::query()->select($provinceCols)->whereIn('country_id', $country_id)->get();
                // ->groupBy(fn($province) => Country::where('id', $province->country_id)->first()->name_english);
            // dd($provinces);
            return response()->json(['status' => 200, 'data' => $provinces, 'total' => $provinces->count()]);
        }
        if ($request->has('province_id')) {
            $divisions = Division::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get();
                // ->groupBy(fn($division) => Province::where('id', $division->province_id)->first()->name_english);
            $cities = City::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get();
                // ->groupBy(fn($city) => Province::where('id', $city->province_id)->first()->name_english);

            return response()->json(['status' => 200, 'data' => $divisions, 'total' => $divisions->count(), 'cities' => $cities, 'city_total' => $cities->count()]);
        }
        if ($request->has('division_id')) {
            $districts = District::query()->select($districtCols)->whereIn('division_id', $request->division_id)->get();
                // ->groupBy(fn($district) => Division::where('id', $district->division_id)->first()->name_english);

            return response()->json(['status' => 200, 'data' => $districts, 'total' => $districts->count()]);
        }
        if ($request->has('district_id')) {
            $tehsils = Tehsil::query()->select($tehsilCols)->whereIn('district_id', $request->district_id)->get();
                // ->groupBy(fn($tehsil) => District::where('id', $tehsil->district_id)->first()->name_english);

            return response()->json(['status' => 200, 'data' => $tehsils, 'total' => $tehsils->count()]);
        }
        if ($request->has('tehsil_id')) {
            $zones = Zone::query()->select($zoneCols)->whereIn('tehsil_id', $request->tehsil_id)->get();
                // ->groupBy(fn($zone) => Tehsil::where('id', $zone->tehsil_id)->first()->name_english);

            return response()->json(['status' => 200, 'data' => $zones, 'total' => $zones->count()]);
        }
        if ($request->has('zone_id')) {
            $councils = UnionCouncil::query()->select($unionCouncilCols)->whereIn('zone_id', $request->zone_id)->get();
                // ->groupBy(fn($unionCouncil) => Zone::where('id', $unionCouncil->zone_id)->first()->name_english);

            return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);
        }
    }
}
