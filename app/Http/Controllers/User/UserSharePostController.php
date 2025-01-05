<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Posts\Post\Post;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserSharePostController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    /**
     *Sharing News Feed Posts
    */
    public function sharePost(Request $request)
    {
        if (auth()->check()) {
            if (!have_permission_for_api(auth()->user(), 'Share-News-Feed-Posts')) {
                return response()->json(['status' => 0, 'message' => 'You do not have permission to share post']);
            }

            $post = Post::find(hashDecode($request->post_id));

            // replicate (copy) post
            $sharedPost = $post->replicate();
            $sharedPost->created_at = Carbon::now();
            $sharedPost->updated_at = Carbon::now();
            $sharedPost->share_id = auth()->user()->id;
            $sharedPost->save();
            
            // copy post files
            foreach ($post->files as $file) {
                $sharedFile = $file->replicate();
                $sharedFile->post_id = $sharedPost->id;
                $sharedFile->save();
            }

            /* log activity */
            auth()->user()->log('you shared a post', route('user.specific-post', hashEncode($post->id)));

            //push nottification
            $title=auth()->user()->full_name;
            $body=auth()->user()->full_name.' shared your post';
            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

            $data = [
                'type' => 'view-post-details',
                'data_id' => $post->id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

            return response()->json(['status' => 200, 'message' => 'post shared']);
        }
    }
}
