<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\EmployeeSection;
use App\Models\Admin\EmployeeSectionLibraryAlbum;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryType;
use App\Models\Admin\Section;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use DataTables;

class EmployeeSectionController extends Controller
{
    /**
     * listing the Employees.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Employee-Sections'))
            access_denied();

        $data = [];
        $data['libraryTypes'] = LibraryType::where('status', 1)->get();
        if ($request->ajax()) {
            // dd($request->section_id);

            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $section_id = (isset($_GET['section_id']) && $_GET['section_id']) ? $_GET['section_id'] : '';
            $db_record = EmployeeSection::orderBy('created_at', 'DESC')->get();
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
            $db_record = $db_record->when($section_id, function ($q, $section_id) {
                return $q->where('section_id', $section_id);
            });
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('section_id', function ($row) {
                return $row->section->name_english;
            });

            // $datatable->filterColumn('section_id', function ($query, $keyword) {
            //     $query->whereHas('section', function ($query) use ($keyword) {
            //         $query->where('id',$request->section_id);
            //     });
            // });

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

                if (have_right('Edit-Employee-Sections')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/employee-sections/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Employee-Sections')) {
                    $actions .= '<form method="POST" action="' . url("admin/employee-sections/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action', 'section_id']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.employee-section.listing', $data);
    }

    /**
     * creating the Employees.
    */
    public function create()
    {
        if (!have_right('Create-Employee-Sections'))
            access_denied();
        $data = [];
        $sections = Section::where('status', 1)->get();
        $data['sections'] = $sections;
        $data['row'] = new EmployeeSection();
        $data['album_type'] = LibraryType::where('status', 1)->get();
        $data['assigned_albums'] = [];
        $data['action'] = 'add';
        return View('admin.employee-section.form', $data);
    }

    /**
     * storing the Employees.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'section_id'    => 'required',
            'name_english' => 'required|string|max:255',
            'short_description_english' => 'required|string|max:100',
            'content_english' => 'required|string',
            // 'image'=> 'dimensions:max_width=400,max_height=400',
        ]);
        if ($validator->fails()) {
            $keysArray = array_keys($validator->messages()->toArray());
            $validatorMessageset = '<ul>';
            foreach ($keysArray as $val) {
                $validatorMessageset .= "<li>" . $validator->messages()->toArray()[$val][0] . "</li>";
            }
            $validatorMessageset .= "</ul>";
            return redirect()->back()->with('error', $validatorMessageset);
        }

        $employeeID = 0;
        if ($input['action'] == 'add') {
            if (!have_right('Create-Employee-Sections'))
                access_denied();

            if (isset($input['image'])) {
                $imagePath = uploadS3File($request , "images/products-images" ,"image","employeeSection",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
            }
            $input['admin_id'] = auth()->user()->id;
            // exit;
            $model = new EmployeeSection();
            $model->fill($input);
            $model->save();
            $employeeID = $model->id;
            $msg = 'Data added Successfully';
        } else {
            if (!have_right('Edit-Employee-Sections'))
                access_denied();
            $id = $input['id'];
            $model = EmployeeSection::find($id);
            // @for delete images

            if (isset($input['image'])) {

                deleteS3File($model->image);
                $imagePath = uploadS3File($request , "images/products-images" ,"image","employeeSection",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
                $image_url =  $model->image;
                if (!empty($image_url)) {
                    if (file_exists(public_path($image_url))) {
                        unlink($image_url);
                    }
                }
            } else {
                unset($input['image']);
            }
            // @for delete images
            $model->fill($input);
            $model->update();
            $employeeID = $input['id'];
            $msg = 'Data updated Successfully';
        }

        EmployeeSectionLibraryAlbum::where('employee_section_id', $employeeID)->delete();
        if (isset($input['albums']) && $employeeID && count($input['albums'])) {
            $albumArray = [];
            foreach ($input['albums'] as $key => $album) {
                $type = array_keys($album)[0];
                $albumArray['type_id'] = $type;
                $albumArray['employee_section_id'] = $employeeID;
                $albumArray['library_album_id'] = (int)$album[$type];

                EmployeeSectionLibraryAlbum::create($albumArray);
            }
        }
        return redirect('admin/employee-sections')->with('message', $msg);
    }

    /**
     * edit the Employees.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Employee-Sections'))
            access_denied();

        $data = [];
        $sections = Section::where('status', 1)->get();
        $data['sections'] = $sections;
        $data['id'] = $id;
        $data['album_type'] = LibraryType::where('status', 1)->get();
        $data['assigned_albums'] = EmployeeSectionLibraryAlbum::where('employee_section_id', $id)->pluck('library_album_id')->toArray();
        $data['row'] = EmployeeSection::find($id);
        $data['action'] = 'edit';
        return View('admin.employee-section.form', $data);
    }

    /**
     * delete the Employees.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Employee-Sections'))
            access_denied();
        $model = EmployeeSection::find($id);

        $image_url =  (!empty($model)) ? $model->image : '';
        // if (!empty($image_url) && file_exists(public_path($image_url))) {
        //     unlink($image_url);
        // }
        deleteS3File($image_url);
        $data = [];
        $data['row'] = EmployeeSection::destroy($id);
        EmployeeSectionLibraryAlbum::where('employee_section_id', $id)->delete();
        return redirect('admin/employee-sections')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading Employees images.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $path = ImageOptimize::improve($request->image, 'images/products-images');
        }
        return $path;
    }
}
