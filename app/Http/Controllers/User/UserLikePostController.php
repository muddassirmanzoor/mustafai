<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Posts\Post\Post;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class UserLikePostController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }

    /**
     *Like post
     */
    public function likePost(Request $request)
    {
        /* if user is login and has permission to like post */
        if (auth()->check()) {
            if (!have_permission_for_api(auth()->user(), 'Like-News-Feed-Posts')) {
                return response()->json(['status' => 0, 'message' => 'You do not have permission to like post']);
            }
        }
        /*find post against post id*/
        $post = Post::find(hashDecode($request->post_id));

        /* checks if like exist then delete */
        if ($likeExists = auth()->check() ? $this->isLikeExistAgainstUserThenDelete($post, auth()->user()->id) : $likeExists = $this->isLikeExistAgainstGuestUserThenDelete($post, $request->ip())) {
            if ($likeExists) {
                return response()->json(['status' => 204, 'message' => 'like deleted', 'data' => $post->likes()->count()]);
            }
        }

        /* create like */
        $post->likes()->create(['user_id' => auth()->check() ? auth()->user()->id : null, 'ip' => $request->ip(), 'like' => 1]);
        /* log activity */
        if (auth()->check()) auth()->user()->log('you like a post', route('user.specific-post', hashEncode($post->id)));
        /* generate notification */
        $this->generateLikeNotification($post);

        return response()->json(['status' => 200, 'message' => 'post liked', 'data' => $post->likes()->count()]);
    }

    /**
     *if like already exists on the same post with current user the delete
     */
    public function isLikeExistAgainstUserThenDelete(Post $post, int $userId)
    {
        $isExists =  $post->likes()->where('user_id', $userId)->exists();
        if ($isExists) {
            $post->likes()->where('user_id', $userId)->delete();
            return true;
        }

        return false;
    }

    /**
     *if like already exists on the same post with guest user the delete
     */
    public function isLikeExistAgainstGuestUserThenDelete($post, string $ip)
    {
        $isExists = $post->likes()->where('ip', $ip)->exists();
        if ($isExists) {
            $post->likes()->where('ip', $ip)->delete();
            return true;
        }

        return false;
    }

    /**
     *generate like notification after like action
     */
    public function generateLikeNotification($post)
    {
        $message = 'Your post is liked by ';
        $messageUrdu = 'نے آپ کی پوسٹ کو پسند کیا ہے ';
        $messageArabic = 'منشورك اعجب به ';

        $notification = Notification::create([
            'title' => !auth()->check() ? $message . 'guest user' : $message . auth()->user()->user_name,
            'title_english' => !auth()->check() ? 'guest user' . $message : $message . auth()->user()->user_name,
            'title_urdu' => !auth()->check() ? 'مہمان صارف' . $messageUrdu : ((auth()->user()->user_name_urdu != '' || auth()->user()->user_name_urdu != null) ? auth()->user()->user_name_urdu : auth()->user()->user_name) . $messageUrdu,
            'title_arabic' => !auth()->check() ? 'استخدام الضيف' . $messageArabic : ((auth()->user()->user_name_arabic != '' || auth()->user()->user_name_arabic != null) ? auth()->user()->user_name_arabic : auth()->user()->user_name) . $messageArabic,
            'link' => route('user.specific-post', hashEncode($post->id)),
            'module_id' => 36,
            'right_id' => 138,
            'ip' => request()->ip()
        ]);

        $toUser = $post->admin_id == null || $post->admin_id == '' ? User::find($post->user_id) : Admin::find($post->admin_id);

        //push nottification
        if(auth()->user()){
            $title=auth()->user()->full_name;
            $body=$message . auth()->user()->full_name;
            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $post->user_id)->pluck('fcm_token')->toArray();

            $data = [
                'type' => 'view-post-details',
                'data_id' => $post->id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($post->user_id,$title,$body,'view-post-details','post_id',$post->id,'key2','val2','key3','val3');
        }

        if ($toUser instanceof User) $toUser->notifications()->attach($notification->id, ['from_id' => auth()->check() ? auth()->user()->id : null]);
        if ($toUser instanceof Admin) $toUser->notifications()->attach($notification->id, ['admin_id' => $post->admin_id]);
    }
}
