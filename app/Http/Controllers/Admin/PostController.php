<?php



namespace App\Http\Controllers\Admin;



use App\Helper\ImageOptimize;

use App\Http\Controllers\Admin\Traits\PostTrait;

use App\Http\Controllers\Controller;

use App\Models\Admin\Donor;

use App\Models\Admin\Notification;

use App\Models\City;

use App\Models\Posts\Post\Post;

use App\Models\Posts\PostFile\PostFile;

use App\Models\User;

use App\Services\FirebaseNotificationService;

use Carbon\Carbon;

use Google\Service\ShoppingContent\Resource\Pos;

use Illuminate\Http\Request;

use Auth;

use DB;

use DataTables;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;



class PostController extends Controller

{

    protected $firebaseNotification;



    public function __construct(FirebaseNotificationService $firebaseNotification)

    {

        $this->firebaseNotification = $firebaseNotification;

    }

    use PostTrait;



    /**

     * listing the Posts

     */

    public function index(Request $request)

    {

        if (!have_right('View-Posts'))

            access_denied();



        $data = [];

        if ($request->ajax()) {

            $post_type = (isset($_GET['post_type']) && $_GET['post_type']) ? $_GET['post_type'] : '';

            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';

            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';

            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';

            $db_record = Post::latest();

            $db_record = $db_record->when($post_type, function ($q, $post_type) {

                return $q->where('post_type', $post_type);

            });

            $db_record = $db_record->when($status, function ($q, $status) {

                $status = $status == 'active' ? 1 : 0;

                return $q->where('status', $status);

            });

            $db_record = $db_record->when($from_date, function ($q, $from_date) {

                $startDate = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';

                return $q->where('created_at', '>=', $startDate);

            });

            $db_record = $db_record->when($to_date, function ($q, $to_date) {

                $endDate = date('Y-m-d', strtotime($to_date)) . ' 23:59:00';

                return $q->where("created_at", '<=', $endDate);

                // return $q->whereRaw("(date(created_at) <='" . $endDate . "')");

            });



            $datatable = Datatables::of($db_record);

            $datatable = $datatable->addIndexColumn();



            $datatable = $datatable->editColumn('status', function ($row) {

                $status = '<span class="badge badge-danger">Disable</span>';

                if ($row->status == 1) {

                    $status = '<span class="badge badge-success">Active</span>';

                }

                return $status;

            });



            $datatable = $datatable->editColumn('post_type', function ($row) {

                if ($row->post_type == 1) $post_type = '<span class="badge badge-success">Simple Post</span>';

                if ($row->post_type == 2) $post_type = '<span class="badge badge-success">Job Post</span>';

                if ($row->post_type == 3) $post_type = '<span class="badge badge-success">Announcement Post</span>';

                if ($row->post_type == 4) $post_type = '<span class="badge badge-success">Product Post</span>';

                if ($row->post_type == 5) $post_type = '<span class="badge badge-success">Blood Post</span>';

                return $post_type;

            });

            $datatable = $datatable->editColumn('statusHidden', function ($row) {

                $status = 'Disable';

                if ($row->status == 1) {

                    $status = 'Active';

                }

                return $status;

            });



            $datatable = $datatable->editColumn('post_typeHidden', function ($row) {

                if ($row->post_type == 1) $post_type = 'Simple Post';

                if ($row->post_type == 2) $post_type = 'Job Post';

                if ($row->post_type == 3) $post_type = 'Announcement Post';

                if ($row->post_type == 4) $post_type = 'Product Post';

                if ($row->post_type == 5) $post_type = 'Blood Post';

                return $post_type;

            });



            $datatable = $datatable->editColumn('title_english', function ($row) {

                return Str::of($row->title_english)->limit(150, '<a data-toggle="modal" data-target="#showPostModal" class="show_post" href="javascript:void(0)" data-post-id="' . $row->id . '"> Read more...</a>');

            });



            $datatable = $datatable->addColumn('user_name', function ($row) {

                $user_id = !empty($row->share_id) ? $row->share_id : $row->user_id;

                $user = User::find($user_id);

                if (!empty($user)) {

                    return '<a href="' . url("admin/users/" . $user->id . '/edit') . '" target="_blank"> ' . $user->full_name . ' </a>';

                } else {

                    return 'N/A';

                }

            });



            $datatable = $datatable->addColumn('action', function ($row) {

                $actions = '<span class="actions">';



                if (have_right('Edit-Posts')) {

                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/posts/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';

                }



                if (have_right('Show-Detail-Posts')) {

                    $actions .= '&nbsp;<a data-toggle="modal" data-target="#showPostModal" class="btn btn-primary show_post" href="javascript:void(0)" data-post-id="' . $row->id . '" title="Show"><i class="far fa-eye"></i></a>';

                }



                if (have_right('Delete-Posts')) {

                    $actions .= '<form method="POST" action="' . url("admin/posts/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';

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



            $datatable = $datatable->rawColumns(['status', 'post_type', 'statusHidden', 'post_typeHidden', 'action', 'title_english', 'user_name']);

            $datatable = $datatable->make(true);

            return $datatable;

        }



        return view('admin.posts.listing', $data);

    }



    /**

     * edit the Posts

     */

    public function edit($id)

    {

        if (!have_right('Edit-Posts')) access_denied();



        $toFindPost = Post::where('id', $id)->exists();



        if (!$toFindPost) {

            return redirect()->back()->withInput()->with('error', 'This record has been deleted');

            // return abort(403,'The resource you want to access is deleted');



        }



        $data = [];

        $data['id'] = $id;

        $data['row'] = Post::with('citi')->where('id', $id)->first();

        $data['files'] = $data['row']->images;

        $data['action'] = 'edit';

        $data['post_type'] = $data['row']->post_type;

        $data['cities'] = City::where('status', 1)->get();



        return View('admin.posts.form', $data);

    }



    /**

     * creating the Posts

     */

    public function create()

    {

        if (!have_right('Create-Posts'))

            access_denied();



        $data = [];

        $data['cities'] = City::where('status', 1)->get();

        $data['row'] = new Post();

        $data['action'] = 'add';



        return view('admin.posts.form', $data);

    }



    /**

     * storing the Posts

     */

    public function store(Request $request)

    {

        if ($request->post_type == 2) { // 2 = job post

            $this->doJobPost($request);

            return redirect('admin/posts')->with('message', 'Post updated successfully');

        }

        if ($request->post_type == 5) { // 5 = blood post

            $this->doBloodPost($request);

            return redirect('admin/posts')->with('message', 'Post updated successfully');

        }



        $validatedData = $this->validateRequest($request);



        $authUserId = auth()->user()->id;



        if ($request->action == 'add') {

            if (!have_right('Create-Posts')) access_denied();

            $post = Post::create(array_merge(Arr::except($validatedData, ['files']), ['admin_id' => $authUserId]));

            if (!empty($validatedData['files'])) {

                $files = $this->uploadFiles($validatedData['files'], $post);

                /*foreach ($files as $Key => $value) {

                    PostFile::create([

                        'file' => $value,

                        'post_id' => $post->id

                    ]);

                }*/

            }



            // notification to all users

            $user_ids = User::pluck('id')->toArray();

            $notification = Notification::create([

                'title' => 'Mustafai has created post',

                'title_english' => 'Mustafai has created post',

                'title_urdu' => 'مصطفائی نے پوسٹ بنائی ہے',

                'title_arabic' => 'أنشأ مصطفائی آخر',

                'module_id' => 36,

                'right_id' => 136,

                'ip' => request()->ip()

            ]);



            $notification->users()->attach($user_ids, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin





            return redirect('admin/posts')->with('message', 'Post added Successfully');

        } else {

            if (!have_right('Edit-Posts')) access_denied();





            unset($request->action);

            $id = $request->id;

            $post = Post::find($id);

            $post->update([

                'post_type' => $request->post_type,

                'title_english' => $request->title_english,

                'title_urdu' => isset($request->title_urdu) ? $request->title_urdu : '',

                'title_arabic' => isset($request->title_arabic) ? $request->title_arabic : '',

                'status' => $request->status,

            ]);



            $updatedPost = Post::find($id);



            if ($request->status == 1 && $post->user_id != null) {

                // send notification



                $notification = Notification::create([

                    'title' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',

                    'title_english' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',

                    'title_urdu' => 'اپ لوڈ لوڈ کر دی گئی ہے ' . Str::words($updatedPost->title_urdu, 3, '...'),

                    'title_arabic' =>  'تم تحميله ' . Str::words($updatedPost->title_arabic, 3, '...'),

                    'link' => route('user.specific-post', hashEncode($post->id)),

                    'module_id' => 36,

                    'right_id' => 135,

                    'ip' => request()->ip()

                ]);



                $notification->users()->attach($updatedPost->user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]);

            }



            if ($request->has('old_files')) {

                $postImagesIds = $post->images->map(fn($img) => $img->id)->toArray();

                $diffArray = array_diff($postImagesIds, $request->old_files);

                if ($diffArray) {

                    $postFiles = PostFile::whereIn('id', $diffArray)->get();

                    foreach ($postFiles as $postFile) {

                        $s3FilePath = $postFile->file; // Replace this with the actual column name storing the S3 file path

                        // Delete the file from S3

                        Storage::disk('s3')->delete($s3FilePath);

                    }

                    PostFile::whereIn('id', $diffArray)->delete();

                }

            }



            if ($request->hasfile('files')) {

                foreach ($request->file('files') as $file) {

                    $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();

                    // if ($file->move(public_path('post-images'), $fileName)) {

                    $path = $file->storeAs(

                        'post-images',

                        $fileName,

                        's3'

                    );

                    // $path =  'post-images/' . $fileName;

                    PostFile::create([

                        'file' => $path,

                        'post_id' => $post->id

                    ]);

                    // }

                }

            }



            return redirect('admin/posts')->with('message', 'Post updated Successfully');

        }

    }



    /**

     * validation requests

     */

    public function validateRequest(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'title_english' => 'required|string|max:200',

            'title_urdu' => 'nullable',

            'title_arabic' => 'nullable',

            'post_type' => 'required',

            'files' => 'nullable',

            'status' => 'required',

        ]);



        if ($validator->fails()) {

            Session::flash('flash_danger', $validator->messages());

            return redirect()->back()->withInput();

        }



        return $validator->safe()->except(['action']);

    }



    /**

     * uploading files for the Posts

     */

    public function uploadFiles($file, $post = null)

    {

        $imagePaths = [];

        foreach ($file as $image) {

            /*$fileName = 'post' . time() . rand(1, 100) . '.' . $image->extension();

            if ($image->move(public_path('post-images'), $fileName)) {

                $path =  'post-images/' . $fileName;

                $imagePaths[] = $path;

            }*/

            ImageOptimize::optimize($image, $post);

        }

        return $imagePaths;

    }



    /**

     * removing the Posts

     */

    public function destroy($id)

    {

        if (!have_right('Delete-Posts'))

            access_denied();

        $data = [];

        $id = $id;

        $data['row'] = Post::destroy($id);

        return redirect('admin/posts')->with('message', 'Data deleted Successfully');

    }



    /**

     * show specific Posts

     */

    public function show(Request $request)

    {

        $post = Post::find($request->post_id);



        $html = view('admin.partial.show-post', get_defined_vars())->render();



        return response()->json(['html' => $html, 'status' => 200]);

    }



    /**

     * status of the Posts

     */

    public function status(Request $request, $id)

    {

        $post = Post::find($id);

        $post->update(['status' => $request->status]);

        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();



        if ($request->status == 1 && $post->user_id != null) {

            //push nottification

            $title = 'Admin';
            $user = User::find($post->user_id);

//            $body =  'Admin Approve Your Post';

            if($user->lang == 'english') {
                $body = 'Admin Approve Your Post';
            }else{
                $body = 'ایڈمن نے آپ کی پوسٹ منظور کر لی ہے';
            }


            $data = [

                'type' => 'view-post-details',

                'date_id' => $post->id

            ];

            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);



            // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

            // send notification

            $notification = Notification::create([

                'title' => Str::words($post->title_english, 3, '...') . ' has been uploaded',

                'title_english' => Str::words($post->title_english, 3, '...') . ' has been uploaded',

                'title_urdu' => 'اپ لوڈ لوڈ کر دی گئی ہے' .  Str::words($post->title_english, 3, '...'),

                'title_arabic' => 'تم تحميله' . Str::words($post->title_english, 3, '...'),

                'link' => route('user.specific-post', hashEncode($post->id)),

                'module_id' => 36,

                'right_id' => 135,

                'ip' => request()->ip()

            ]);






            // send notification to all users for blood if post is blood post

            if ($post->post_type == 5) {

                $bloodPostNotification = Notification::create([

                    'title' => $user->user_name . ' has created blood post',

                    'title_english' => $user->user_name . ' has created blood post',

                    'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . 'نے بلڈ پوسٹ بنائی ہے ',

                    'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . 'قد خلقت آخر الدم ',

                    'link' => route('user.specific-post', hashEncode($post->id))

                ]);



                $user_ids = User::where('division_id', $user->division_id)->where('id', '!=', $user->id)->pluck('id')->toArray();

                $notEligibleIds = [];

                $notEligibleDonors = Donor::query()

                    ->select('user_id', 'eligible_after')

                    ->whereIn('user_id', $user_ids)

                    ->get();



                foreach ($notEligibleDonors as $notEligibleDonor) {

                    $todayDate = date('Y-m-d');

                    $notEligibleDate = $notEligibleDonor->eligible_after;

                    if ($notEligibleDate > $todayDate) {

                        $notEligibleIds[] = $notEligibleDonor->user_id;

                    }

                }



                $eligibleIds = array_diff($user_ids, $notEligibleIds);



                if ($user->division_id != null) {

                    $bloodPostNotification->users()->attach($eligibleIds, ['notification_type' => 0, 'from_id' => $user->id]);

                }

            }



            $details = [

                'subject' =>  "Post Approved Successfully",

                'user_name' =>  $post->user->user_name,

                'content'  => "<p> Your post : " . Str::words($post->title_english, 3, '...') . " has approved by Admin .</p>",

                'links'    =>  "<a href='" . url('/user/dashboard') . "'>Click Here</a> To See Posts",

            ];

            // sendEmail($post->user->email, $details);

            saveEmail($post->user->email, $details);



            $notification->users()->attach($post->user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]);

        }

        if ($request->status == 2 && $post->user_id != null) {

            //push nottification

            $title = 'Admin';

//            $body = 'Admin Reject Your Post';
            $user = User::find($post->user_id);


            if($user->lang == 'english') {
                $body = 'Admin Reject Your Post';
            }else{
                $body = 'ایڈمن نے آپ کی پوسٹ مسترد کر دی ہے';
            }

            $data = [

                'type' => 'view-post-details',

                'date_id' => $post->id

            ];

            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);



            // sendNotificationrToUser($post->user_id, $title, $body, 'view-post-details', 'post_id', $post->id, 'key2', 'val2', 'key3', 'val3');

            $details = [

                'subject' =>  'Your post has rejected',

                'user_name' =>  $post->user->user_name,

                'content'  => "<p> Your post : " . Str::words($post->title_english, 3, '...') . " has rejected by Admin .</p>",

                'links'    =>  "<a href='" . url('/user/dashboard') . "'>Click Here To create new post</a>",

            ];

            // sendEmail($post->user->email, $details);

            saveEmail($post->user->email, $details);

        }



        return redirect()->back()->with('message', 'Status Updated Successfully');

    }

}

