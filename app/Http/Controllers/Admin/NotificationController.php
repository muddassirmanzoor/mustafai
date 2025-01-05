<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Notification;
use App\Models\Chat\GroupChat;
use App\Models\Chat\ReadedGroupChat;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Route;

class NotificationController extends Controller
{
    /**
     * read single noification for admin.
    */
    public function readNotification(Request $request)
    {
        $admin = \App\Models\Admin\Admin::find(1);
        $admin->notifications()->updateExistingPivot($request->id, ['is_read' => 1]);

        return response()->json(['status' => 200, 'data' => auth()->user()->unreadNotifications()->count()]);
    }

    /**
     * read multiple noification for admin.
    */
    public function readNotifications()
    {
        $admin = \App\Models\Admin\Admin::find(1);
        $admin->notifications()->update(['is_read' => 1]);

        return response()->json(['status' => 200, 'data' => auth()->user()->unreadNotifications()->count()]);
    }

    /**
     * read single noification for user.
    */
    public function readUserNotification(Request $request)
    {
        auth()->user()->notifications()->updateExistingPivot($request->id, ['is_read' => 1]);

        return response()->json(['status' => 200, 'data' => auth()->user()->unreadNotifications()->count()]);
    }

    /**
     * read multiple noification for user.
    */
    public function readUserNotifications()
    {
        auth()->user()->notifications()->update(['is_read' => 1]);

        return response()->json(['status' => 200, 'data' => auth()->user()->unreadNotifications()->count()]);
    }

    /**
     * notifications counter
    */
    public function getNotificationCounter()
    {
        $data = [];
        $userId = Auth::user()->id;

        $groups = Auth::user()->myGroups;
        $groupsCounts = 0;
        foreach ($groups as $key => $group) {
            $grpSMS = GroupChat::where('group_id', $group->group->id)->where('from_id', '<>', $userId)->pluck('id')->toArray();
            $groupCount = ReadedGroupChat::where('group_id', $group->group->id)->where('user_id', $userId)->count();
            $groupsCounts += (count($grpSMS)) - $groupCount;
        }

        $data['notification'] =  auth()->user()->unreadNotifications()->count();
        $data['chat'] =  auth()->user()->unreadChat()->count() + $groupsCounts;
        $data['friend_request'] =  auth()->user()->getFriendRequests()->count();

        if (isset($_GET['urlAction']) && $_GET['urlAction'] == 'chats') {
            $data['contacts'] = Auth::user()->getContactCounters();
            $groupsCounts = [];
            foreach ($groups as $key => $group) {
                $groupSms = GroupChat::where('group_id', $group->group->id)->where('from_id', '<>', $userId)->pluck('id')->toArray();
                // ->whereNotIn('sms_id',$groupSms)
                $count = ReadedGroupChat::where('group_id', $group->group->id)->where('user_id', $userId)->count();

                $groupsCounts[$key]['id'] = $group->group->id;
                $groupsCounts[$key]['count'] = (count($groupSms)) - $count;
            }
            $data['group_counts'] = $groupsCounts;
        }

        echo json_encode($data);
        exit();
    }

    /**
     * header notifications
    */
    public function headerNotifications(Request $request)
    {
        if ($request->ajax()) {
            $html = view('user.partials.header-notifications-partial')->render();
            return response()->json(['status' => 200, 'data' => $html]);
        }
    }
}
