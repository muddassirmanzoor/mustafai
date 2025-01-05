<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cabinet;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Role;
use App\Models\Admin\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Models\Admin\Country;
use App\Models\Admin\City;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\Zone;
use App\Models\Admin\UnionCouncil;

class CabinetController extends Controller
{
    /**
     * listing the cabinet.
    */
    public function index(Request $request)
    {
        // dd($request->ipinfo->all);
        if (!have_right('View-Cabinets'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $db_record = Cabinet::orderBy('created_at', 'DESC')->get();
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            // $datatable = $datatable->editColumn('users', function ($row) {
            //     $users = '<a href="javascript:void(0)" ><span class="badge badge-success list_users" data-cabinet-id="' . $row->id . '" onclick="loadUsers(this)">show users</span></a>';
            //     return $users;
            // });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('statusColumn', function ($row) {
                if ($row->status == 1) {
                    $status = 'Active';
                } else {
                    $status = 'Disable';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Cabinets')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/cabinets/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Cabinets')) {
                    $actions .= '<form method="POST" action="' . url("admin/cabinets/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['users', 'status', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.cabinet.listing', $data);
    }

    /**
     * creating the cabinet.
    */
    public function create()
    {
        if (!have_right('Create-Cabinets'))
            access_denied();

        $data = [];
        $data['row'] = new Cabinet();
        $data['action'] = 'add';

        $data['cabinets'] = Cabinet::where('parent_id', '=', null)->get();
        $data['all_cabinets'] = Cabinet::pluck('name_english', 'id')->all();
        $data['users'] = User::all();
        $data['designations'] = Designation::where('type', 2)->get();
        $data['countries'] = Country::select('id', 'name_english as name')->where('status', 1)->get();
        $data['provinces'] = Province::select('id', 'name_english as name')->where('status', 1)->get();
        $data['divisions'] = Division::select('id', 'name_english as name')->where('status', 1)->get();
        $data['districts'] = District::select('id', 'name_english as name')->where('status', 1)->get();
        $data['tehsils'] = Tehsil::select('id', 'name_english as name')->where('status', 1)->get();
        $data['zones']  = Zone::select('id', 'name_english as name')->where('status', 1)->get();
        $data['cities'] = City::select('id', 'name_english as name')->where('status', 1)->get();
        $data['union_councils'] =  UnionCouncil::select('id', 'name_english as name')->where('status', 1)->get();
        $data['is_edit'] = false;
        return View('admin.cabinet.form', $data);
    }
    public function showProfile()
    {
        $user = auth()->user();

        // Fetch related address details (assuming these fields are stored in the users table or a related model)
        $address = [
            'country' => Country::where('id', $user->country_id)->pluck('name_english')->first(),
            'province' => Province::where('id', $user->province_id)->pluck('name_english')->first(),
            'division' => Division::where('id', $user->division_id)->pluck('name_english')->first(),
            'district' => District::where('id', $user->district_id)->pluck('name_english')->first(),
            'tehsil' => Tehsil::where('id', $user->tehsil_id)->pluck('name_english')->first(),
            'zone' => Zone::where('id', $user->zone_id)->pluck('name_english')->first(),
            'city' => City::where('id', $user->city_id)->pluck('name_english')->first(),
            'union_council' => UnionCouncil::where('id', $user->union_council_id)->pluck('name_english')->first(),
        ];

        return view('user.profile', compact('user', 'address'));
    }


    /**
     * storing the cabinet.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $users = $input['users'];
        $designations = $input['designations'];
        unset($input['users'], $input['designations']);

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Cabinets'))
                access_denied();

            $model = new Cabinet();
            $model->fill($input);
            $model->save();

            $id = $model->id;
            $msg = 'Data added Successfully';
        } else {
            if (!have_right('Edit-Cabinets'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = Cabinet::find($id);
            $model->fill($input);
            $model->update();
            $msg = 'Data updated Successfully';
        }

        CabinetUser::where('cabinet_id', $id)->delete();
        $userData = [];
        $userIds = [];

        foreach ($users as $key => $user) {
            if (in_array((int)$user, $userIds)) {
                continue;
            }
            $userIds[] = (int)$user;
            $userData[$key]['user_id'] = (int)$user;
            $userData[$key]['designation_id'] = (int)$designations[$key];
            $userData[$key]['status'] = 1;
            $details = [
                'subject' =>  'Cabinet Member Assigned Successfully',
                'user_name' =>  User::find($user)->user_name,
                'content'  => "<p>You are now a cabinet member of the cabinet " .  $model->name_english . "  .</p>",
                'links'    =>  "<a href='" . url('/') . "'>Click Here </a>To Mustafai Portral",
            ];
            /*** Update Cabinet Member Logged In Role*/
            if ((int)($designations[$key])){
                User::where('id', $user)->update(['login_role_id' => (int)$designations[$key]]);
            } else {
                User::where('id', $user)->update(['login_role_id' => (int)$roles[$key]]);
            }
            // sendEmail(User::find($user)->email, $details);
            saveEmail(User::find($user)->email, $details);
        }
        $model->cabinetUsers()->createMany($userData);

        return redirect('admin/cabinets')->with('message', $msg);
    }

    /**
     * edit the cabinet.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Cabinets'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Cabinet::find($id);
        $data['action'] = 'edit';
        $data['cabinets'] = Cabinet::where('parent_id', '=', null)->where('id', '<>', $id)->get();
        $data['all_cabinets'] = Cabinet::pluck('name_english', 'id')->all();
        $data['cabinet_users_ids'] = CabinetUser::where('cabinet_id', $id)->get()->toArray();
        $data['designations'] = Designation::where('type', 2)->get();
        $data['countries'] = Country::select('id', 'name_english as name')->where('status', 1)->get();
        $data['provinces'] = Province::select('id', 'name_english as name')->where('status', 1)->get();
        $data['divisions'] = Division::select('id', 'name_english as name')->where('status', 1)->get();
        $data['districts'] = District::select('id', 'name_english as name')->where('status', 1)->get();
        $data['tehsils'] = Tehsil::select('id', 'name_english as name')->where('status', 1)->get();
        $data['zones']  = Zone::select('id', 'name_english as name')->where('status', 1)->get();
        $data['cities'] = City::select('id', 'name_english as name')->where('status', 1)->get();
        $data['union_councils'] =  UnionCouncil::select('id', 'name_english as name')->where('status', 1)->get();
        $data['is_edit'] = true;
        $data['users'] = User::all();

        return View('admin.cabinet.form', $data);
    }

    /**
     * removing the cabinet.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Cabinets'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = Cabinet::destroy($id);
        CabinetUser::where('cabinet_id', $id)->delete();
        return redirect('admin/cabinets')->with('message', 'Data deleted Successfully');
    }

    /**
     * show the cabinet users.
    */
    public function cabinetUsers(Request $request)
    {
        //        $cabinet = Cabinet::find($request->id)->with('cabinetUsers')->get();
        $cabinet = CabinetUser::where('cabinet_id', $request->id)->get();
        $userDesignations = Designation::where('type', 2)->get();
        $users = User::all();
        $cabinetId = $request->id;
        $cabinet_users_ids = CabinetUser::where('cabinet_id', $request->id)->pluck('user_id')->toArray();
        $html = view('admin.partial.cabinet-users', compact('cabinet', 'users', 'cabinet_users_ids', 'cabinetId', 'userDesignations'))->render();

        return response()->json(['status' => 200, 'data' => $html]);
    }

    /**
     * updating the cabinet.
    */
    public function updateCabinetUsers(Request $request)
    {
        $input = $request->all();
        // dd($input);
        if (!empty($input['designation_id'])) {

            foreach ($request->user_id as $key => $user) {
                $user = User::find($user);
                $user->update([
                    'designation_id' =>  $input['designation_id'][$key],
                ]);
            }
        }
        CabinetUser::where('cabinet_id', $request->cabinet_id)->delete();
        $userData = [];
        foreach ($request->users as $key => $user) {
            $userData[$key]['user_id'] = (int)$user;
            $userData[$key]['status'] = 1;
            $userData[$key]['cabinet_id'] = $request->cabinet_id;
        }

        $model = Cabinet::find($request->cabinet_id);
        $model->cabinetUsers()->createMany($userData);

        return redirect()->back()->with('message', 'Members updated successfully!');
    }

    /**
     * calling address function.
    */
    public function addressFunction(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('country_id')) {
                // dd("ok");
                $provinces = Province::select('id', 'name_english as name')->where('country_id', $request->country_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $provinces, 'total' => $provinces->count()]);
            }
            if ($request->has('province_id')) {
                $divisions = Division::select('id', 'name_english as name')->where('province_id', $request->province_id)->where('status', 1)->get();
                $cities = City::select('id', 'name_english as name')->where('province_id', $request->province_id)->where('status', 1)->get();

                return response()->json(['status' => 200, 'data' => $divisions, 'total' => $divisions->count(), 'cities' => $cities, 'city_total' => $cities->count()]);
            }
            if ($request->has('division_id')) {
                $districts = District::select('id', 'name_english as name')->where('division_id', $request->division_id)->where('status', 1)->get();

                return response()->json(['status' => 200, 'data' => $districts, 'total' => $districts->count()]);
            }
            if ($request->has('district_id')) {
                $tehsils = Tehsil::select('id', 'name_english as name')->where('district_id', $request->district_id)->where('status', 1)->get();

                return response()->json(['status' => 200, 'data' => $tehsils, 'total' => $tehsils->count()]);
            }
            if ($request->has('tehsil_id')) {
                $zones = Zone::select('id', 'name_english as name')->where('tehsil_id', $request->tehsil_id)->where('status', 1)->get();

                return response()->json(['status' => 200, 'data' => $zones, 'total' => $zones->count()]);
            }
            if ($request->has('zone_id')) {
                $councils = UnionCouncil::select('id', 'name_english as name')->where('zone_id', $request->zone_id)->where('status', 1)->get();

                return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);
            }
        }
    }

    /**
     * selected users of the cabinet.
    */
    public function cabientUsersSelect(Request $request)
    {
        if ($request->ajax()) {
            $users = User::when($request->country_id, fn ($q) => $q->where('country_id', $request->country_id)->where('country_id', '!=', null))
                ->when($request->province_id, fn ($q) => $q->where('province_id', $request->province_id)->where('province_id', '!=', null))
                ->when($request->division_id, fn ($q) => $q->where('division_id', $request->division_id)->where('division_id', '!=', null))
                ->when($request->district_id, fn ($q) => $q->where('district_id', $request->district_id)->where('district_id', '!=', null))
                ->when($request->city_id, fn ($q) => $q->where('city_id', $request->city_id)->where('city_id', '!=', null))
                ->when($request->tehsil_id, fn ($q) => $q->where('tehsil_id', $request->tehsil_id)->where('tehsil_id', '!=', null))
                ->when($request->zone_id, fn ($q) => $q->where('zone_id', $request->zone_id)->where('zone_id', '!=', null))
                ->when($request->union_council_id, fn ($q) => $q->where('union_council_id', $request->union_council_id)->where('union_council_id', '!=', null))
                ->where('status', 1)
                ->get();
            $designations = Designation::where('type', 2)->get();
            if (!empty($users) && count($users)) {
                $html = view('admin.partial.cabinet-users-select', get_defined_vars())->render();
                return response()->json(['html' => $html, 'status' => 200]);
            } else {
                return response()->json(['message' => 'No user Found for that Address', 'status' => 201]);
            }
        }
    }
}
