<?php

namespace App\Http\Controllers\User;

use App\Events\SendNotification;
use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\City;
use App\Models\Admin\Notification;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Image;
use App\Models\Admin\Cabinet;

class PostController extends Controller
{
    /**
     *Creating New Feed Post
     */
    public function createPost(Request $request)
    {
        if (!have_permission('Create-News-Feed-Posts')) return redirect()->back()->with('error',  __('app.not-permission'));
        // validate request
        $messages = [
            'files.max' => 'files cannot be more than 5'
        ];
        $request->validate([
            'title' => 'nullable',
            'files.*' => 'mimes:jpg,jpeg,png,bmp,svg,gif,webp,mp4,mov,wmv,avi,flv,swf,mkv,webm|max:10000',
            //            'files' => 'max:5'
        ], $messages);

        $title = $request->title;

        // create post
        $post = auth()->user()->posts()->create(['post_type' => 1, 'title_english' => $title, 'title_urdu' => $title, 'title_arabic' => $title]);

        // if request has files then save to DB
        if ($request->hasFile('files')) foreach ($request->file('files') as $file) $this->uploadFiles($file, $post, $this->detectDiscardedFiles($request, 'create'), 'create');
        // send notification

        $notification = Notification::create([
            'title' => auth()->user()->user_name . ' has created post',
            'title_english' =>  auth()->user()->user_name . ' has created post',
            'title_urdu' => auth()->user()->user_name . 'نے پوسٹ بنائی ہے ',
            'title_arabic' => auth()->user()->user_name . 'قام بإنشاء وظيفة ',
            'link' => route('posts.edit', $post->id),
            'module_id' => 23,
            'right_id' => 76,
            'ip' => request()->ip()
        ]);

        $admin = Admin::first();
        $details_admin = [
            'subject' => "Simple Post Received",
            'user_name' => "Super Admin",
            'content' => "<p> A user named " . ((Auth::check()) ? auth()->user()->user_name : 'Guest user') . " submitted a post .</p>",
            'links' => "<a href='" . url('/admin/posts') . "'>Click here</a> to log in and approve/disapprove the post",
        ];
        $details_user = [
            'subject' => "s",
            'user_name' => (Auth::check()) ? auth()->user()->user_name : 'Guest user',
            'content' => "<p> You  Created Simple Post after approval post is show on the dashboard .</p>",
            'links' => "<a href='" . url('user/dashboard') . "'>Click Here To See Posts</a>",
        ];
        // sendEmail(auth()->user()->email, $details_user);
        saveEmail(auth()->user()->email, $details_user);
        // sendEmail($admin->email, $details_admin);
        $adminEmail = settingValue('emailForNotification');
        saveEmail($adminEmail, $details_admin);
        $admin->notifications()->attach($notification->id);

        return redirect()->back()->with('success', __('app.post-created-message'));
    }

    /**
     *Updating new post
     */
    public function updatePost(Request $request)
    {
        if (!have_permission('Edit-News-Feed-Posts')) return redirect()->back()->with('error',  __('app.not-permission'));

        $post = Post::where('id', $request->post_id)->first();

        // detect spam request
        if ($post->user_id != auth()->user()->id) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        if ($post->post_type == 5) { // blood post
            if ($post->title_english == $request->title && $post->city == $request->city && $post->hospital == $request->hospital && $post->blood_group == $request->blood_group && $request->blood_for == $post->blood_for && !$this->detectMediaChange($request, $post)) {
                return redirect()->back()->with('success', __('app.made-no-change'));
            }
            // update post

            $post->update(['title_english' => $request->title, 'title_urdu' => $request->title, 'title_arabic' => $request->title, 'city' => $request->city, 'hospital' => $request->hospital, 'address' => $request->address, 'status' => 0, 'blood_for' => $request->blood_for,'blood_group' => $request->blood_group]);
        }
        if ($post->post_type == 1) { // simple post
            if ($post->title_english == $request->title && !$this->detectMediaChange($request, $post)) {
                return redirect()->back()->with('success', __('app.made-no-change'));
            }
            // update post
            $post->update(['title_english' => $request->title, 'title_urdu' => $request->title, 'title_arabic' => $request->title, 'status' => 0]);
        }

        if ($post->post_type == 2) { // job post
            if (!$this->isChangeInPost($post, $request)) {
                return redirect()->back()->with('success',  __('app.made-no-change'));
            }
            // update post
            $post->update(
                [
                    'title_english' => $request->title, 'occupation' => $request->occupation, 'experience' => $request->experience, 'skills' => $request->skills,
                    'description_english' => $request->description_english, 'description_urdu' => $request->description_english, 'description_arabic' => $request->description_english,
                    'job_seeker_name' => $request->job_seeker_name, 'job_seeker_or_hire_email' => $request->job_seeker_or_hire_email,
                    'job_seeker_or_hire_phone' => $request->job_seeker_or_hire_phone, 'job_seeker_currently_working' => $request->job_seeker_currently_working,
                    'job_seeker_or_hire_job_type' => $request->job_seeker_or_hire_job_type, 'hiring_company_name' => $request->hiring_company_name,
                    'status' => 0
                ]
            );
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

        // if request has files then save to DB
        if ($request->hasFile('files')) foreach ($request->file('files') as $file) $this->uploadFiles($file, $post, $this->detectDiscardedFiles($request, 'edit'), 'edit');

        // send notification
        $notification = Notification::create([
            'title' => auth()->user()->user_name . ' has edit post',
            'title_english' =>  auth()->user()->user_name . ' has edit post',
            'title_urdu' => auth()->user()->user_name . 'نےایڈٹ کی ہے ',
            'title_arabic' => auth()->user()->user_name . 'حررت بواسطة ',
            'link' => route('posts.edit', $post->id),
            'module_id' => 23,
            'right_id' => 77,
            'ip' => request()->ip()
        ]);

        $admin = Admin::first();
        $admin->notifications()->attach($notification->id);

        return redirect()->back()->with('success', __('app.sent-admin'));
    }

    /**
     *Deleting new post
     */
    public function deletePost(Request $request)
    {
        $post = Post::find($request->post_id);
        if ($post->user_id == auth()->user()->id || $post->share_id == auth()->user()->id) {
            $post->delete();
            return response()->json(['status' => 1, 'message' => 'post deleted']);
        }

        return response()->json(['status' => 0, 'message' => 'Unauthorized']);
    }

    /**
     *Showing feed Post
     */
    public function showPost(Request $request)
    {
        $cols = array_merge(getQuery(App::getLocale(), ['title']), [
            'id', 'admin_id', 'user_id', 'job_type', 'post_type', 'product_id', 'city', 'hospital', 'address', 'occupation', 'experience',
            'skills', 'resume', 'description_english', 'job_seeker_name', 'job_seeker_or_hire_email', 'job_seeker_or_hire_phone', 'job_seeker_currently_working',
            'job_seeker_or_hire_job_type', 'hiring_company_name', 'blood_for','blood_group'
        ]);

        $cityCols =  array_merge(getQuery(App::getLocale(), ['name']), ['id']);
        $cities = City::select($cityCols)->where('status', 1)->get();
        $post = Post::where('id', $request->post_id)->select($cols)->with(['images', 'comments.user', 'likes', 'user', 'admin', 'citi' => fn ($q) => $q->select($cityCols)])->first();
        $html = view('user.partials.show-post-partial', get_defined_vars())->render();

        return response()->json(['status' => 200, 'data' => $html]);
    }

    /**
     *upload files for related post
     */
    public function uploadFiles($file, $post, $discardedFilesArray, $case)
    {
        if (!in_array($file->getClientOriginalName(), $discardedFilesArray) && $case == 'create') {
            ImageOptimize::optimize($file, $post);
        }
        if ($case == 'edit') {
            ImageOptimize::optimize($file, $post);
        }
    }

    /**
     *detecting if on updation there is achange of image
     */
    public function detectMediaChange($request, $post)
    {
        if ($request->has('old_files')) {
            $postImagesIds = $post->images->map(fn ($img) => $img->id)->toArray();
            $diffArray = array_diff($postImagesIds, $request->old_files);
            if (empty($diffArray) && !$request->hasFile('files')) return false;
        }
        if (!$request->has('old_files') && !$request->hasFile('files')) {
            $post->images()->delete();
        }
        return true;
    }

    /**
     *discarding unnecessary files on updation
     */
    public function detectDiscardedFiles($request, $case)
    {
        $fileNames = $case == 'create' ? $request->tracking_files : $request->tracking_files_edit;

        $discardedFiles = explode(',', $fileNames);
        $originalFileNames = [];
        foreach ($request->file('files') as $file) {
            $originalFileNames[] = $file->getClientOriginalName();
        }

        return array_diff($originalFileNames, $discardedFiles);
    }

    /**
     *function used in updation of post to check if there is change in the post
     */
    public function isChangeInPost($post, $request)
    {
        if (
            $post->title_english == $request->title && $post->occupation == $request->occupation && $post->experience == $request->experience && $post->skills == $request->skills
            && $post->description_english == $request->description_english && $post->job_seeker_or_hire_email == $request->job_seeker_or_hire_email
            && $post->job_seeker_or_hire_phone == $request->job_seeker_or_hire_phone
        ) {
            return false;
        }

        return true;
    }
}
