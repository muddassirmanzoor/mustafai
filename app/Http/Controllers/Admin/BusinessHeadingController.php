<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BusinessHeading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class BusinessHeadingController extends Controller
{
    /**
     * listing the business headings.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Business-Heading'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $db_record = BusinessHeading::orderBy('created_at', 'DESC')->get();
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

                if (have_right('Edit-Business-Heading')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/business-heading/" . encodeDecode($row->id) . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }
                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.business-heading.listing', $data);
    }

    public function create()
    {
        //
    }

    /**
     * storing the business headings.
    */
    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string|max:255',
            'content_english' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages())->withInput();
        }

        if ($input['action'] == 'add') {
            // if (!have_right('Create-Ceo-Message'))
            //     access_denied();

            // if (isset($input['image'])) {
            //     $imagePath = $this->uploadimage($request);
            //     $input['image'] = $imagePath;
            // }
            // $input['admin_id'] = auth()->user()->id;
            // $model = new CeoMessage();
            // $model->fill($input);
            // $model->save();
            // return redirect('admin/ceomessage')->with('message', 'Data added Successfully');
        } else {

            if (!have_right('Edit-Business-Heading'))
                access_denied();
            // dd("ok");
            $id = $input['id'];
            $model = BusinessHeading::find(encodeDecode($id));
            unset($input['action']);
            $model->fill($input);
            $model->update();
            return redirect('admin/business-heading')->with('message', 'Data updated Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    /**
     * edit the business headings.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Business-Heading'))
            access_denied();
        $data = [];
        $data['id'] = $id;
        $data['row'] = BusinessHeading::find(encodeDecode($id));
        $data['action'] = 'edit';
        return View('admin.business-heading.form', $data);
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
