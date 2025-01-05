<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\Comment\Comment;
use App\Models\Posts\Like\Like;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin\Donor;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User\ApplyJob;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

trait PostTrait
{
    /**
     *save post comments
    */
    function saveComment($request, $comment, $post)
    {

        $user_id = $request->user_id ? $request->user_id : '';
        $comment = Comment::create([
            'user_id' => $user_id,
            'post_id' => $request->post_id,
            'ip' => $request->ip(),
            'body' => $comment
        ]);
// dd($post);
        // notification

        $messageUrdu = 'نے آپ کی پوسٹ پر تبصرہ کیا ';
        $messageArabic = 'علق على رسالتك ';

        $fromUser = User::find($request->user_id);
        $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

        if($toUser->lang == 'english') {
            $message = ' commented on your post';
        }else{
            $message = 'نے آپ کی پوسٹ پر تبصرہ کیا ';
        }
        // log activity
        if ($request->user_id != '') $fromUser->log('you commented on a post', route('user.specific-post', hashEncode($post->id)));

        $notification = Notification::create([
            'title' => $request->user_id  == '' || $request->user_id == null ? 'guest user'.$message : $fromUser->user_name.$message,
            'title_english' => $request->user_id  == '' || $request->user_id == null ? 'guest user'.$message : $fromUser->user_name.$message,
            'title_urdu' => $request->user_id  == '' || $request->user_id == null ? 'مہمان صارف'.' '.$messageUrdu : (($fromUser->user_name_urdu != '' || $fromUser->user_name_urdu != null) ? $fromUser->user_name_urdu : $fromUser->user_name).$messageUrdu,
            'title_arabic' => $request->user_id  == '' || $request->user_id == null ? 'استخدام الضيف'.$messageArabic : (($fromUser->user_name_arabic != '' || $fromUser->user_name_arabic != null) ? $fromUser->user_name_arabic : $fromUser->user_name).$messageArabic,
            'link' => route('user.specific-post', hashEncode($post->id)),
            'module_id'=> 36,
            'right_id'=>138,
            'ip'=>request()->ip()
        ]);

        if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => $request->user_id]);
        if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);

        //push nottification
        $title=$fromUser?$fromUser->full_name:'';
        $body=($fromUser ? $fromUser->full_name : '').$message;
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

        $data = [
            'type' => 'view-post-details',
            'data_id' => $post->id
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
        // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

        return $comment;
    }

    /**
     *delete post likes
    */
    public function deleteLike($post, $request)
    {
        if ($request->user_id  == '' || $request->user_id == null ) $post->likes()->where('ip', $request->ip())->delete();

        $post->likes()->where('user_id', $request->user_id)->delete();
    }

    /**
     *check is post like exist
    */
    public function isPostLikeExists($post, $request)
    {
        // dd('test');
        return $post->likes()->where('ip', $request->ip())->exists();
    }

    /**
     *check is post like exist for auth
    */
    public function isAuthPostLikeExists($post, $request)
    {
        return $post->likes()->where('user_id', $request->user_id)->exists();
    }
    /**
     *store job post trait function
    */
    public function doJobPost(Request $request)
    {
        \Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'summary' => 'nullable',
            'occupation' => 'required',
            'experience' => 'required',
            'skills' => 'required',
            'resume' => 'nullable',
            'title' => 'required',
            'jobType' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Fail',
                'data'    =>  $validator->errors()->toArray(),

            ], 200);
        }

        if ($request->action == 'add') {
            // $post = $request->except(['resume', 'action','title','postType','phoneNumber','jobType','emailAddress','summary']);

            $postFile = $request->file('resume');

            $post['post_type'] = 2;
            $post['title_english'] = $request->title;
            $post['title_urdu'] = $request->title;
            $post['title_arabic'] = $request->title;

            $post['description_english'] = $request->summary;
            $post['description_urdu'] = $request->summary;
            $post['description_arabic'] = $request->summary;
            $post['occupation'] = $request->occupation;
            $post['job_seeker_or_hire_phone'] = $request->phoneNumber;
            $post['experience'] = $request->experience;
            $post['job_seeker_or_hire_email'] = $request->emailAddress;
            $post['skills'] = $request->skills;
            $post['job_seeker_name'] = $request->name;
            $post['job_seeker_currently_working'] = $request->job_seeker_currently_working;
            $post['job_seeker_or_hire_job_type'] = $request->job_seeker_or_hiring_job_type;
            $post['hiring_company_name'] = $request->hiring_company_name;
            $post['resume'] = $request->is_resume == 'on' ? User::find(auth()->id())->resume : '';


            $createdPost = auth()->user()->posts()->create( Arr::add($post, 'job_type', $request->jobType == 1 ? 1 : 2) );

            if ($request->hasFile('resume')) $this->uploadResume($postFile, $createdPost, 'post-images');

        } else {

            unset($request->action);
            $id = $request->postId;
            $post = Post::find($id);

            $post->update([
                'post_type'=>2,
                'title_english' => $request->title,
                'title_urdu' => $request->title,
                'title_arabic' => $request->title,
                'description_english' => $request->summary,
                'description_urdu' => $request->summary,
                'description_arabic' => $request->summary,
                'occupation' => $request->occupation,
                'experience' => $request->experience,
                'skills' => $request->skills,
                'job_seeker_or_hire_email' => $request->emailAddress,
                'job_seeker_or_hire_phone'=>$request->phoneNumber,
                'job_seeker_name' => $request->name,
                'job_seeker_currently_working' => $request->job_seeker_currently_working,
                'job_seeker_or_hire_job_type' => $request->job_seeker_or_hiring_job_type,
                'hiring_company_name' => $request->hiring_company_name,
                'resume' => $request->is_resume == 'on' ? User::find(auth()->id())->resume : '',
                'status' => 0,
            ]);

            $updatedPost = Post::find($id);

            if ($request->hasFile('resume')) $this->uploadResume($request->file('resume'), $updatedPost, 'post-images');
        }

        $admin = Admin::first();
        $details_admin = [
            'subject' =>  "Job Post Received",
            'user_name' =>  "Super Admin",
            'content'  => "<p> A user named " . ((Auth::check()) ? auth()->user()->user_name : "Guset User") . " submitted a job post via the job bank module .</p>",
            'links'    =>  "<a href='" . url('/admin/posts') . "'>Click here</a> to log in and approve/disapprove the job post",
        ];
        $details_user = [
            'subject' =>  "",
            'user_name' => ((Auth::check()) ? auth()->user()->user_name : "Guset User"),
            'content'  => "<p> You  created Job Post after approval post is show on the dashboard .</p>",
            'links'    =>  "<a href='" . url('user/dashboard') . "'>Click Here</a> To See Posts",
        ];
        // sendEmail(auth()->user()->email, $details_user);
        saveEmail(auth()->user()->email, $details_user);
        // sendEmail($admin->email, $details_admin);
        $adminEmail = settingValue('emailForNotification');
        saveEmail($adminEmail, $details_admin);
        if ($request->action == 'add') {
            // send notification
            $notification = Notification::create([
                'title' => auth()->user()->user_name . ' has created job post',
                'title_english' => auth()->user()->user_name . ' has created job post',
                'title_urdu' => auth()->user()->user_name.'نے نوکری کی پوسٹ بنائی ہے ',
                'title_arabic' => auth()->user()->user_name.'قام بإنشاء وظيفة ',
                'link' => route('posts.edit', $createdPost->id),
                'module_id'=> 23,
                'right_id'=>76,
                'ip'=>request()->ip()
            ]);
            $admin->notifications()->attach($notification->id);
        }
        else{
            // send notification
            $notification = Notification::create([
                'title' => auth()->user()->user_name . ' has updated job post',
                'title_english' => auth()->user()->user_name . ' has updated job post',
                'title_urdu' => auth()->user()->user_name.'نے نوکری کی پوسٹ اپ ڈیٹ کی ہے ',
                'title_arabic' => auth()->user()->user_name.'قام بإنشاء وظيفة ',
                'link' => route('posts.edit', $updatedPost->id),
                'module_id'=> 23,
                'right_id'=>76,
                'ip'=>request()->ip()
            ]);
            $admin->notifications()->attach($notification->id);
        }
    }
    /**
     *store blood post trait function
    */
    public function doBloodPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'hospital' => 'required',
            'address' => 'required',
            'title' => 'required',
            'postType' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Fail',
                'data'    =>  $validator->errors()->toArray(),

            ], 200);
        }

        if ($request->action == 'add') {
            $post = $request->except(['action']);

            $postFile = $request->file('files');
            $input=$request->all();

            $post = auth()->user()->posts()->create([
                'post_type' => 5,
                'title_english' => $input['title'],
                'title_urdu' => $input['title'],
                'title_arabic' => $input['title'],
                'city' => $input['city'],
                'hospital' => $input['hospital'],
                'address' => $input['address'],
                'blood_for'=>$input['blood_for'],
                'blood_group' => $input['bloodGroup'],

            ]);


            if ($request->hasFile('files')) foreach ($request->file('files') as $file) $this->uploadFiles($file, $post);

            // notification to all users
            $user_ids = User::query()->where('status', 1)->pluck('id')->toArray();
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

            $notification = Notification::create([
                'title' => auth()->user()->user_name . ' has created blood post',
                'title_english' => auth()->user()->user_name . ' has created blood post',
                'title_urdu' => auth()->user()->user_name.'نے بلڈ پوسٹ بنائی ہے ',
                'title_arabic' =>  auth()->user()->user_name.'قد خلقت آخر الدم ',
                'link' => route('posts.edit', $post->id),
                'module_id'=> 18,
                'right_id'=>53,
                'ip'=>request()->ip()
            ]);

            $admin = Admin::first();

            $details_admin = [
                'subject' =>  "Blood Donation Post",
                'user_name' =>  "Super Admin",
                'content'  => "<p> A user named " . ((Auth::check()) ? auth()->user()->user_name : "Guset User") . " asked for blood via the blood bank module  .</p>",
                'links'    =>  "<a href='" . url('/admin/posts') . "'>Click here </a> to log in and approve/disapprove the post.",
            ];
            $details_user = [
                'subject' =>  "Blood Donation Post",
                'user_name' => ((Auth::check()) ? auth()->user()->user_name : "Guset User"),
                'content'  => "<p> You  have created  blood post after approval post is show on the dashboard .</p>",
                'links'    =>  "<a href='" . url('user/dashboard') . "'>Click Here</a> To See Posts",
            ];
            // sendEmail(auth()->user()->email, $details_user);
            saveEmail(auth()->user()->email, $details_user);
            // sendEmail($admin->email, $details_admin);

            $adminEmail = settingValue('emailForNotification');
            saveEmail($adminEmail, $details_admin);
            $admin->notifications()->attach($notification->id);
        } else {
            unset($request->action);
            $id = $request->postId;
            $post = Post::find($id);

            $post->update([
                'post_type' => 5,
                'title_english' => $request->title,
                'title_urdu' => $request->title,
                'title_arabic' => $request->title,
                'city' => $request->city,
                'hospital' => $request->hospital,
                'address' => $request->address,
                'blood_for'=>$request->blood_for,
                'blood_group' => $request->bloodGroup,
                'status' => 0,
            ]);

            $updatedPost = Post::find($id);

            if ($request->status == 1 && $post->user_id != null) {
                // send notification

                $user = User::find($post->user_id);

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

            }
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
                else{
                    PostFile::where('post_id',$post->id)->delete();
                }

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
        }
        $admin = Admin::first();

        $details_admin = [
            'subject' =>  "Blood Donation Post",
            'user_name' =>  "Super Admin",
            'content'  => "<p> A user named " . ((Auth::check()) ? auth()->user()->user_name : "Guset User") . " asked for blood via the blood bank module  .</p>",
            'links'    =>  "<a href='" . url('/admin/posts') . "'>Click here </a> to log in and approve/disapprove the post.",
        ];
        $details_user = [
            'subject' =>  "Blood Donation Post",
            'user_name' => ((Auth::check()) ? auth()->user()->user_name : "Guset User"),
            'content'  => "<p> You  have created  blood post after approval post is show on the dashboard .</p>",
            'links'    =>  "<a href='" . url('user/dashboard') . "'>Click Here</a> To See Posts",
        ];
        // sendEmail(auth()->user()->email, $details_user);
        saveEmail(auth()->user()->email, $details_user);
        // sendEmail($admin->email, $details_admin);

        $adminEmail = settingValue('emailForNotification');
        saveEmail($adminEmail, $details_admin);
        if($request->action == 'edit'){
            $notification = Notification::create([
                'title' => auth()->user()->user_name . ' has updated blood post',
                'title_english' => auth()->user()->user_name . ' has updated blood post',
                'title_urdu' => auth()->user()->user_name.'نے بلڈ پوسٹ اپ ڈیٹ کی ہے ',
                'title_arabic' =>  auth()->user()->user_name.'قد خلقت آخر الدم ',
                'link' => route('posts.edit', $post->id),
                'module_id'=> 18,
                'right_id'=>53,
                'ip'=>request()->ip()
            ]);
            $admin->notifications()->attach($notification->id);
        }
    }
    /**
     *upload resume function
    */
    public function uploadResume($file, $createdPost, $dir)
    {
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();
            $path=$file->storeAs(
                $dir,
                $fileName,
                's3'
            );
        Post::where('id', $createdPost->id)->update(['resume' => $path]);
    }
    /**
     *upload post files function
    */
    public function uploadFiles($file, $post)
    {
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
