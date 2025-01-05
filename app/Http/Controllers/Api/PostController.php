<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollectionResource;
use App\Http\Resources\PostResource;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\Comment\Comment;
use App\Models\Posts\Like\Like;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PostController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    use PostTrait;
    /**
     *get posts
    */
    public function index(Request $request)
    {
        try {
            $posts = Post::with(['images:post_id,file', 'comments:id,post_id,user_id,body', 'comments.user:id,user_name,profile_image', 'user:id,user_name,profile_image','shareUser:id,user_name,profile_image', 'admin:id,first_name,profile'])
                ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
                ->active()
                ->latest()
                ->limit(10)
                ->offset($request->lastPostId ?? 0)
                ->get()
                ->each(fn ($post) => ($post->likes()->where('user_id', $request->userId)->exists()) ? $post->is_like = 1 : $post->is_like = 0)
                ->reject(fn ($post) => $post->job_type == 2);

            return new PostResource($posts);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * GET ALL POSTS
     *@param  [string] limit
     *@param  [integer] postType
     *@param  [integer] jobType
     *@return \Illuminate\Http\JsonResponse
     */

    public function getAllPosts(Request $request)
    {
        try {
            $userId = $request->userId;
            $postType = $request->postType;
            $jobType = $request->jobType;

            $userNameCols = array_merge(getQuery($request->lang, ['user_name']), ['id', 'user_name_english', 'user_name_urdu', 'user_name_arabic', 'is_public', 'profile_image']);
            $posts = Post::with(['images:id,post_id,file', 'comments:id,post_id,user_id,body', 'comments.user:id,user_name,profile_image', 'user' => fn($q) => $q->select($userNameCols), 'shareUser' => fn($q) => $q->select($userNameCols), 'admin:id,first_name,profile'])
            ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
                ->active()
                ->where('id', '>', $request->lastPostId ?? 0)
                // ->when($userId, function ($query, $userId) {
                //     return $query->where('user_id', $userId);
                // })
                ->when($postType, function ($query, $postType) {
                    return $query->where('post_type', $postType);
                })->when($jobType, function ($query, $jobType) {
                    return $query->where('job_type', $jobType);
                })
                ->latest()
                // ->limit($request->limit ?? 10)
                // ->get()
                ->paginate($request->limit ?? 10)
                ->each(fn ($post) => ($post->likes()->where('user_id', $request->userId)->exists()) ? $post->is_like = 1 : $post->is_like = 0);

                // return response()->json(['status' => 0, 'data' => $posts, 'message' => "No record found"]);

            // if (!$posts->isEmpty()) {
                return PostResource::collection($posts)
                    ->additional([
                        'message' => "Post Listing",
                        'status'  => 1
                    ]);
            // } else {
            //     return response()->json(['status' => 0, 'data' => [], 'message' => "No record found"]);
            // }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
    /**
     *get User posts
    */
    public function getUserPosts(Request $request)
    {
        try {
            $userId = $request->userId;
            $posts = Post::with(['images:id,post_id,file', 'comments:id,post_id,user_id,body', 'comments.user:id,user_name,profile_image', 'user:id,user_name,profile_image,is_public', 'admin:id,first_name,profile'])
            ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
                ->where('id', '>', $request->lastPostId ?? 0)
                ->when($userId, function ($query, $userId) {
                    return $query->where('user_id', $userId)->orWhere('share_id', $userId);
                })
                ->latest()
                ->limit($request->limit ?? 10)
                ->get()
                ->each(fn ($post) => ($post->likes()->where('user_id', $request->userId)->exists()) ? $post->is_like = 1 : $post->is_like = 0);

                // return response()->json(['status' => 0, 'data' => $posts, 'message' => "No record found"]);

            if (!$posts->isEmpty()) {
                return PostResource::collection($posts)
                    ->additional([
                        'message' => "Post Listing",
                        'status'  => 1
                    ]);
            } else {
                return response()->json(['status' => 0, 'data' => [], 'message' => "No record found"]);
            }
        } catch (\Exception $e) {

        }
    }
    /**
     *get post details api
    */
    public function getPostDetails(Request $request)
    {
        if (is_numeric($request->postId)) {
            $postId= $request->postId;
        } else {
            $postId= $id =encodeDecode($request->postId);
        }
        $posts = Post::with(['images:post_id,file', 'comments:post_id,body', 'comments:id,post_id,user_id,body', 'user:id,user_name,profile_image', 'admin:id,first_name,profile'])
            ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
            ->active()
            ->where('id', $postId)
            ->get()
            ->each(fn ($post) => ($post->likes()->where('user_id', $request->userId)->exists()) ? $post->is_like = 1 : $post->is_like = 0);
            // ->reject(fn ($post) => $post->job_type == 2);

        return PostResource::collection($posts);
    }

    /**
     *like post api
    */
    public function likePost(Request $request): \Illuminate\Http\JsonResponse
    {
        // dd( $request->ip() );
        // dd('test');
        $post = Post::find($request->post_id);

        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

        if ($request->user_id != '' ? $this->isAuthPostLikeExists($post, $request) : $this->isPostLikeExists($post, $request)) {
            $this->deleteLike($post, $request);
            return response()->json(['status' => 204, 'message' => 'like deleted', 'data' => $post->likes()->count()]);
        }

        $post->likes()->create([
            'user_id' => $request->user_id,
            'ip' => $request->ip(),
            'like' => 1
        ]);

        $user = User::find($request->user_id);

        if($user->lang == 'english') {
            $message = 'Your post is liked by ';
        }else{
            $message = ' نے آپ کی پوسٹ کو پسند کیا ہے';
        }
        $messageUrdu = 'نے آپ کی پوسٹ کو پسند کیا ہے ';
        $messageArabic = 'منشورك اعجب به ';

        $fromUser = User::find($request->user_id);
        $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

        // log activity
        if ($request->user_id != '') $fromUser->log('you like a post', route('user.specific-post', hashEncode($post->id)));

        $notification = Notification::create([
            'title' => $request->user_id  == '' || $request->user_id == null ? $message . 'guest user' : $message . $fromUser->user_name,
            'title_english' => $request->user_id  == '' || $request->user_id == null ? $message . 'guest user' : $message . $fromUser->user_name,
            'title_urdu' => $request->user_id  == '' || $request->user_id == null ? 'مہمان صارف' . $messageUrdu : (($fromUser->user_name_urdu != '' || $fromUser->user_name_urdu != null) ? $fromUser->user_name_urdu : $fromUser->user_name) . $messageUrdu,
            'title_arabic' => $request->user_id  == '' || $request->user_id == null ? 'استخدام الضيف' . $messageArabic : (($fromUser->user_name_arabic != '' || $fromUser->user_name_arabic != null) ? $fromUser->user_name_arabic : $fromUser->user_name) . $messageArabic,
            'link' => route('user.specific-post', hashEncode($post->id)),
            'module_id'=> 36,
            'right_id'=>137,
            'ip'=>request()->ip
        ]);

        if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => $request->user_id]);
        if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);

        //push nottification
        $title=$fromUser?$fromUser->full_name: '';
        $body = $message . ($fromUser ? $fromUser->full_name : '');

        $data = [
            'type' => 'view-post-details',
            'data_id' => $post->id
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);

        // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

        return response()->json(['status' => 200, 'message' => 'post liked', 'data' => $post->likes()->count()]);
    }

    /**
     *comment post api
    */
    public function commentPost(Request $request)
    {

        if ($request->comment == '') {
            return response()->json(['status' => 204, 'data' => 'please write something']);
        }
        $post = Post::find($request->post_id);
        // $request->whenFilled('comment', function ($comment) use ($request, $post) {
        $savedComment = $this->saveComment($request, $request->comment, $post);
        // });

        return response()->json(['status' => 200, 'message' => 'comment posted', 'data' => $post->comments()->count(), 'comment' => $savedComment]);
    }
    /**
     *get real time likes of post
    */
    public function fetchRealTimeLikes()
    {
        $data = Post::withCount('likes')->get();
        return response()->json(['status' => 200, 'data' => $data]);
    }
    /**
     *get real time comments of post
    */
    public function fetchRealTimeComments()
    {
        $data = Post::withCount('comments')->with('comments.user')->get();
        return response()->json(['status' => 200, 'data' => $data]);
    }
    /**
     *delete post api
    */
    public function destroy(Request $request)
    {
        $post = Post::find($request->post_id);

        if ($post->user_id == $request->user_id || $post->share_id == $request->user_id) {
            $post->likes()->delete();
            $post->comments()->delete();
            $post->delete();
            return response()->json(['status' => 1, 'message' => 'post deleted']);
        }

        return response()->json(['status' => 0, 'message' => 'Unauthorized']);
    }
    /**
     *Add user posts of all types
    */
    public function addUserPost(Request $request)
    {

        if(request()->lang=='english'){
            $addSucessMessage='post created and sent to admin for approval!';
            $updateSucessMessage='post Update and sent to admin for approval!';
        }
        else{
            $addSucessMessage='پوسٹ بنائی گئی ہے اور منظوری کے لیے ایڈمن کو بھیج دی گئی ہے';
            $updateSucessMessage='پوسٹ کو اپ ڈیٹ کر کے ایڈمن کو منظوری کے لیے بھیج دیا گیا';
        }
        if ($request->postType == 2) {
            // 2 = job post
            $job_post=$this->doJobPost($request);
            if($job_post!=null){
                return $job_post;
            }
            return response()->json(['status' => 1, 'message' => $addSucessMessage]);
        }
        if ($request->postType == 5) {
            // 5 = blood post
            $blood_post=$this->doBloodPost($request);
            if($blood_post!=null){
                return $blood_post;
            }
            return response()->json(['status' => 1, 'message' => $addSucessMessage]);
        }
        $rules = [
            'files' => Rule::requiredIf(!$request->filled('title') || $request->title == ''),
            'title' => Rule::requiredIf(!$request->has('files'))
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'Please post something'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 0,
                'message' => 'validation fails',
                'data' => $validator->errors()->toArray()
            ]);

        $title = $request->title;

        // create post
        $user = User::find($request->user_id);
        if($request->action=='add'){

        $post = $user->posts()->create(['post_type' => 1, 'title_english' => $title, 'title_urdu' => $title, 'title_arabic' => $title]);

        // if request has files then save to DB
        if ($request->hasFile('files')) foreach ($request->file('files') as $file) $this->uploadFiles($file, $post);

        // send notification
        $notification = Notification::create([
            'title' => $user->user_name . ' has created post',
            'title_english' => $user->user_name . ' has created post',
            'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . 'نے پوسٹ بنائی ہے ',
            'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . 'قام بإنشاء وظيفة  ',
            'link' => route('posts.edit', $post->id),
            'module_id'=> 23,
            'right_id'=>76,
            'ip'=>request()->ip()

        ]);

        $admin = Admin::first();

        $admin->notifications()->attach($notification->id);

        return response()->json(['status' => 1, 'message' => $addSucessMessage]);
        }
        else{
            unset($request->action);
            $id = $request->postId;
            $post = Post::find($id);
            $post->update([
                'post_type' => 1,
                'title_english' => $request->title,
                'title_urdu' => $request->title,
                'title_arabic' => $request->title,
            ]);

            if ($post->user_id != null) {
                // send notification
                $notification = Notification::create([
                    'title' => auth()->user()->user_name . ' has edit post',
                    'title_english' =>  auth()->user()->user_name . ' has edit post',
                    'title_urdu' => auth()->user()->user_name.'نےایڈٹ کی ہے ',
                    'title_arabic' => auth()->user()->user_name.'حررت بواسطة ',
                    'link' => route('posts.edit', $post->id),
                    'module_id'=> 23,
                    'right_id'=>77,
                    'ip'=>request()->ip()
                ]);

                $admin = Admin::first();
                $admin->notifications()->attach($notification->id);
            }

            // if user remove files, then delete from DB
            if ($request->has('old_files')) {
                $postImagesIds = $post->images->map(fn ($img) => $img->id)->toArray();
                $diffArray = array_diff($postImagesIds, $request->old_files);
                if ($diffArray){
                    $postFiles=PostFile::whereIn('id', $diffArray)->get();
                    foreach ($postFiles as $postFile) {
                        $s3FilePath = $postFile->file; // Replace this with the actual column name storing the S3 file path
                        // Delete the file from S3
                        Storage::disk('s3')->delete($s3FilePath);
                    }
                    PostFile::whereIn('id', $diffArray)->delete();
                }

            }
            if (empty($request->old_files) && empty($request->files)) {
                PostFile::where('post_id',$post->id)->delete();
            }

            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();
                    $path=$file->storeAs(
                        'post-images',
                        $fileName,
                        's3'
                    );
                    PostFile::create([
                        'file' => $path,
                        'post_id' => $post->id
                    ]);
                }
            }

            return response()->json(['status' => 1, 'message' => $updateSucessMessage]);
        }
    }
    /**
     *Upload files function
    */
    public function uploadFiles($file, $post)
    {
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();
        $path = $file->storeAs(
                'post-images',
                $fileName,
                's3'
            );
        PostFile::create(['file' => $path, 'post_id' => $post->id]);
        // if ($file->move(public_path('post-images'), $fileName)) {
        //     $path =  'post-images/' . $fileName;

        //     PostFile::create(['file' => $path, 'post_id' => $post->id]);
        // }
    }
    /**
     *Share post backup function
    */
    public function sharePost_bk(Request $request)
    {
        if ($request->user_id != '' || $request->user_id != null) {
            if (!have_permission_for_api(User::find($request->user_id), 'Share-News-Feed-Posts')) return response()->json(['status' => 0, 'message' => 'You do not have permission to share post']);
        }

        $post = Post::find($request->post_id);

        // replicate (copy) post
        $sharedPost = $post->replicate();
        $sharedPost->created_at = Carbon::now();
        $sharedPost->updated_at = Carbon::now();
        $sharedPost->share_id = $request->user_id;
        $sharedPost->save();
        // copy post files
        foreach ($post->files as $file) {
            $sharedFile = $file->replicate();
            $sharedFile->post_id = $sharedPost->id;
            $sharedFile->save();
        }

        $user = User::find($request->user_id);
        $user->log('you shared a post', route('user.specific-post', $post->id));

        return response()->json(['status' => 200, 'message' => 'post shared']);
    }
    /**
     *Share post on profile api
    */
    public function sharePost(Request $request)
    {
        if ($request->user_id != '' || $request->user_id != null) {
            if (!have_permission_for_api(User::find($request->user_id), 'Share-News-Feed-Posts')) return response()->json(['status' => 0, 'message' => 'You do not have permission to share post']);
        }

        $post = Post::find($request->post_id);
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

        // replicate (copy) post
        $sharedPost = $post->replicate();
        $sharedPost->created_at = Carbon::now();
        $sharedPost->updated_at = Carbon::now();
        $sharedPost->share_id = $request->user_id;

        $sharedPost->save();

        // copy post files
        foreach ($post->files as $file) {
            $sharedFile = $file->replicate();
            $sharedFile->post_id = $sharedPost->id;
            $sharedFile->save();
        }

        /* log activity */
        $user=User::find($request->user_id);
        $user->log('you shared a post', route('user.specific-post', $post->id));

        //push nottification
        $title=$user->full_name;
        if($user->lang == 'english') {
            $body=$user->full_name.' shared your post';
        }else{
            $body = $user->full_name.' نے آپ کی پوسٹ شیئر کی ہے';
        }
        $data = [
            'type' => 'view-post-details',
            'data_id' => $post->id
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
        // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

        return response()->json(['status' => 200, 'message' => 'post shared']);

    }
    /**
     *Delete posts comments api
    */
    public function deleteComment(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        $comment->delete();

        return response()->json(['status' => 200, 'message' => 'comment deleted']);
    }
    /**
     *user delete post comments api
    */
    public function userDeleteComment(Request $request)
    {
        $comment = Comment::query()->where('id', $request->comment_id)->where('post_id', $request->post_id)->first();

        $comment->delete();

        return response()->json(['status' => 1, 'message' => 'deleted']);
    }
    /**
     *get social links api
    */
    public function getSocialLinks(Request $request){
        $data=[];
        $facebookUrl='https://www.facebook.com/dialog/share?app_id=656385503002340&display=popup&href='.route('user.specific-post', hashEncode($request->postId)).'&redirect_uri='.route('user.specific-post', hashEncode($request->postId)) .'';
        $twitterUrl='https://twitter.com/intent/tweet?text='. route('user.specific-post', hashEncode($request->postId)) .'';
        $data['facebookUrl']=$facebookUrl;
        $data['twitterUrl']=$twitterUrl;
        return response()->json(['status' => 1, 'message' => 'links created!!','data'=>$data]);
    }
    /**
     *get job applicants of job posts
    */
    public function getJobApplicant(){
        if (!have_permission('View-CV-Resume-Applicants')) {
            $authUserPosts = collect([]);
        } else {
            $authUserPosts = Post::with('applied')->active()->job()->hiring()->where('user_id', Auth::id())->get()
            ->each(function ($item) {
                $item->phone=$item->job_seeker_or_hire_phone;
                $item->email=$item->job_seeker_or_hire_email;
                $item->postId=$item->id;
            });
        }
        return response()->json(['status' => 1, 'message' => 'success','data'=>$authUserPosts]);
    }
}
