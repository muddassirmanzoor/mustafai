<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User;
use App\Models\User\ApplyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class JobBankController extends Controller
{
    /**
     *Creating Job Posts
     */
    public function createJobPost(JobPostRequest $request)
    {
        if (!have_permission('Create-Job-Bank-Post')) return redirect()->back()->with('error',  __('app.not-permission'));
        $request->validated();
        $post = $request->except(['resume', 'is_resume', 'apply_for_whom']);
        $postFile = $request->file('resume');

        $post['post_type'] = 2;
        $post['title_urdu'] = $request->title_english;
        $post['title_arabic'] = $request->title_english;
        $post['resume'] = $request->is_resume == 'on' ? User::find(auth()->id())->resume : '';
        $createdPost = auth()->user()->posts()->create(Arr::add($post, 'job_type', $request->job_type == 1 ? 1 : 0));

        if ($request->hasFile('resume')) $this->uploadResume($postFile, $createdPost, 'post-images');

        // send notification

        $notification = Notification::create([
            'title' => auth()->user()->user_name . ' has created job post',
            'title_english' => auth()->user()->user_name . ' has created job post',
            'title_urdu' => auth()->user()->user_name . 'نے نوکری کی پوسٹ بنائی ہے ',
            'title_arabic' => auth()->user()->user_name . 'قام بإنشاء وظيفة ',
            'link' => route('posts.edit', $createdPost->id),
            'module_id' => 23,
            'right_id' => 76,
            'ip' => request()->ip()
        ]);

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

        saveEmail(auth()->user()->email, $details_user);

        $adminEmail = settingValue('emailForNotification');
        saveEmail($adminEmail, $details_admin);
        $admin->notifications()->attach($notification->id);

        return redirect()->back()->with('success', __('app.post-created-message'));
    }

    /**
     *User Applying on Job Post
     */
    public function applyJob(Request $request)
    {
        if (!have_permission('Apply-Hiring-Job-Bank')) return redirect()->back()->with('error',  __('app.not-permission'));

        if (Auth::check()) {
            $isApplied = ApplyJob::where('user_id', auth()->user()->id)->where('job_post_id', $request->job_post_id)->exists();
            if ($isApplied) {
                return redirect()->back()->with('error', __('app.already-apply-job'));
            }
        }

        if (!Auth::check()) {
            $isGuestApplied = ApplyJob::where('ip', $request->ip())->where('job_post_id', $request->job_post_id)->exists();
            if ($isGuestApplied) {
                return response()->json(['status' => 0, 'message' => __('app.already-apply-job')]);
            }
        }

        // if person is applying on job and not filled education, experience etc
        if ($request->is_resume == 'on') {
            $user = User::with(['experience', 'education'])->where('id', auth()->user()->id)->first();
            if (!$this->hasSkills($user) || $user->experience->count() == 0 || $user->education->count() == 0) {
                return redirect(route('user.profile'))->with('error', $this->whatIsMissing($user));
            } else {
                $this->createResume();
            }
        }

        $post = $request->except(['resume', 'is_resume']);
        $jobFile = $request->file('resume');

        $createdJob = ApplyJob::create([
            'name' => $request->name,
            'experience' => $request->experience,
            'age' => $request->age,
            //            'resume' => $request->is_resume == 'on' ? auth()->user()->resume : '',
            'resume' => $request->is_resume == 'on' && $request->apply_for_whom == 'you' ? auth()->user()->resume : '',
            'user_id' => auth()->user()->id ?? null,
            'ip' => !auth()->check() ? $request->ip() : null,
            'job_post_id' => $request->job_post_id
        ]);

        $post = Post::find($request->job_post_id);
        $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

        // log activity
        if (\Auth::check()) auth()->user()->log('you applied at job', route('user.specific-post', hashEncode($post->id)));

        if ($request->hasFile('resume')) $this->uploadFiles($jobFile, $createdJob, 'job-files');

        // notification to user
        $userName = $request->name ?? 'guest user';
        $notification = Notification::create([
            'title' => $userName . ' has applied to your job',
            'title_english' => $userName . ' has applied to your job',
            'title_urdu' => $userName . 'نے آپ کی نوکری کے لیے اپلائی کر دیا ہے ',
            'title_arabic' => $userName . 'تقدم إلى وظيفتك ',
            'link' => route('user.job-bank', 1),
            'module_id' => 37,
            'right_id' => 146,
            'ip' => request()->ip()

        ]);

        if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => auth()->user()->id ?? null]);
        if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);

        $toName = ($toUser instanceof User) ? $toUser->user_name : $toUser->first_name;


        $mailName = '';
        if (Auth::check()) {
            $mailName = auth()->user()->user_name;
        } else {
            $mailName = $request->name;
        }

        $details = [
            'user_name' => $toName,
            'content'  => "<p>Your applied for the job via your job post " . $mailName . " .</p>",
            'links'    =>  "<a href='" . url('/user/job-bank') . "'>Click here</a> to view the candidate’s detail",
        ];
        $email = $toUser->email;
        try {
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('success', __('app.successfully-applied'));
    }

    /**
     *Guest Applying on Job Post
     */
    public function guestApplyJob(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $isApplied = ApplyJob::where('user_id', auth()->user()->id)->where('job_post_id', $request->job_post_id)->exists();
                if ($isApplied) {
                    return response()->json(['status' => 0, 'message' => __('app.already-apply-job')]);
                }
            }

            if (!Auth::check()) {
                $isGuestApplied = ApplyJob::where('ip', $request->ip())->where('job_post_id', $request->job_post_id)->exists();
                if ($isGuestApplied) {
                    return response()->json(['status' => 0, 'message' => __('app.already-apply-job')]);
                }
            }

            $post = $request->except(['resume']);
            $jobFile = $request->file('resume');

            $createdJob = ApplyJob::create([
                'name' => $request->name,
                'experience' => $request->experience,
                'age' => $request->age,
                'resume' => $request->is_resume == 'on' ? (!empty(auth()->user()) ? auth()->user()->resume : '') : '',
                'user_id' => !empty(auth()->user()) ? auth()->user()->id : null,
                'ip' => !auth()->check() ? $request->ip() : null,
                'job_post_id' => $request->job_post_id
            ]);

            $post = Post::find($request->job_post_id);
            $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

            // log activity
            if (\Auth::check()) auth()->user()->log('you applied at job', route('user.specific-post', hashEncode($post->id)));

            if ($request->hasFile('resume')) $this->uploadFiles($jobFile, $createdJob, 'job-files');

            // notification to user
            $userName = $request->name ?? 'guest user';
            $notification = Notification::create([
                'title' => $userName . ' has applied to your job',
                'title_english' => $userName . ' has created job post',
                'title_urdu' => $userName . 'نے آپ کی نوکری کے لیے اپلائی کر دیا ہے ',
                'title_arabic' => $userName . 'تقدم إلى وظيفتك ',
                'link' => route('user.job-bank', 1),
                'module_id' => 37,
                'right_id' => 146,
                'ip' => request()->ip()
            ]);


            if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => auth()->user()->id ?? null]);
            if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);

            $toName = ($toUser instanceof User) ? $toUser->user_name : $toUser->first_name;
            $mailName = '';
            if (Auth::check()) {
                $mailName = auth()->user()->user_name;
            } else {
                $mailName = $request->name;
            }

            $details = [
                'subject' => "Job Application Received",
                'user_name' => $toName,
                'content'  => "<p> " . $mailName . "  applied for the job via your job post  </p>",
                'links'    => "<a href='" . url('/user/job-bank') . "'>Click Here</a>to view the candidate’s details",
            ];
            $email = $toUser->email;
            try {
            } catch (Exception $e) {
                return response()->json(['status' => 0, 'message' => $e->getMessage()]);
            }
            return response()->json(['status' => 200]);
        }
    }

    /**
     *Uploading files of related post
     */
    public function uploadFiles($file, $createdPost, $dir)
    {
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();

        $path = $file->storeAs(
            $dir,
            $fileName,
            's3'
        );
        ApplyJob::where('id', $createdPost->id)->update(['resume' => $path]);
    }

     /**
     * upload resume for Post trait
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
     *Checking user skills
     */
    public function hasSkills($user): bool
    {
        if ($user->skills_english != null || $user->skills_urdu != null || $user->skills_arabic != null) {
            return true;
        }

        return false;
    }

    /**
     *Unlink the image when Edit
     */
    public function deleteEditoImage($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }

    /**
     *Creating Resume
     */
    public function createResume()
    {
        $html = (string) View('user.pdf.resume');
        $footer = '<p style="font-size: 8px; color: #666; text-align:center; margin-top: 0px;">.</p>';
        $mpdf = new Mpdf(['margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10, 'margin_header' => 0, 'margin_footer' => 0,]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S'); // Output the PDF as a string
        // Define the folder and file name within the "resume" folder
        $folder = 'Resume';
        $fileName = $folder . '/' . Auth::user()->user_name . '_' . md5(time()) . '.pdf';
        // Store the PDF on S3 in the specified folder
        $pdfPath=Storage::disk('s3')->put($fileName, $pdfContent);
        // $fileName = Auth::user()->user_name . '_' . md5(time()) . '.pdf';
        // $pdfPath = public_path('Resume/' . $fileName);
        if ($pdfPath) {
            $mpdf->Output($pdfPath, 'F');
            $path = $fileName;
            $id = Auth::user()->id;
            $user = User::find($id);
            if (!empty($user->resume)) {
                deleteS3File($user->resume);
                // $this->deleteEditoImage($user->resume);
            }
            $user->resume = $path;
            $user->update();
        }
        // $pdf = app('dompdf.wrapper');
        // $pdf->getDomPDF()->set_option("enable_php", true);
        // $pdf = PDF::loadView('user.pdf.resume')->setPaper('a2', 'potrait');
        // $fileName = Auth::user()->user_name . '_' . md5(time()) . '.pdf';
        // $savlfile = Storage::put('public/pdf/' . $fileName, $pdf->output());

        // if ($savlfile) {
        //     $path = 'storage/pdf/' . $fileName;
        //     $id = Auth::user()->id;
        //     $user = User::find($id);
        //     if (!empty($user->resume)) {
        //         $this->deleteEditoImage($user->resume);
        //     }
        //     $user->resume = $path;
        //     $user->update();
        // }
    }
    
    /**
     *If user does not fills the skills or experience or education it gives error while creating post.
     */
    public function whatIsMissing($user): string
    {
        $whatMissing = '';
        $whatMissing .= !$this->hasSkills($user) ? __('app.skills') . ',' : '';
        $whatMissing .= $user->experience->count() == 0 ? __('app.experience') . ',' : '';
        $whatMissing .= $user->education->count() == 0 ? __('app.education') . ',' : '';
        // remove last comma of string
        $whatMissing = Str::of($whatMissing)->beforeLast(',');

        return $whatMissing .= __('app.is-missing');
    }
}
