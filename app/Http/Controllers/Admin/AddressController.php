<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\Zone;
use App\Models\Admin\City;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AddressController extends Controller
{
    public function __construct()
    {
        $table_model = request()->segment(2);
        switch ($table_model) {
            case "countries":
                return new Country;
                break;
            case "provinces":
                return new Province;
                break;
            case "divisions":
                return new Division;
                break;
            case "districts":
                return new District;
                break;
            case "tehsils":
                return new Tehsil;
                break;
            case "cities":
                return new City;
                break;
            case "branches":
                return new Zone;
                break;
            case "union-councils":
                return new UnionCouncil;
                break;
            default:
                return false;
        }
    }

    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {

        if (!have_right('View-Address'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $dbclass = $this->__construct();
            $db_record = $dbclass->orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
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

            $columnArr = [];
            if (request()->segment(2) != 'union-councils') {
                array_push($columnArr, 'parent_name');
                $datatable = $datatable->addColumn('parent_name', function ($row) {
                    return   optional($row->parent)->name_english;
                });
            } else {

                $datatable = $datatable->addColumn('tehsil', function ($row) {
                    return   !empty($row->tehsil) ? $row->tehsil->name_english : 'N/A';
                });
                $datatable = $datatable->addColumn('branch', function ($row) {
                    return   !empty($row->zone) ? $row->zone->name_english : 'N/A';
                });
                array_push($columnArr, 'tehsil', 'branch');
            }

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Address')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/" . request()->segment(2) . "/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Address')) {
                    $actions .= '<form method="POST" action="' . url("admin/" . request()->segment(2) . "/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button  class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $columsArray = implode(",", $columnArr);
            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'parent_name', 'action', $columsArray]);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.address.listing', $data);
    }

    /**
     * Show the form for creating a new resource.
    */
    public function create(Request $request)
    {
        if (!have_right('Create-Address'))
            access_denied();
        $data = [];
        $data['row'] =  $this->__construct();
        $data['action'] = 'add';
        $data['dynamic_data'] = $this->getDynamicData($request->segment(2));
        return View('admin.address.form', $data);
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        // dd($request->url());
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
        // try {
        if ($input['action'] == 'add') {
            if (!have_right('Create-Address'))
                access_denied();

            $model = $this->__construct();
            $exist = $model->where('name_english', $input['name_english'])->orWhere('name_urdu', $input['name_urdu'])->count();
            if ($exist > 0) {
                return redirect('admin/' . request()->segment(2) . '')->with('error', 'Record Already Existed!!');
            } else {
                $model->fill($input);
                $model->save();
                return redirect('admin/' . request()->segment(2) . '')->with('message', 'Data added Successfully');
            }
        } else {
            if (!have_right('Edit-Address'))
                access_denied();

            $id = $input['id'];
            // dd($request);
            // @get model with find second parament is for find
            $model = $this->__construct();
            $exist = $model
                ->where(function ($query) use ($input) {
                    $query->where('name_english', $input['name_english'])
                        ->orWhere('name_urdu', $input['name_urdu']);
                })
                ->where('id', '!=', $id)
                ->count();
            if ($exist > 0) {
                return redirect('admin/' . request()->segment(2) . '')->with('error', 'Record Already Existed!!');
            } else {
                $model = $model->find($id);
                $model->fill($input);
                $model->update();
                return redirect('admin/' . request()->segment(2) . '')->with('message', 'Data updated Successfully');
            }
        }
        // } catch (\Exception $e) {
        //     return redirect('admin/' . request()->segment(2) . '')->with('error', request()->segment(2) . 'is Already Exist');
        // }
    }

    /**
     * Show the form for editing the specified resource.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Address'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $dbclass = $this->__construct();
        $data['row'] = $dbclass->find($id);
        $data['action'] = 'edit';
        $data['dynamic_data'] = $this->getDynamicData(request()->segment(2), $data['row']);
        return View('admin.address.form', $data);
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Address'))
            access_denied();

        $data = [];
        $dbclass = $this->__construct();
        $record  = $dbclass->find($id);
        $data['row'] = $record->delete();
        return redirect('admin/' . request()->segment(2) . '')->with('message', 'Data deleted Successfully');
    }

    /**
     * this function is return dynamic string of html.
    */
    public function getDynamicData($segment, $data = null)
    {
        switch ($segment) {
            case "countries":
                if (empty($data)) {
                    return '<div class="col-sm-4"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-4"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-4  d-none"><div class="form-group"><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                } else {
                    return '<div class="col-sm-4"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-4"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-4 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                }
                break;
            case "provinces":
                if (empty($data)) {

                    $country_data = Country::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($country_data as $country) {
                        $option .= '<option value="' . $country->id . '" >' . $country->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Country <span class="text-red">*</span></label><select class="form-control" name="country_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $country_data = Country::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';

                    foreach ($country_data as $country) {
                        if ($data->country_id ==  $country->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $country->id . '" ' . $selected . ' >' . $country->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Country <span class="text-red">*</span></label><select class="form-control" name="country_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;
            case "divisions":
                if (empty($data)) {

                    $province_data = Province::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($province_data as $province) {
                        $option .= '<option value="' . $province->id . '" >' . $province->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Province <span class="text-red">*</span></label><select class="form-control" name="province_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $province_data = Province::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';

                    foreach ($province_data as $province) {
                        if ($data->province_id ==  $province->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $province->id . '" ' . $selected . ' >' . $province->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Province *</label><select class="form-control" name="province_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;
            case "districts":
                if (empty($data)) {

                    $division_data = Division::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($division_data as $division) {
                        $option .= '<option value="' . $division->id . '" >' . $division->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Division <span class="text-red">*</span></label><select class="form-control" name="division_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) *</label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $division_data = Division::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';

                    foreach ($division_data as $division) {
                        if ($data->division_id ==  $division->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $division->id . '" ' . $selected . ' >' . $division->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select division <span class="text-red">*</span></label><select class="form-control" name="division_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) *</label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;
            case "tehsils":
                if (empty($data)) {

                    $district_data = District::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($district_data as $district) {
                        $option .= '<option value="' . $district->id . '" >' . $district->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select district *</label><select class="form-control" name="district_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $district_data = District::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';

                    foreach ($district_data as $district) {
                        if ($data->district_id ==  $district->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $district->id . '" ' . $selected . ' >' . $district->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select district <span class="text-red">*</span></label><select class="form-control" name="district_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;

            case "cities":
                if (empty($data)) {

                    $province_data = Province::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($province_data as $province) {
                        $option .= '<option value="' . $province->id . '" >' . $province->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Province <span class="text-red">*</span></label><select class="form-control" name="province_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $province_data = Province::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';

                    foreach ($province_data as $province) {
                        if ($data->province_id ==  $province->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $province->id . '" ' . $selected . ' >' . $province->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select Province *</label><select class="form-control" name="province_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;
            case "branches":
                if (empty($data)) {

                    $tehsil_data = Tehsil::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($tehsil_data as $tehsil) {
                        $option .= '<option value="' . $tehsil->id . '" >' . $tehsil->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select tehsil <span class="text-red">*</span></label><select class="form-control" name="tehsil_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div>';
                    return $data_dynamic;
                } else {
                    $tehsil_data = Tehsil::where('status', 1)->get();
                    $option = '<option value="">--Select Dropdown--</option>';
                    foreach ($tehsil_data as $tehsil) {
                        if ($data->tehsil_id ==  $tehsil->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $tehsil->id . '" ' . $selected . ' >' . $tehsil->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-3"><label class="form-label">Select tehsil *</label><select class="form-control" name="tehsil_id" required>' . $option . '</select></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-3"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-3 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div>';
                    return $data_dynamic;
                }
                break;
            case "union-councils":
                if (empty($data)) {

                    $tehsil_data = Tehsil::where('status', 1)->get();
                    $option = '<option  value="">--Select Dropdown--</option>';
                    foreach ($tehsil_data as $tehsil) {
                        $option .= '<option value="' . $tehsil->id . '" >' . $tehsil->name_english . '</option>';
                    }
                    $zone_data = Zone::where('status', 1)->get();
                    $option_zone = '<option value="">--Select Dropdown--</option>';
                    foreach ($zone_data as $zone) {
                        $option_zone .= '<option value="' . $zone->id . '" >' . $zone->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-6"><label class="form-label">Select Type <span class="text-red">*</span></label><select class="form-control" id="link_type" onchange="selectLinkType($(this))" required><option>--select Type--</option><option value="tehsil_div">Tehsil</option><option value="zone_div">Zone</option></select></div><div class="col-sm-6 dynamic_div" id="tehsil_div" style="display: none;"><label class="form-label">Select Tehsil <span class="text-red">*</span></label><select class="form-control" name="tehsil_id" required>' . $option . '</select></div><div class="col-sm-6 dynamic_div" id="zone_div" style="display: none;"><label class="form-label">Select Zone <span class="text-red">*</span></label><select class="form-control" name="zone_id" required>' . $option_zone . '</select></div></div><div class="row clearfix mt-5"><div class="col-sm-4"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required=""></div></div><div class="col-sm-4"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required=""></div></div><div class="col-sm-4 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required=""></div></div></div>';
                    return $data_dynamic;
                } else {
                    if (empty($data->tehsil_id)) {
                        $tehsil_div = 'display: none !important; ';
                        $zone_div = 'display: block !important;';
                    } else {
                        $tehsil_div = 'display: block !important;';
                        $zone_div = 'display: none   !important;';
                    }
                    $tehsil_data = Tehsil::where('status', 1)->get();
                    $option = '<option value="" >--Select Dropdown--</option>';
                    foreach ($tehsil_data as $tehsil) {
                        if ($data->tehsil_id ==  $tehsil->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="' . $tehsil->id . '" ' . $selected . ' >' . $tehsil->name_english . '</option>';
                    }
                    $zone_data = Zone::where('status', 1)->get();
                    $option_zone = '<option value="" >--Select Dropdown--</option>';
                    foreach ($zone_data as $zone) {
                        if ($data->zone_id ==  $zone->id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $option_zone .= '<option value="' . $zone->id . '" ' . $selected . ' >' . $zone->name_english . '</option>';
                    }
                    $data_dynamic = '<div class="col-sm-6 "><label class="form-label">Select Type <span class="text-red">*</span></label><select class="form-control" id="link_type" onchange="selectLinkType($(this))" required><option value="">--select Type--</option><option value="tehsil_div" ' . (!empty($data->tehsil_id) ? 'selected ' : ' ') . '>Tehsil</option><option value="zone_div" ' . (!empty($data->zone_id) ? 'selected ' : ' ') . '>Zone</option></select></div><div class="col-sm-6 dynamic_div " id="tehsil_div" style="' . $tehsil_div . '"><label class="form-label">Select Tehsil <span class="text-red">*</span></label><select class="form-control" name="tehsil_id">' . $option . '</select></div><div class="col-sm-6 dynamic_div " id="zone_div" style="' . $zone_div . '"><label class="form-label">Select Zone <span class="text-red">*</span></label><select class="form-control" name="zone_id">' . $option_zone . '</select></div></div><div class="row clearfix mt-5"> <div class="col-sm-4"><div class="form-group "><label class="form-label">Name (English) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="' . $data->name_english . '" required=""></div></div><div class="col-sm-4"><div class="form-group "><label class="form-label">Name (Urdu) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="' . $data->name_urdu . '" required=""></div></div><div class="col-sm-4 d-none"><div class="form-group "><label class="form-label">Name (Arabic) <span class="text-red">*</span></label><input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="' . $data->name_arabic . '" required=""></div></div></div>';
                    return $data_dynamic;
                }
                break;
            default:
                echo false;
        }
    }

    /**
     *getting adress options
    */
    public function getAddressOptions($data = null)
    {

        return  '<div class="col-sm-4">
                <div class="form-group ">
                <label class="form-label">Name (English) <span class="text-red">*</span>
                </label>
                <input type="text" class="form-control" placeholder="Enter Name In English" name="name_english" value="" required="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                <label class="form-label">Name (Urdu) <span class="text-red">*</span>
                </label>
                <input type="text" class="form-control" placeholder="Enter Name In Urdu" name="name_urdu" value="" required="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                <label class="form-label">Name (Arabic) <span class="text-red">*</span>
                </label>
                <input type="text" class="form-control" placeholder="Enter Name In Arabic" name="name_arabic" value="" required="">
                </div>
            </div>';
    }
}
