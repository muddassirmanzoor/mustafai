<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\Admin\Notification;
use App\Models\Chat\GroupChat;
use App\Models\Chat\ReadedGroupChat;
use Auth;
class NotificationController extends Controller
{
    /**
     *get notifications list api
    */
    public function notifications(Request $request)
    {
        $cols = array_merge(getQuery($request->lang, ['title']), ['notifications.id', 'notifications.created_at', 'notifications.link']);
        $notifications = auth()->user()->notifications()
            ->paginate($request->limit ?? 20, $cols)
            ->each(fn($q) => $q->profile = $q->pivot->notification_type == 1 ? ((!empty(User::find($q->pivot->from_id))) ? ((!empty(User::find($q->pivot->from_id))) ? User::find($q->pivot->from_id)->profile_image : '') : '') : ((!empty(Admin::find($q->pivot->from_id))) ? Admin::find($q->pivot->from_id)->profile : ''));

        return response()->json(['status'=>1,'message'=>'success','data'=>$notifications]);
    }
    /**
     *read one or all notification api
    */
    public function readUserNotifications(Request $request)
    {
        if($request->has('notificationId') && !empty($request->notificationId) && isset($request->notificationId)){
            auth()->user()->notifications()->updateExistingPivot($request->notificationId, ['is_read' => 1]);
        }
        else{
            auth()->user()->notifications()->update(['is_read' => 1]);
        }
        return response()->json(['status' => 200, 'data' =>['notification_count'=>auth()->user()->unreadNotifications()->count()]]);
    }
    /**
     *get notifications counter api
    */
    public function getNotificationCounter()
    {
        $data = [];
        $userId = Auth::user()->id;
        $groups = Auth::user()->myGroups;
        $data['notification'] =  auth()->user()->unreadNotifications()->count();
        $data['chat'] =  auth()->user()->unreadChat()->count();
        $data['friend_request'] =  auth()->user()->getFriendRequests()->count();
        // $data['contacts'] = Auth::user()->getContactCounters();
        $groupsCounts = [];
        $sum=0;
        foreach($groups as $key => $group)
        {
            $groupSms = GroupChat::where('group_id',$group->group->id)->where('from_id','<>',$userId)->pluck('id')->toArray();
            // ->whereNotIn('sms_id',$groupSms)
            $count = ReadedGroupChat::where('group_id',$group->group->id)->where('user_id',$userId)->count();
            $groupsCounts[$key]['id'] = $group->group->id;
            $groupsCounts[$key]['count'] = (count($groupSms)) - $count;
            $sum = $sum+((count($groupSms)) - $count);
        }
        // $data['group_counts'] = $groupsCounts;
        $data['total_group_counts']=$sum;
        return response()->json(['status' => 200, 'data' =>$data]);
        // echo json_encode($data);exit();
    }
    /**
     *push user notification
    */
    public function pushUserNotification(Request $request)
    {
        sendNotificationrToUser($request->userId,'your title','your notification body','type-here','key1','val1','key2','val2','key3','val3');

    }
}
