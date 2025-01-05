<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Event;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryAlbumDetails;
use App\Models\Admin\LibraryType;
use App\Models\Posts\Like\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use DataTables;
use Illuminate\Support\Facades\DB as FacadesDB;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Storage;

class LibraryController extends Controller
{
    /**
     * listing the libraries.
    */
    public function index(Request $request)
    {
        if (!have_right('view-Library'))
            access_denied();

        return redirect('/admin');
        $data = [];
        if ($request->ajax()) {
            $db_record = LibraryType::all();

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

                if (have_right('edit-library')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/library/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('delete-library')) {
                    $actions .= '<form method="POST" action="' . url("admin/library/" . $row->id) . '" accept-charset="UTF-8" style="display:none;">';
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

        return view('admin.library.listing', $data);
    }

    /**
     * creating the libraries.
    */
    public function create()
    {
        // if (!have_right('View-Library-Image') || !have_right('View-Library-Video') || !have_right('View-Library-Audio') || !have_right('View-Library-Book') || !have_right('View-Library-Document'))
        //     access_denied();
        $data = [];
        $data['row'] = new Library();
        $data['action'] = 'add';

        $libraryTypes = LibraryType::where('status', 1)->get();
        $data['libraryTypes'] = $libraryTypes;

        return View('admin.library.form', $data);
    }

    /**
     * Store Data Of Library Images Is not Included
    */
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($request);
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'content_english' => 'required',
            'content_urdu' => 'required',
            'content_arabic' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'edit') {
            unset($input['action']);
            // if (!have_right('edit-Library'))
            //     access_denied();
            if (isset($request->old_libraries_ids)) {

                $old_ids = $request->old_libraries_ids;
                $old_data_for_delete_file = DB::table('libraries')->whereNotIn('id', $old_ids)->where('type_id', $request->type_id)->get();

                foreach ($old_data_for_delete_file as $key => $val) {
                    $this->deleteEditoImage($val->file);
                }

                DB::table('libraries')->whereNotIn('id', $old_ids)->where('type_id', $request->type_id)->delete();
                //________________old_libraries_ids contain all ids for update library data_____//
                foreach ($request->old_libraries_ids as $key => $val) {
                    $model  = Library::find($val);
                    $model->type_id = $request->type_id;
                    $model->description = $request->description[$key];
                    $model->file_title = $request->file_title[$key];
                    $model->update();
                }
            }
            $model = LibraryType::find($request->type_id);
            $model->content_english = $request->content_english;
            $model->content_urdu    = $request->content_urdu;
            $model->content_arabic  = $request->content_arabic;
            $model->status          = $request->status;
            $model->update();
            return redirect('admin/library/' . $request->type_id . '/edit')->with('message', 'Data added Successfully');
        }
    }

    /**
     * edit the libraries.
    */
    public function edit($id)
    {
        $libraryType = LibraryType::where('id', $id)->first();
        $data = [];
        $data['id'] = $id;
        $data['row'] = LibraryAlbum::where('type_id', $id)->where('id', '>', 0)->whereNull('parent_id')->get();
        $data['action'] = 'edit';
        $data['previous_parent'] = NULL;
        $data['libraryType'] = $libraryType;

        return View('admin.library.form', $data);
    }

    /**
     * uploading files for the libraries.
    */
    public function uploadFile(Request $request)
    {
        $fileName = 'library' . time() . '.' . $request->file->extension();
        if ($request->file->move(public_path('images/lib_images_thumb'), $fileName)) {
            $path =  'images/lib_images_thumb/' . $fileName;
            return $path;
        }
    }

    /**
     * removing image of editor in the libraries.
    */
    public function deleteEditoImage($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }

    /**
     * save library files.
    */
    public function saveFilesAjax(Request $request)
    {
        // dd(request());
        if (isset($request->videUrl)) {
            $dataalbumname = LibraryAlbumDetails::where('library_album_id', request()->libraryAlbumId)->max('id');
            $model = new LibraryAlbumDetails();
            $albumId = request()->libraryAlbumId;
            $filePath = $request->videUrl;
            $model->file =  $filePath;
            $model->library_album_id =  $albumId;
            $model->title_english =  'New File' . $dataalbumname;
            $model->title_urdu =  'New File' . $dataalbumname;
            $model->title_arabic =  'New File' . $dataalbumname;
            $model->type_video = 1;
            $model->status =  1;
            $model->save();
            $html = $this->openDirData($model);
            echo json_encode($html);
            exit;
        }
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            echo "file is not uploaded";
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $fileOrgName = $file->getClientOriginalName();
            $fileOrgNameWithoutExtension = pathinfo($fileOrgName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = "lib" . mt_rand(0, 2000) . md5(time()) . '.' . $extension; // a unique file name
            $pathLib=$file->storeAs(
                'storage/lib-files',
                $fileName,
                's3'
            );
            // $disk = Storage::disk(config('filesystems.default'));
            // $disk->putFileAs('public/lib-files', $file, $fileName);
            // $pathLib = 'storage/lib-files/' . $fileName;
            $dataalbumname = LibraryAlbumDetails::where('library_album_id', request()->albumId)->max('id');
            $model = new LibraryAlbumDetails();
            $albumId = request()->albumId;
            $filePath = $pathLib;
            $model->file =  $filePath;
            $model->library_album_id =  $albumId;
            $model->title_english =  $fileOrgNameWithoutExtension.'-'.$dataalbumname;
            $model->title_urdu =  $fileOrgNameWithoutExtension.'-'.$dataalbumname;
            $model->title_arabic =  $fileOrgNameWithoutExtension.'-'.$dataalbumname;
            $model->status =  1;
            $model->save();

            // delete chunked file
            unlink($file->getPathname());

            $html = $this->openDirData($model);
            echo json_encode($html);
            exit;
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    /**
     * update thumb image of the libraries.
    */
    public function updateThumbImg(Request $request)
    {
        $id = $request->id;
        $model = Library::find($id);
        $filePath = $this->uploadFile($request);
        $model->img_thumb_nail = $filePath;
        $model->update();
    }

    /**
     * creating directory for the libraries.
    */
    public function createDirectory(Request $request)
    {
        $input                  = $request->input();
        $dataalbumname          = LibraryAlbum::where('type_id', $input['type_id'])->max('id');
        $library                = new LibraryAlbum();
        $input['title_english'] = "New Album" . $dataalbumname;
        $input['title_urdu'] = "New Album" . $dataalbumname;
        $input['title_arabic'] = "New Album" . $dataalbumname;
        if ($input['parent_id'] == "NULL") {
            $input['parent_id'] = NULL;
        } else {
            $input['parent_id'] = $request->parent_id;
        }
        unset($input['directory_type']);
        $library->fill($input);
        $library->save();
        $data['type']           = $input['type_id'];
        $data['row']            = LibraryAlbum::where('id', $library->id)->first();
        $html                   = (string) View('admin.partial.directory', $data);
        echo json_encode($html);
    }

    /**
     * directoy details of the libraries.
    */
    public function dirDetails(Request $request)
    {
        $input = $request->input();
        if ($request->isMethod('get')) {
            $input = $request->input();
            $data['action'] = 'edit';
            $data['id'] = $input['id'];
            $data['row'] =  ($input['directory_type'] == 'files') ? $data['row'] = LibraryAlbumDetails::where('id', $input['id'])->first() :  $data['row'] = LibraryAlbum::where('id', $input['id'])->first();
            $html        = (string) View('admin.modals.dir-details', $data);
            echo json_encode($html);
        } else {


            $id = $input['id'];
            if ($input['files'] == 'files') {
                $model = LibraryAlbumDetails::find($id);
                $type_id = $model->libraryAlbum->type_id;
            } else {

                $model = LibraryAlbum::find($id);
                $type_id = $model->type_id;
            }
            // @for delete images
            if ($request->img_thumb_nail) {
                // dd($request->img_thumb_nail);
                    // $imagePath = $this->uploadImage($request);
                    // $input['img_thumb_nail'] = $imagePath;
                    // $image_url =  $model->img_thumb_nail;
                        deleteS3File($model->img_thumb_nail);
                        $imagePath = uploadS3File($request , "images/lib-icons" ,"img_thumb_nail","library",$filename = null);
                        $input['img_thumb_nail'] = $imagePath;
                    
                }
             else {
                unset($input['img_thumb_nail']);
            }
            unset($input['action']);
            unset($input['files']);
            $model->fill($input);
            $model->update();
            return redirect('admin/library/' . $type_id . '/edit')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * open directory of the libraries.
    */
    public function openDir(Request $request)
    {

        $data = [];
        $dataParent = LibraryAlbum::where('id', $request->data_parent_id)->first();
        $data['previous_parent'] = $dataParent->parent_id;
        $libraryType = LibraryType::where('id', $request->data_libType)->first();
        $data['id'] = $request->data_libType;
        $data['row'] = LibraryAlbum::where('type_id', $request->data_libType)->where('id', '>', 0)->where('parent_id', $request->data_parent_id)->get();
        $data['parentRow'] = LibraryAlbum::where('type_id', $request->data_libType)->where('id', $request->data_parent_id)->first();
        $data['action'] = 'edit';
        $data['parent_id'] = $request->data_parent_id;
        // $data['libraryAlbumDetails'] = LibraryAlbumDetails::where('library_album_id', $request->data_parent_id)->limit(21)->get();
        $data['libraryAlbumDetails'] = LibraryAlbumDetails::where('library_album_id', $request->data_parent_id)->paginate(21);
        $data['libType'] = $request->data_libType;
        $data['libraryType'] = $libraryType;
        if (!empty($request->data_back)) {
            $data['dataBack'] = $request->data_back;
        } else {
            $data['dataBack'] = '';
        }

        //new data for bread crums
        // dd($request->data_parent_id);
        // $dataParentRow = LibraryAlbum::where('id', $request->data_parent_id)->first();

        // LibraryAlbum::where('parent_id',">",0)->where('id',"<",)

        // if (!empty($dataParentRow->parent_id)) {



        //     $data['bread_crums'] = LibraryAlbum::where('parent_id', '<=', $dataParentRow->parent_id)->where('type_id', $dataParentRow->type_id)->get();
        // } else {
        //     $data['bread_crums'] =  NULL;
        // }
        if($request->load_more){
            $html  = (string) View('admin.partial.load-more-files', $data);
        }
        else{
            $html  = (string) View('admin.partial.open-dir-details', $data);
        }
        $arr['html'] = $html;

        $arr['load_more']=$data['libraryAlbumDetails']->count();
        //set session

        $arr['breadcrums'] = 'bread';
        echo json_encode($arr);
    }

    /**
     * upload icon image for the libraries.
    */
    public function uploadImage(Request $request)
    {
        // dd($request->icon->extension());
        $path = '';
        if ($request->img_thumb_nail) {
            $imageName = 'lib-icons' . time() . '.' . $request->img_thumb_nail->extension();
            if ($request->img_thumb_nail->move(public_path('images/lib-icons'), $imageName)) {
                $path =  'images/lib-icons/' . $imageName;
            }
        }
        return $path;
    }

    /**
     * removing directory from the libraries.
    */
    public function removeDir(Request $request)
    {
        $input = $request->input();
        if ($input['dirtype'] == 'files') {
            // $path = LibraryAlbumDetails::find($input['id'])->file;
            // dd($path);
            // delefile($path);
            $file=LibraryAlbumDetails::find($input['id']);
            deleteS3File($file->file);
            LibraryAlbumDetails::find($input['id'])->delete();
        } else {
            LibraryAlbum::find($input['id'])->delete();
        }
        echo 1;
        exit;
    }

    /**
     * getting parent albums of the library.
    */
    public function getParentsAlbums(Request $request)
    {
        $lastParentId = $request->data_parent_id;
        $libAlbum = LibraryAlbum::where('parent_id', $lastParentId)->first();
        // dd($libAlbum->parents());
    }

    /**
     * view playlist for album data in library.
    */
    public function viewPlaylist(Request $request)
    {
        $data['libAlbumData'] = LibraryAlbumDetails::where('id', $request->clickid)->first();
        $data['libAlbumDetails'] = LibraryAlbumDetails::where('library_album_id', $data['libAlbumData']->library_album_id)->get();
        $html  = (string) View('admin.partial.playlist-viewer', $data);
        echo json_encode($html);
    }

    /**
     * open directory data in the libraries.
    */
    function openDirData($model)
    {
        // dd($model);
        $libraryType = LibraryType::where('id', $model->libraryAlbum->type_id)->first();
        $data = [];
        $data['id'] = $model->libraryAlbum->type_id;
        $data['row'] = LibraryAlbum::where('type_id', $model->libraryAlbum->type_id)->where('id', '>', 0)->where('parent_id', $model->libraryAlbum->id)->get();
        $data['action'] = 'edit';
        $data['parent_id'] =  $model->libraryAlbum->id;
        $data['libraryAlbumDetails'] = LibraryAlbumDetails::where('library_album_id', $model->libraryAlbum->id)->paginate(21);
        $data['libType'] = $model->libraryAlbum->type_id;
        $data['libraryType'] = $libraryType;
        $data['previous_parent'] = '';
        $data['parentRow'] = '';
        return  (string) View('admin.partial.open-dir-details', $data);
    }
}
