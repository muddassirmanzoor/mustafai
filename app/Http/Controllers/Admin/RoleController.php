<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Auth;
use DataTables;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * listing the ROles
    */
    public function index(Request $request)
    {
        if (!have_right('View-Roles-Management'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $search_word = (isset($_GET['search']) && $_GET['search']) ? $_GET['search'] : '';
            $db_record = Role::orderBy('order_rows', 'ASC');
            $db_record = $db_record->when($search_word, function ($q, $search_word) {
                return $q->where('name_english', 'LIKE', "%{$search_word}%");
            });
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('type', function ($row) {
                if ($row->type == 1) {
                    $type = '<span class="label label-success">Admin</span>';
                } else if ($row->type == 2) {
                    $type = '<span class="label label-success">User</span>';
                }
                return $type;
            })->filter(function ($instance) use ($request) {
                if (!empty($request->get('type'))) {
                    $instance->where(
                        function ($w) use ($request) {
                            $search = $request->get('type');
                            $w->orWhere('type', $search);
                            // ->orWhere('email', 'LIKE', "%$search%");
                        }
                    );
                }
            });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('typeColumn', function ($row) {
                if ($row->type == 1) {
                    $type = 'Admin';
                } else if ($row->type == 2) {
                    $type = 'User';
                }
                return $type;
            });
            $datatable = $datatable->editColumn('order_rows', function ($row) {
                if (have_right('Set-Order-Roles-Management')) {
                    $order = '<input data-role-id="' . $row->id . '"  id="order_set_' . $row->id . '" class="role_input form-control input-sm" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                    $order .= "<button data-role-id='" . $row->id . "'  class='btn btn-primary order-set-button' >Enter</button>";
                } else {
                    $order = '<input  readonly data-role-id="' . $row->id . '"  id="order_set_' . $row->id . '" class=" form-control input-sm" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                }
                return $order;
            });
            $datatable = $datatable->editColumn('subscription_charges', function ($row) {
                if (have_right('Set-Order-Roles-Management')) {
                    $subscription_charges = '<div class="set-charges"><input data-role-id="' . $row->id . '"  id="subscription_charge_' . $row->id . '" class="form-control input-sm" type="number" placeholder="Enter charges" value="' . $row->subscription_charges . '">';
                    $subscription_charges .= "<button data-role-id='" . $row->id . "'  class='btn btn-primary charges-set-button' >Update</button></div>";
                } else {
                    $subscription_charges = '<input  readonly data-role-id="' . $row->id . '"  id="subscription_charge_' . $row->id . '" class=" form-control input-sm" type="number" placeholder="Enter charges" value="' . $row->subscription_charges . '">';
                }
                return $subscription_charges;
            });

            $datatable = $datatable->addColumn('statusColumn', function ($row) {
                $status = 'Disable';
                if ($row->status == 1) {
                    $status = 'Active';
                }
                return $status;
            });

            // $datatable=$datatable->filter(function ($instance) use ($request) {
            //     if (!empty($request->get('type'))) {
            //             $instance->where(function ($w) use ($request) {
            //                 $search = $request->get('type');
            //                     $w->orWhere('type', $search);
            //                     // ->orWhere('email', 'LIKE', "%$search%");
            //             }
            //         );
            //     }
            // });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Roles-Management') && $row->id != 1 && auth()->user()->role_id != $row->id) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/roles/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Roles-Management') && $row->id != 1 && $row->id != 2 && $row->id != 19 && auth()->user()->role_id != $row->id) {
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/roles/" . $row->id) . '" accept-charset="UTF-8" style="display:inline">';
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

            $datatable = $datatable->rawColumns(['type', 'order_rows','subscription_charges', 'status', 'typeColumn', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.roles.listing', $data);
    }

    /**
     * creating the ROles
    */
    public function create()
    {
        if (!have_right('Create-Roles-Management'))
            access_denied();

        $data['row'] = new Role();
        $data['action'] = "Add";
        $data['type'] = $_GET['type'];
        return view('admin.roles.form')->with($data);
    }

    /**
     * storing the ROles
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $input['right_ids'] = $request->has('right_ids') ? implode(",", $request->right_ids) : NULL;

        if ($input['action'] == 'Add') {
            if (!have_right('Create-Roles-Management'))
                access_denied();

            $validator = Validator::make($request->all(), [
                'name_english' => ['required', 'string', 'max:100', Rule::unique('roles')]
            ]);

            $model = new Role();
            $flash_message = 'Role has been created successfully.';
        } else {
            if (!have_right('Edit-Roles-Management'))
                access_denied();

            $input['id'] = $input['id'];
            $validator = Validator::make($request->all(), [
                'name_english' => ['required', 'string', 'max:100', Rule::unique('roles')->ignore($input['id'])]
            ]);
            $model = Role::findOrFail($input['id']);
            $flash_message = 'Role has been updated successfully.';
        }

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages());
        }
        $model->fill($input);
        $model->save();
        if ($request->type == 1) {

            $adminRoles = Admin::where('role_id', $input['id'])->get();
            foreach ($adminRoles as $key => $val) {
                $details = [
                    'subject' =>  "Roles & Permissions Updated",
                    'user_name' =>  $val->first_name . " " . $val->last_name,
                    'content'  => "<p> " . $val->first_name . " Your roles and permissions are updated by the super-admin. Your Role Is " . $val->role->name_english . ".</p>",
                    'links'    =>  "<a href='" . url('admin/roles') . "'>Click Here To Login And See Permitions</a>",
                ];
                // sendEmail($val->email, $details);
                saveEmail($val->email, $details);
            }
            $details['user_name'] = "Admin";
            // sendEmail(Admin::first()->email, $details);

            $adminEmail = settingValue('emailForNotification');
            saveEmail($adminEmail, $details);
        } else {
            $cabinetRoles = CabinetUser::where('role_id', $input['id'])->get();

            foreach ($cabinetRoles as $key => $val) {
                $details = [
                    'subject' =>  "Roles & Permissions Assigned Successfully",
                    'user_name' =>  $val->user->user_name,
                    'content'  => "<p> " . $val->user->user_name . " You have been assigned certain roles and permissions as a cabinet member..</p>",
                    'links'    =>  "<a href='" . url('/login') . "'>Click here</a> to view the details. ",
                ];
                saveEmail($val->user->email, $details);
            }
        }
        if (auth()->user()->role_id == $model->id && $model->status == 0) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.auth.login')->with('error', 'Your role has been disabled.');
        }

        return redirect('admin/roles')->with('message', $flash_message);
    }

    /**
     * edit the ROles
    */
    public function edit($id)
    {
        if (!have_right('Edit-Roles-Management'))
            access_denied();


        if ($id == 1)
            access_denied();

        $data['action'] = "Edit";
        $data['id'] = $id;
        $id = $id;
        $data['row'] = Role::findOrFail($id);
        $data['type'] = $data['row']->type;
        return view('admin.roles.form')->with($data);
    }

    /**
     * removing the ROles
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Roles-Management'))
            access_denied();

        $id = $id;

        if ($id == 1)
            access_denied();

        $role = Role::find($id);
        if (count($role->admins) > 0) {
            return redirect('admin/roles')->with('message', 'You cannot delete this role because admin user(s) have already registered with this role.');
        }

        Role::destroy($id);
        return redirect('admin/roles')->with('message', 'Role has been deleted successfully.');
    }

    /**
     * update the Roles order
    */
    public function updateOrder(Request $request)
    {
        $role = Role::query()
            ->where('id', $request->id)
            ->update(['order_rows' => $request->order]);

        if ($role) {
            return response()->json(['status' => 1, 'data' => $role]);
        }

        return response()->json(['status' => 0]);
    }
    /**
     * update the Roles subscription charges
    */
    public function subscriptionCharge(Request $request)
    {
        $role = Role::query()
            ->where('id', $request->id)
            ->update(['subscription_charges' => $request->subscription_charges]);

        if ($role) {
            return response()->json(['status' => 1, 'data' => $role]);
        }

        return response()->json(['status' => 0]);
    }
}
