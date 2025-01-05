<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodPostRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\PostFile\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodBankController extends Controller
{
    /**
     *Storing Blood Bank Post
    */
    public function store(StoreBloodPostRequest $request)
    {
        if (!have_permission('Create-Blood-Bank-Post')) return redirect()->back()->with('error',  __('app.not-permission'));

        $validated = $request->validated();
        // dd($validated['blood_for']);
        $post = auth()->user()->posts()->create([
            'post_type' => 5,
            'title_english' => $validated['title'],
            'title_urdu' => $validated['title'],
            'title_arabic' => $validated['title'],
            'city' => $validated['city'],
            'blood_group' => $validated['blood_group'],
            'hospital' => $validated['hospital'],
            'address' => $validated['address'],
            'blood_for'=>$validated['blood_for'],
          
        ]);

        // if request has files then save to DB
        if ($request->hasFile('files')) foreach ($request->file('files') as $file) $this->uploadFiles($file, $post);
        // send notification

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

        return redirect()->back()->with('success', __('app.post-created-message'));
    }
    
    /**
     *Uploading Files in Databse and Project folder
    */
    public function uploadFiles($file, $post)
    {
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();

        $path=$file->storeAs(
            'post-images',
            $fileName,
            's3'
        );
        //dd($path);
        PostFile::create(['file' => $path, 'post_id' => $post->id]);
        // if ($file->move(public_path('post-images'), $fileName)) {
        //     $path =  'post-images/' . $fileName;

            
        // }

    }
}
