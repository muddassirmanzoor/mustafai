<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\Comment\Comment;
use App\Models\Posts\Post\Post;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class UserCommentPostController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     *Comment on Post
    */
    public function commentPost(Request $request)
    {
        /* if user has no permission to comment on post */
        if (auth()->check()) {
                if (!have_permission_for_api(auth()->user(), 'Add-Comment-News-Feed-Posts')) {
                    return response()->json(['status' => 0, 'message' => 'You do not have permission to comment on post']);
                }
        }
        /* if comment has empty body */
        if($request->comment == '') {
            return response()->json(['status' => 204, 'data' => 'please write something']);
        }
        /* find post against post id */
        $post = Post::find(hashDecode($request->post_id));
        /* create comment */
        $comment = Comment::create(['user_id' => auth()->check() ? auth()->user()->id : null, 'post_id' => $post->id, 'ip' => $request->ip(), 'body' => $request->comment]);
        /* log activity */
        if (auth()->check()) auth()->user()->log('you commented on a post', route('user.specific-post', hashEncode($post->id)));
        /* generate notification */
        $this->generateNotification($post);

        return response()->json(['status' => 200, 'message' => 'comment posted', 'data' => $post->comments()->count(), 'comment' => $comment, 'commentId' => hashEncode($comment->id)]);
    }


    /**
     * @param Post $post
     * @return void
     */
    /**
     *Get notification after the comment
    */
    public function generateNotification(Post $post)
    {
        $message = ' commented on your post';
        $messageUrdu = 'نے آپ کی پوسٹ پر تبصرہ کیا ';
        $messageArabic = 'علق على رسالتك ';

        $fromUser = auth()->check() ? auth()->user() : null;
        $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

        $notification = Notification::create([
            'title' => ! auth()->check() ? 'guest user'.$message : $fromUser->user_name.$message,
            'title_english' => ! auth()->check() ? 'guest user'.$message : $fromUser->user_name.$message,
            'title_urdu' => ! auth()->check() ? $messageUrdu.'مہمان صارف' : (($fromUser->user_name_urdu != '' || $fromUser->user_name_urdu != null) ? $fromUser->user_name_urdu : $fromUser->user_name).$messageUrdu,
            'title_arabic' => ! auth()->check() ? $messageArabic.'استخدام الضيف' : (($fromUser->user_name_arabic != '' || $fromUser->user_name_arabic != null) ? $fromUser->user_name_arabic : $fromUser->user_name).$messageArabic,
            'link' => route('user.specific-post', hashEncode($post->id)),
            'module_id'=> 36,
            'right_id'=>138,
            'ip'=>request()->ip()
        ]);

        //push nottification
        $title=$fromUser->full_name;
        $body=$fromUser->full_name.$message;
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

        $data = [
            'type' => 'view-post-details',
            'data_id' => $post->id
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
        // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');

        if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => auth()->check() ? $fromUser->id : null]);
        if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     *Delete Comment
    */
    public function deleteComment(Request $request)
    {
        $comment = Comment::find(hashDecode($request->comment_id));
        $comment->delete();

        return response()->json(['status' => 200, 'message' => 'comment deleted']);
    }
}
