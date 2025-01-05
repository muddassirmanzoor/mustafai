<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use DataTables;

class SectionController extends Controller
{
    /**
     * listing the Sections
    */
    public function index(Request $request)
    {
        if (!have_right('View-Our-Team-Section'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $db_record = Section::orderBy('created_at', 'DESC')->get();
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
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Our-team-Section')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/sections/" . encodeDecode($row->id) . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.main-sections.listing', $data);
    }

    public function create()
    {
        //
    }

    /**
     * storing the Sections
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->with('error', $validator->messages());
        }
        if ($input['action'] == 'add') {
        } else {
            if (!have_right('Edit-Our-Team-Section'))
                access_denied();
            $id = $input['id'];
            $model = Section::find(encodeDecode($id));
            unset($input['id']);
            $model->fill($input);
            $model->update();
            $msg = 'Data updated Successfully';
        }
        return redirect('admin/sections')->with('message', $msg);
    }

    public function show($id)
    {
        //
    }

    /**
     * edit the Sections
    */
    public function edit($id)
    {
        if (!have_right('Edit-Our-Team-Section'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Section::find(encodeDecode($id));
        $data['action'] = 'edit';
        return View('admin.main-sections.form', $data);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
