<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Admin\Admin;
use App\Models\Admin\Donor;
use App\Models\Admin\Notification;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User;
use App\Models\User\ApplyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait PostTrait
{
    /**
     * store for job post trait function
    */
    public function doJobPost(Request $request)
    {
        $request->validate([
            'description_english' => 'required',
            'occupation' => 'required',
            'experience' => 'required',
            'skills' => 'required',
            'resume' => 'nullable',
            'title_english' => 'required',
            'job_type' => 'required'
        ]);

        if ($request->action == 'add') {
            $post = $request->except(['resume', 'action']);

            $postFile = $request->file('resume');

            $post['post_type'] = 2;
            $post['title_urdu'] = $request->title_english;
            $post['title_arabic'] = $request->title_english;

            $post['description_urdu'] = $request->description_english;
            $post['description_arabic'] = $request->description_english;

            $createdPost = auth()->user()->posts()->create(Arr::add($post, 'job_type', $request->job_type == 1 ? 1 : 0));

            if ($request->hasFile('resume')) $this->uploadResume($postFile, $createdPost, 'post-images');

            // notification to all users
            $user_ids = User::pluck('id')->toArray();
            $notification = Notification::create([
                'title' => 'Mustafai has created job post',
                'title_english' => 'Mustafai has created job post',
                'title_urdu' => 'مصطفائی نے نوکری کی پوسٹ بنائی ہے',
                'title_arabic' => 'أنشأ مصطفى حسين وظيفة',
                'module_id' => 37,
                'right_id' => 144,
                'ip' => request()->ip
            ]);

            $notification->users()->attach($user_ids, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin
        } else {
            if (!have_right('Edit-Posts')) access_denied();

            unset($request->action);
            $id = $request->id;
            $post = Post::find($id);

            $post->update([
                'post_type' => $request->post_type,
                'title_english' => $request->title_english,
                'title_urdu' => $request->title_urdu,
                'title_arabic' => $request->title_arabic,
                'description_english' => $request->description_english,
                'description_urdu' => $request->description_urdu,
                'description_arabic' => $request->description_arabic,
                'occupation' => $request->occupation,
                'experience' => $request->experience,
                'skills' => $request->skills,
                'status' => $request->status,
            ]);

            $updatedPost = Post::find($id);

            if ($request->status == 1 && $post->user_id != null) {
                // send notification

                $notification = Notification::create([
                    'title' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',
                    'title_english' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',
                    'title_urdu' => 'اپ لوڈ لوڈ کر دی گئی ہے ' . Str::words($updatedPost->title_english, 3, '...'),
                    'title_arabic' => 'تم تحميله ' . Str::words($updatedPost->title_english, 3, '...'),
                    'link' => route('user.specific-post', hashEncode($post->id)),
                    'module_id' => 36,
                    'right_id' => 135,
                    'ip' => request()->ip
                ]);

                $notification->users()->attach($updatedPost->user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]);
            }
            if ($request->hasFile('resume')) $this->uploadResume($request->file('resume'), $updatedPost, 'post-images');
        }
    }

    /**
     * store for blood post trait function
    */
    public function doBloodPost(Request $request)
    {
        $request->validate([
            'city' => 'required',
            'hospital' => 'required',
            'address' => 'required',
            'title_english' => 'required',
            'post_type' => 'required'
        ]);

        if ($request->action == 'add') {
            $postFile = $request->file('files');
            $createdPost = auth()->user()->posts()->create([
                'post_type' => 5,
                'title_english' => $request->title_english,
                'title_urdu' => $request->title_english,
                'title_arabic' => $request->title_english,
                'city' => $request->city,
                'hospital' => $request->hospital,
                'address' => $request->address,
            ]);
            if ($request->hasFile('files')) {
                // $files = $this->uploadFiles($request->files);
                foreach ($request->file('files') as $Key => $value) {
                    $fileName = 'post' . time() . rand(1, 100) . '.' . $value->extension();
                        $path=$value->storeAs(
                            'post-images',
                            $fileName,
                            's3'
                        );
                    PostFile::create([
                        'file' => $path,
                        'post_id' => $createdPost->id
                    ]);
                }
            }

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
                'title' => 'Mustafai has created blood post',
                'title_english' => 'Mustafai has created blood post',
                'title_urdu' => 'مصطفائی نے بلڈ پوسٹ بنائی ہے',
                'title_arabic' => 'قام مصطفى بإنشاء عمود دم',
                'module_id' => 38,
                'right_id' => 149,
                'ip' => request()->ip
            ]);

            $notification->users()->attach($eligibleIds, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin
        } else {
            if (!have_right('Edit-Posts')) access_denied();

            unset($request->action);
            $id = $request->id;
            $post = Post::find($id);

            $post->update([
                'post_type' => $request->post_type,
                'title_english' => $request->title_english,
                'title_urdu' => $request->title_urdu,
                'title_arabic' => $request->title_arabic,
                'city' => $request->city,
                'hospital' => $request->hospital,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            $updatedPost = Post::find($id);

            if ($request->status == 1 && $post->user_id != null) {
                // send notification

                $user = User::find($post->user_id);

                $notification = Notification::create([
                    'title' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',
                    'title_english' => Str::words($updatedPost->title_english, 3, '...') . ' has been uploaded',
                    'title_urdu' =>  'اپ لوڈ لوڈ کر دی گئی ہے' . Str::words($updatedPost->title_english, 3, '...'),
                    'title_arabic' => 'تم تحميله' . Str::words($updatedPost->title_english, 3, '...'),
                    'link' => route('user.specific-post', hashEncode($post->id)),
                    'module_id' => 36,
                    'right_id' => 135,
                    'ip' => request()->ip
                ]);

                $notification->users()->attach($updatedPost->user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]);

                // send notification to all users for blood
                $bloodPostNotification = Notification::create([
                    'title' => $user->user_name . ' has created blood post',
                    'title_english' => $user->user_name . ' has created blood post',
                    'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . 'نے بلڈ پوسٹ بنائی ہے ',
                    'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . 'قد خلقت آخر الدم ',
                    'link' => route('user.specific-post', hashEncode($post->id)),
                    'module_id' => 38,
                    'right_id' => 149,
                    'ip' => request()->ip
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

                // send notifications to user division area
                if ($user->division_id != null) {
                    $bloodPostNotification->users()->attach($eligibleIds, ['notification_type' => 0, 'from_id' => $user->id]);
                }
            }

            if ($request->has('old_files')) {
                $postImagesIds = $post->images->map(fn ($img) => $img->id)->toArray();
                $diffArray = array_diff($postImagesIds, $request->old_files);
                if ($diffArray) PostFile::whereIn('id', $diffArray)->delete();
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
    }

    /**
     * upload resume for Post trait
    */
    public function uploadResume($file, $createdPost, $dir)
    {
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();
        // if ($file->move(public_path($dir), $fileName)) {
            // $path =  $dir . '/' . $fileName;
            $path=$file->storeAs(
                $dir,
                $fileName,
                's3'
            );
            Post::where('id', $createdPost->id)->update(['resume' => $path]);
        // }
    }
}
