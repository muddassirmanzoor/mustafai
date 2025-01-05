<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Headline;
use App\Models\City;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HeadlinesController extends Controller
{
    /**
     * listing Headlines.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Headlines'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Headline::where('id', '>', 0)->orderBy('headline_order')->get();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });

            $datatable = $datatable->editColumn('order', function ($row) {

                if (have_right('Set-Order-Headlines')) {

                    $order = '<input data-headline-id="' . $row->id . '"  min="0" id="order_set_' . $row->id . '" class="headline_input form-control input-sm" type="number" placeholder="Enter order" value="' . $row->headline_order . '">';
                    $order .= "<button data-headline-id='" . $row->id . "'  class='btn btn-primary order-set-button' >Enter</button>";
                } else {
                    $order = '<input readonly data-headline-id="' . $row->id . '"  min="0" id="order_set_' . $row->id . '" class=" form-control input-sm" type="number" placeholder="Enter order" value="' . $row->headline_order . '">';
                }
                return $order;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Headlines')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/headlines/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Headlines')) {
                    $actions .= '<form id="deleteHeadlineForm" method="POST" action="' . url("admin/headlines/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button type="button" class="btn btn-danger" style="margin-left:02px;" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'order', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.headlines.listing', $data);
    }

    /**
     * creating the Headlines.
    */
    public function create()
    {
        if (!have_right('Create-Headlines'))
            access_denied();

        $data = [];
        $data['row'] = new Headline();
        $data['action'] = 'add';

        $data['cities'] = City::query()->where('status', 1)->get();

        return View('admin.headlines.form', $data);
    }

    /**
     * storing Headlines.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        unset($input['files']);

        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string|max:200',
            'content_english' => 'required',
            'reporting_city' => 'required',
            'reporter_name' => 'required',
            'reporting_date_time' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Headlines'))
                access_denied();

            $model = new Headline();
            $model->fill($input);
            $model->save();

            return redirect('admin/headlines')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Headlines'))
                access_denied();

            unset($input['action']);
            unset($input['files']);
            $id = $input['id'];
            $model = Headline::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/headlines')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Headlines.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Headlines'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Headline::find($id);
        $data['action'] = 'edit';

        $data['cities'] = City::query()->where('status', 1)->get();

        return View('admin.headlines.form', $data);
    }

    /**
     * removing Headlines.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Headlines'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = Headline::destroy($id);
        return redirect('admin/headlines')->with('message', 'Data deleted Successfully');
    }

    /**
     * updating orders in Headlines.
    */
    public function updateOrder(Request $request)
    {
        $headline = Headline::query()
            ->where('id', $request->id)
            ->update(['headline_order' => $request->order]);

        if ($headline) {
            return response()->json(['status' => 1, 'data' => $headline]);
        }

        return response()->json(['status' => 0]);
    }
}
