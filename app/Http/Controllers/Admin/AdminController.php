<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Admin\Admin;
use App\Models\Admin\CountryCode;
use App\Models\Admin\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Hash;
use Auth;
use DB;
use DataTables;
use Hashids\Hashids;
use Session;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    /**
     *listing admins
    */
    public function index(Request $request)
    {
        deleteS3File('images/profile/profile_1691403850.jpg');
        if (!have_right('View-Admin'))
            access_denied();

        $data = [];
        $data['emails'] = Admin::whereNotIn('id', [1, auth()->user()->id])->pluck('email');
        $data['roles'] = Role::where('id', '>', 0)->get();
        if ($request->ajax()) {
            // DB::enableQueryLog();
            $db_record = Admin::whereNotIn('id', [1, auth()->user()->id]);

            if (!empty($request->statusColumn)) {
                // $statuCoulmn = (int) $request->statusColumn;
                $statuCoulmn = $request->statusColumn == 'active' ? 1 : 0;
                $db_record->where('status', $statuCoulmn);
            }
            if (!empty($request->email)) {
                $db_record->where('email', 'like', '%' . $request->email . '%');
            }
            if (!empty($request->role)) {
                $role_id = (int) $request->role;

                $db_record->where('role_id', $role_id);
            }
            $db_record = $db_record->orderBy('created_at', 'DESC')->get();
            // dd($db_record);
            // dd(DB::getQueryLog());
            $datatable = Datatables::of($db_record);


            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->addColumn('role', function ($row) {
                return (!empty($row->role)) ? $row->role->name_english : 'Not Found';
            });
            $datatable = $datatable->addColumn('name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            });
            $datatable = $datatable->editColumn('status', function ($row) {

                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                } else {
                    $status = '<span class="badge badge-danger">Disable</span>';
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

                if (have_right('Edit-Admin')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/admins/" . hashEncode($row->id) . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Admin')) {
                    $actions .= '<form method="POST" action="' . url("admin/admins/" . hashEncode($row->id)) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['name', 'statusColumn', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.admins.listing', $data);
    }

    /**
     *Creating admins
    */
    public function create()
    {
        if (!have_right('Create-Admin'))
            access_denied();

        $data = [];
        $data['roles'] = Role::where(['status' => 1, 'type' => 1])->where('id', '!=', 1)->orderBy('order_rows', 'ASC')->get();
        $data['row'] = new Admin();
        $data['action'] = 'add';
        $data['country_codes'] = CountryCode::orderBy(DB::raw('id = 160'), 'DESC')->get();
        return View('admin.admins.form', $data);
    }

    /**
     *edit admins
    */
    public function edit($id)
    {
        if (!have_right('Edit-Admin'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = hashDecode($id);
        $id = $id;
        $data['roles'] = Role::where(['status' => 1, 'type' => 1])->where('id', '!=', 1)->orderBy('order_rows', 'ASC')->get();
        $data['row'] = Admin::find($id);
        $data['action'] = 'edit';
        $data['country_codes'] = CountryCode::orderBy(DB::raw('id = 160'), 'DESC')->get();
        return View('admin.admins.form', $data);
    }

    /**
     *storing admins
    */
    public function store(Request $request)
    {
        $input = $request->all();
        if (isset($input['password']) || isset($input['origional_password'])) {
            $input['password'] = Hash::make($input['password']);
            $input['origional_password'] = $input['repeat_password'];
        } else {
            unset($input['password'], $input['origional_password']);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'phone' => 'required|string|max:100',
            'password' => 'required|string|min:8|max:30',
        ]);

        if ($validator->fails()) {
            // Session::flash('flash_danger', $validator->messages());
            $keysArray = array_keys($validator->messages()->toArray());
            $string = '<ul>';
            foreach ($keysArray as $val) {
                $string .= "<li>" . $validator->messages()->toArray()[$val][0] . "</li>";
            }
            $string .= "</ul>";
            return redirect()->back()->with('error', $string)->withInput();
        }

        if ($input['action'] == 'add') {
            if (!have_right('Create-Admin'))
                access_denied();

            $model = new Admin();
            $model->fill($input);
            if ($model->save()) {
                $userEmail = $model->email;
                $roleName = Role::find($model->role_id)->value('name_english');
                $details = [
                    'subject' =>  "Admin User Created Successfully",
                    'user_name' =>   $model->first_name,
                    'content'  => "<p>You are registered successfully as a " . $roleName . " on Mustafai Portal by the super admin .</p><p>Your credentials are as follows:</p>
                    <bold><p> Email:" . $model->email . "</p><p>Password:" . $model->origional_password . "</p></bold>",
                    'links'    =>  "<a href='" . url('/admin') . "'>Click here </a> to log in to Mustafai Portal.",
                ];
                // sendEmail($userEmail, $details);
                saveEmail($userEmail, $details);
            }
            return redirect('admin/admins')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Admin'))
                access_denied();

            $id = $input['id'];
            $id = hashDecode($id);
            // $id = $id;
            $model = Admin::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/admins')->with('message', 'Data updated Successfully');
        }
    }

    /**
     *deleting admins
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Admin'))
            access_denied();

        $data = [];
        $id = hashDecode($id);
        // $adminEmail = Admin::find($id)->value("email");
        $data['row'] = Admin::destroy($id);
        $details = [
            'subject' =>  "Sub-admin Deleted",
            'user_name' =>  "Sub-admin",
            'content'  => "<p>Unfortunately, your account has been suspended by the super-admin. You will not be able to log in as a sub-admin anymore.</p>",
            'links'    =>  "",
        ];
        // sendEmail($adminEmail, $details);
        $adminEmail = settingValue('emailForNotification');
        saveEmail($adminEmail, $details);
        return redirect('admin/admins')->with('message', 'Data deleted Successfully');
    }

    /**
     *Admin Update Profile
    */
    public function profile()
    {
        if (!have_right('View-Admin-Profile'))
            access_denied();

        if (\Request::isMethod('post')) {
            if (!have_right('Update-Admin-Profile'))
                access_denied();

            unset($_POST['_token']);
            $data = $_POST;
            // dd($data);
            if (isset($data['password']) && !empty($data['password'])) {
                $hashedPassword = Auth::user()->password;
                if (!\Hash::check($data['old_password'], $hashedPassword)) {
                    return redirect()->back()->withInput()->with('error', 'Old Password Is Not Correct');
                } else {
                    unset($data['old_password']);
                    unset($data['confirm_password']);
                    $data['origional_password'] = $data['password'];
                    $data['password'] = Hash::make($data['password']);
                }
            } else {
                unset($data['password']);
                unset($data['old_password']);
                unset($data['confirm_password']);
            }
            $id = auth()->user()->id;
            $admin = Admin::find($id);
            $admin->fill($data);
            $admin->save();
            return redirect()->back()->withInput()->with('message', 'Save Record Successfully');
        }
        $user = auth()->user();
        return View('admin.admins.profile', $user);
    }

    /**
     *Admin Update Profile picture
    */
    public function profilePic(Request $request)
    {
        $input = $request->all();
        $id = Auth::user()->id;
        $model = Admin::find($id);
        if (isset($input['image'])) {
            // $imagePath = $this->uploadImage($request);
            deleteS3File($model->profile);
            $imagePath = uploadS3File($request , "images/profile" ,"image","profile",$filename = null);
            $input['profile'] = $imagePath;
        }
        unset($input['image']);
        $model->fill($input);
        if ($model->update()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * upload the image .
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'profile' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/profile'), $imageName)) {
                $path =  'images/profile/' . $imageName;
            }
        }
        return $path;
    }
}
