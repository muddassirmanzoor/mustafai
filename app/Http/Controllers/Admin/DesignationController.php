<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Auth;
use DataTables;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    /**
     * Listing the designations
     */
    public function index(Request $request)
    {
        if (!have_right('View-Designations-Management')) {
            access_denied();
        }

        $data = [];
        if ($request->ajax()) {
            $search_word = (isset($_GET['search']) && $_GET['search']) ? $_GET['search'] : '';
            $db_record = Designation::orderBy('order_rows', 'ASC');
            $db_record = $db_record->when($search_word, function($q, $search_word) {
                return $q->where('name_english', 'LIKE', "%{$search_word}%");
            });

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('type', function($row) {
                if ($row->type == 1) {
                    $type = '<span class="label label-success">Admin</span>';
                } elseif ($row->type == 2) {
                    $type = '<span class="label label-success">User</span>';
                }
                return $type;
            })->filter(function($instance) use ($request) {
                if (!empty($request->get('type'))) {
                    $instance->where(function($w) use ($request) {
                        $search = $request->get('type');
                        $w->orWhere('type', $search);
                    });
                }
            });

            $datatable = $datatable->editColumn('status', function($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('typeColumn', function($row) {
                if ($row->type == 1) {
                    $type = 'Admin';
                } elseif ($row->type == 2) {
                    $type = 'User';
                }
                return $type;
            });

            $datatable = $datatable->editColumn('order_rows', function($row) {
                if (have_right('Set-Order-Designations-Management')) {
                    $order = '<input data-designation-id="' . $row->id . '" id="order_set_' . $row->id . '" class="form-control input-sm designation_input" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                    $order .= "<button data-designation-id='" . $row->id . "' class='btn btn-primary order-set-button'>Enter</button>";
                } else {
                    $order = '<input readonly data-designation-id="' . $row->id . '" id="order_set_' . $row->id . '" class="form-control input-sm" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                }
                return $order;
            });

            $datatable = $datatable->editColumn('subscription_charges', function($row) {
                if (have_right('Set-Order-Designations-Management')) {
                    $subscription_charges = '<div class="set-charges"><input data-designation-id="' . $row->id . '" id="subscription_charge_' . $row->id . '" class="form-control input-sm" type="number" placeholder="Enter charges" value="' . $row->subscription_charges . '">';
                    $subscription_charges .= "<button data-designation-id='" . $row->id . "' class='btn btn-primary charges-set-button'>Update</button></div>";
                } else {
                    $subscription_charges = '<input readonly data-designation-id="' . $row->id . '" id="subscription_charge_' . $row->id . '" class="form-control input-sm" type="number" placeholder="Enter charges" value="' . $row->subscription_charges . '">';
                }
                return $subscription_charges;
            });

            $datatable = $datatable->addColumn('statusColumn', function($row) {
                $status = 'Disable';
                if ($row->status == 1) {
                    $status = 'Active';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('action', function($row) {
                $actions = '<span class="actions">';
                if (have_right('Edit-Designations-Management') && $row->id != 1 && auth()->user()->designation_id != $row->id) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/designations/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }
                if (have_right('Delete-Designations-Management') && $row->id != 1 && $row->id != 2 && $row->id != 19 && auth()->user()->designation_id != $row->id) {
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/designations/" . $row->id) . '" accept-charset="UTF-8" style="display:inline">';
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

            $datatable = $datatable->rawColumns(['type', 'order_rows', 'subscription_charges', 'status', 'typeColumn', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.designations.listing', $data);
    }

    /**
     * Creating the designation
     */
    public function create()
    {
        if (!have_right('Create-Designations-Management')) {
            access_denied();
        }

        $data['row'] = new Designation();
        $data['action'] = "Add";
        $data['type'] = $_GET['type'];
        return view('admin.designations.form')->with($data);
    }

    /**
     * Storing the designations
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['right_ids'] = $request->has('right_ids') ? implode(",", $request->right_ids) : NULL;

        if ($input['action'] == 'Add') {
            if (!have_right('Create-Designations-Management')) {
                access_denied();
            }
            $validator = Validator::make($request->all(), [
                'name_english' => ['required', 'string', 'max:100', Rule::unique('designations')]
            ]);

            $model = new Designation();
            $flash_message = 'Designation has been created successfully.';
        } else {
            if (!have_right('Edit-Designations-Management')) {
                access_denied();
            }
            $input['id'] = $input['id'];
            $validator = Validator::make($request->all(), [
                'name_english' => ['required', 'string', 'max:100', Rule::unique('designations')->ignore($input['id'])]
            ]);

            $model = Designation::findOrFail($input['id']);
            $flash_message = 'Designation has been updated successfully.';
        }

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages());
        }

        $model->fill($input);
        $model->save();

        if ($request->type == 1) {
            $adminDesignations = Admin::where('designation_id', $input['id'])->get();
            foreach ($adminDesignations as $key => $val) {
                $details = [
                    'subject' => "Designations & Permissions Updated",
                    'user_name' => $val->first_name . " " . $val->last_name,
                    'content' => "<p>" . $val->first_name . ", your designations and permissions are updated by the super-admin. Your Designation is " . $val->designation->name_english . ".</p>",
                    'links' => "<a href='" . url('admin/designations') . "'>Click Here To Login And See Permissions</a>",
                ];
                saveEmail($val->email, $details);
            }
        } else {
            $cabinetDesignations = CabinetUser::where('designation_id', $input['id'])->get();
            foreach ($cabinetDesignations as $key => $val) {
                $details = [
                    'subject' => "Designations & Permissions Assigned Successfully",
                    'user_name' => $val->user->user_name,
                    'content' => "<p>" . $val->user->user_name . ", you have been assigned certain designations and permissions as a cabinet member.</p>",
                    'links' => "<a href='" . url('/login') . "'>Click here</a> to view the details.",
                ];
                saveEmail($val->user->email, $details);
            }
        }

        if (auth()->user()->designation_id == $model->id && $model->status == 0) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.auth.login')->with('error', 'Your designation has been disabled.');
        }

        return redirect('admin/designations')->with('message', $flash_message);
    }

    /**
     * Edit the designation
     */
    public function edit($id)
    {
        if (!have_right('Edit-Designations-Management')) {
            access_denied();
        }
        if ($id == 1) {
            access_denied();
        }

        $data['action'] = "Edit";
        $data['id'] = $id;
        $id = $id;
        $data['row'] = Designation::findOrFail($id);
        $data['type'] = $data['row']->type;
        return view('admin.designations.form')->with($data);
    }

    /**
     * Remove the designation
     */
    public function destroy($id)
    {
        if (!have_right('Delete-Designations-Management')) {
            access_denied();
        }
        $id = $id;
        if ($id == 1) {
            access_denied();
        }

        $designation = Designation::find($id);
        if (count($designation->admins) > 0) {
            return redirect('admin/designations')->with('message', 'You cannot delete this designation because admin user(s) have already registered with this designation.');
        }

        Designation::destroy($id);
        return redirect('admin/designations')->with('message', 'Designation has been deleted successfully.');
    }

    /**
     * Update the designation order
     */
    public function updateOrder(Request $request)
    {
        $designation = Designation::query()->where('id', $request->id)->update(['order_rows' => $request->order]);
        if ($designation) {
            return response()->json(['status' => 1, 'data' => $designation]);
        }
        return response()->json(['status' => 0]);
    }

    /**
     * Update the designation subscription charges
     */
    public function subscriptionCharge(Request $request)
    {
        $designation = Designation::query()->where('id', $request->id)->update(['subscription_charges' => $request->subscription_charges]);
        if ($designation) {
            return response()->json(['status' => 1, 'data' => $designation]);
        }
        return response()->json(['status' => 0]);
    }
}
