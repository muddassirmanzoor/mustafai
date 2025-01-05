<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CeoMessage;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use DataTables;


class CeoMessageController extends Controller
{
    /**
     * listing CEO messages.
    */
    public function index(Request $request)
    {
        // dd("test");
        // dd($request->ipinfo->all);
        if (!have_right('View-Ceo-Message'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $db_record = CeoMessage::orderBy('created_at', 'DESC')->get();
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

                if (have_right('Edit-Ceo-Message')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/ceomessage/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                // if (have_right('Delete-Ceo-Message')) {
                //     $actions .= '<form method="POST" action="' . url("admin/ceomessage/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                //     $actions .= '<input type="hidden" name="_method" value="DELETE">';
                //     $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                //     $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                //     $actions .= '<i class="far fa-trash-alt"></i>';
                //     $actions .= '</button>';
                //     $actions .= '</form>';
                // }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.ceo-message.listing', $data);
    }

    /**
     * creating CEO messages.
    */
    public function create()
    {
        if (!have_right('Create-Ceo-Message'))
            access_denied();
        abort(404);

        $data = [];
        $data['row'] = new CeoMessage;
        $data['action'] = 'add';
        return View('admin.ceo-message.form', $data);
    }

    /**
     * storing CEO messages.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'message_title' => 'required|string|max:255',
            'message_urdu' => 'required|string',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
        $url_image = array();


        if ($input['action'] == 'add') {
            if (!have_right('Create-Ceo-Message'))
                access_denied();

            if (isset($input['image'])) {
                $imagePath = uploadS3File($request , "images/ceo-message" ,"image","ceoMessage",$filename = null);
                $input['image'] = $imagePath;
            }
            $input['admin_id'] = auth()->user()->id;
            $model = new CeoMessage();
            $model->fill($input);
            $model->save();
            return redirect('admin/ceomessage')->with('message', 'Data added Successfully');
        } else {

            if (!have_right('Edit-Ceo-Message'))
                access_denied();

            $id = $input['id'];
            $model = CeoMessage::find($id);
            // @for delete images
            if (isset($input['image'])) {
                deleteS3File($model->image);
                $imagePath = uploadS3File($request , "images/ceo-message" ,"image","ceoMessage",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
                $image_url =  $model->image;
                if (file_exists(public_path($image_url))) {
                    unlink($image_url);
                }
            } else {
                unset($input['image']);
            }
            $model->fill($input);
            $model->update();
            return redirect('admin/ceomessage')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit CEO messages.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Ceo-Message'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        // $id = Hashids::decode($id)[0];
        $data['row'] = CeoMessage::find($id);
        $data['action'] = 'edit';
        return View('admin.ceo-message.form', $data);
    }

    /**
     * destroy/remove CEO messages.
    */
    public function destroy($id)
    {

        if (!have_right('Delete-Ceo-Message'))
            access_denied();

        abort(404);
        $model = CeoMessage::find($id);
        $image_url =  $model->image;
        // if (file_exists(public_path($image_url))) {
        //     unlink($image_url);
        // }
        deleteS3File($model->image);
        $data = [];
        $data['row'] = CeoMessage::destroy($id);
        return redirect('admin/ceomessage')->with('message', 'Data deleted Successfully');
    }

    /**
     * CEO messages including picture.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'ceo-message' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/ceo-message'), $imageName)) {
                $path =  'images/ceo-message/' . $imageName;
            }
        }
        return $path;
    }
}
