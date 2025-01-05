<?php



namespace App\Http\Controllers\Home;



use App\Http\Controllers\Controller;

use App\Models\Admin\City;

use App\Models\Admin\Country;

use App\Models\Admin\District;

use App\Models\Admin\Division;

use App\Models\Admin\Province;

use App\Models\Admin\Tehsil;

use App\Models\Admin\UnionCouncil;
use Illuminate\Support\Facades\App;

use App\Models\Admin\Zone;

use App\Models\User;

use Illuminate\Http\Request;


use App\Models\Admin\Occupation;

use DataTables;



class OccupationController extends Controller

{

    public function __construct()

    {

    }



    /**

     *show profession details for website

    */

    public function index(Request $request, $slug)

    {

        // $guard = $request->header('Authorization');

        // dd($guard);



        $query = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);



        $occupation = Occupation::select($query)->where('slug', $slug)->where('status', 1)->first();



        [$countries, $provinces, $divisions, $cities, $districts, $tehsils, $zones, $councils, $occupations] = $this->getFiltersData();



        if ($request->ajax()) {

            $occupationUsers = User::query()

                ->select('id', 'user_name', 'email', 'full_name', 'profile_image')

                ->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id))

                ->get();



            $datatable = Datatables::of($occupationUsers);

            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('profile_image', function ($row) {
                if (!empty($row)) {
                    $profile_img = getS3File($row->profile_image);
                }
                $img = '<img src="' . $profile_img . '"class="img-fluid" style="width: 150px; height: auto;">';
                return $img ;
            });
            $datatable = $datatable->rawColumns(['profile_image']);
            $datatable = $datatable->make(true);



            return $datatable;

        }

        return view('home.occupation-page', get_defined_vars());

    }



    /**

     *get Filtered data of professions for website

    */

    public function getFiltersData()

    {

        $name_query = array_merge(getQuery(\Illuminate\Support\Facades\App::getLocale(), ['name']), ['id']);
        $countries = Country::select($name_query)->where('status', 1)->get();
        $provinces = Province::select($name_query)->where('status', 1)->get();
        $divisions = Division::select($name_query)->where('status', 1)->get();
        $cities = City::select($name_query)->where('status', 1)->get();
        $districts = District::select($name_query)->where('status', 1)->get();
        $tehsils = Tehsil::select($name_query)->where('status', 1)->get();
        $zones = Zone::select($name_query)->where('status', 1)->get();
        $councils = UnionCouncil::select($name_query)->where('status', 1)->get();
        $occupationCols = array_merge(getQuery(\Illuminate\Support\Facades\App::getLocale(), ['title']), ['id', 'parent_id']);
        $occupations = Occupation::query()
            ->select($occupationCols)
            ->where('status', 1)
            ->where('parent_id', null)
            ->with(['subProfession' => fn ($q) => $q->select($occupationCols)])->get();
        return [$countries, $provinces, $divisions, $cities, $districts, $tehsils, $zones, $councils, $occupations];

    }
    /**

     *get Filtered profession addressed without token for both website and Mobile App

    */

    public function filterProfessionAddresses(Request $request)

    {

        // Check if the 'lang' parameter is present in the request
        if ($request->has('lang')) {
            $locale = $request->input('lang');
            // Store the locale in the session
            session(['locale' => $locale]);
        } else {
            // Retrieve the locale from the session or fallback to the default locale
            $locale = session('locale', App::getLocale());
        }
        $request['lang'] = $locale;

        if(isset($request->lang)){
            App::setLocale($request->lang);
        }

        $name_query = getQuery(App::getLocale(), ['name']);

        $name_query[] = 'id';

        $provinceCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'country_id']);

        $divisionCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'province_id']);

        $districtCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'division_id']);

        $tehsilCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'district_id']);

        $zoneCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'tehsil_id']);

        $unionCouncilCols = array_merge(getQuery(App::getLocale(), ['name']), ['tehsil_id', 'zone_id']);

        if ($request->ajax()) {

            if ($request->has('country_id')) {

                $country_id = is_array($request->country_id) ? $request->country_id : (array)$request->country_id;

                $provinces = Province::query()->select($provinceCols)->whereIn('country_id', $country_id)->get()

                    ->groupBy(fn ($province) => Country::where('id', $province->country_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $provinces, 'total' => $provinces->count()]);

            }

            if ($request->has('province_id')) {

                $divisions = Division::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get()

                    ->groupBy(fn ($division) => Province::where('id', $division->province_id)->first()->name_english);

                $cities = City::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get()

                    ->groupBy(fn ($city) => Province::where('id', $city->province_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $divisions, 'total' => $divisions->count(), 'cities' => $cities, 'city_total' => $cities->count()]);

            }

            if ($request->has('division_id')) {

                $districts = District::query()->select($districtCols)->whereIn('division_id', $request->division_id)->get()

                    ->groupBy(fn ($district) => Division::where('id', $district->division_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $districts, 'total' => $districts->count()]);

            }

            if ($request->has('district_id')) {

                $tehsils = Tehsil::query()->select($tehsilCols)->whereIn('district_id', $request->district_id)->get()

                    ->groupBy(fn ($tehsil) => District::where('id', $tehsil->district_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $tehsils, 'total' => $tehsils->count()]);

            }

            if ($request->has('tehsil_id')) {

                $zones = Zone::query()->select($zoneCols)->whereIn('tehsil_id', $request->tehsil_id)->get()

                    ->groupBy(fn ($zone) => Tehsil::where('id', $zone->tehsil_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $zones, 'total' => $zones->count()]);

            }

            if ($request->has('zone_id')) {

                $councils = UnionCouncil::query()->select($unionCouncilCols)->whereIn('zone_id', $request->zone_id)->get()

                    ->groupBy(fn ($unionCouncil) => Zone::where('id', $unionCouncil->zone_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);

            }

        }

    }



    /**

     *get profession detail for Mobile APP according local set by Mobile App

    */

    public function indexApp(Request $request, $slug)

    {
        // Check if the 'lang' parameter is present in the request
        if ($request->has('lang')) {
            $locale = $request->input('lang');
            // Store the locale in the session
            session(['locale' => $locale]);
        } else {
            // Retrieve the locale from the session or fallback to the default locale
            $locale = session('locale', App::getLocale());
        }
        $request['lang'] = $locale;

        if(isset($request->lang)){
            App::setLocale($request->lang);
        }

        $query = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);



        $occupation = Occupation::select($query)->where('slug', $slug)->where('status', 1)->first();



        [$countries, $provinces, $divisions, $cities, $districts, $tehsils, $zones, $councils, $occupations] = $this->getFiltersData();



        if ($request->ajax()) {

            $occupationUsers = User::query()

                ->select('id', 'user_name', 'email')

                ->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id))

                ->get();



            $datatable = Datatables::of($occupationUsers);

            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->make(true);



            return $datatable;

        }

        return view('home.occupation-page', get_defined_vars());

    }



    /**

     *get profession detail for Mobile APP for unauthenticated users

    */

    public function indexHomeProfessionApp(Request $request, $slug)

    {

        $request['lang'] = isset($request->lang) ? $request->lang : 'urdu';

        App::setLocale($request->lang);



        $query = array_merge(getQuery($request->lang, ['title', 'content']), ['id']);



        $occupation = Occupation::select($query)->where('slug', $slug)->where('status', 1)->first();

        return view('home.home-occupation-page-app', get_defined_vars());

    }

}

