<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\HeaderSetting;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\Validator;
use Session;
use DataTables;
use App\Models\Admin\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    /**
     * listing the Occupation.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Occupations'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Occupation::orderBy('id', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Occupations')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/occupations/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Occupations')) {
                    $actions .= '<form method="POST" action="' . url("admin/occupations/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.occupation.listing', $data);
    }

    /**
     * creating the Occupation.
    */
    public function create()
    {
        if (!have_right('Create-Occupations'))
            access_denied();

        $data = [];
        $data['row'] = new Occupation();
        $data['action'] = 'add';
        $data['occupations'] = Occupation::where('parent_id', '=', null)->get();
        return View('admin.occupation.form', $data);
    }

    /**
     * storing the Occupation.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string|max:200',
            'status' => 'required',
        ]);
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $input['title_english']));

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Occupations'))
                access_denied();

            $model = new Occupation();
            unset($input['files']);
            $model->fill($input);
            $model->save();

            return redirect('admin/occupations')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Occupations'))
                access_denied();
            unset($input['action']);
            unset($input['files']);
            // dd($input);
            $id = $input['id'];
            $model = Occupation::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/occupations')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Occupation.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Occupations'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Occupation::find($id);
        $data['action'] = 'edit';
        $data['occupations'] = Occupation::where('parent_id', '=', null)->where('id', '!=', $id)->get();
        return View('admin.occupation.form', $data);
    }

    /**
     * removing the Occupation.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Occupations'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = Occupation::destroy($id);
        $data['child'] = Occupation::where('parent_id', $id)->delete();
        return redirect('admin/occupations')->with('message', 'Data deleted Successfully');
    }
}
