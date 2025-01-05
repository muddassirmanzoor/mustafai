<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\MagazineCategory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MagazineCategoryController extends Controller
{
    /**
     * listing the Magzine Category.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Magazines-Category'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = MagazineCategory::all();

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

                if (have_right('Edit-Magazines-Category')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/magazine-categories/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Magazines-Category')) {
                    $actions .= '<form method="POST" action="' . url("admin/magazine-categories/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

        return view('admin.magazine-categories.listing', $data);
    }

    /**
     * creating the Magzine Category.
    */
    public function create()
    {
        if (!have_right('Create-Magazines-Category'))
            access_denied();

        $data = [];
        $data['row'] = new MagazineCategory();
        $data['action'] = 'add';
        return View('admin.magazine-categories.form', $data);
    }

    /**
     * storing the Magzine Category.
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Magazines-Category'))
                access_denied();

            $model = new MagazineCategory();
            $model->fill($input);
            $model->save();

            return redirect('admin/magazine-categories')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Magazines-Category'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = MagazineCategory::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/magazine-categories')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Magzine Category.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Magazines-Category'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = MagazineCategory::find($id);
        $data['action'] = 'edit';
        return View('admin.magazine-categories.form', $data);
    }

    /**
     * removing the Magzine Category.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Magazines-Category'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = MagazineCategory::destroy($id);
        return redirect('admin/magazine-categories')->with('message', 'Data deleted Successfully');
    }
}
