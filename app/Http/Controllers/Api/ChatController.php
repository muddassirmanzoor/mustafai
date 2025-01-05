<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactsResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Chat\Chat;
use App\Models\Chat\GroupChat;
use App\Models\Chat\ReadedGroupChat;
use App\Models\Chat\Contact;
use App\Models\Chat\Group;
use App\Models\Chat\GroupUser;
use App\Services\FirebaseNotificationService;
use DB;

class ChatController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    /**
     * get contacts of users
     */
    public function getContacts(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 0,
                'message' => 'validation fails',
                'data' => $validator->errors()->toArray()
            ]);

        // Retrieve the validated input...
        $validated = $validator->validated();

        $user = User::find($validated['user_id']);

        $myID = $request->user_id;
        $contactIds = [];
        $contacts = Contact::where(function ($query) use ($myID) {
            $query->where('user_1', $myID)->orWhere('user_2', $myID);
        })
            ->where('status', 1)
            ->get(['user_1', 'user_2']);
        foreach ($contacts as $contact) {
            if ($contact->user_1 == $myID) {
                $contactIds[] = $contact->user_2;
            } else {
                $contactIds[] = $contact->user_1;
            }
        }
        $uniqueContactIds = array_unique($contactIds);
        $contacts = User::select('id', 'profile_image', 'full_name', 'user_name', 'is_public', 'created_at')
            ->whereIn('id', $uniqueContactIds)
            ->limit($request->limit ?? 10)
            ->offset($request->offset ?? 0)
            ->latest()
            ->get()
            ->each(function ($item) {
                $item->unread = Chat::where(['from_id' => $item->id, 'to_id' => auth()->user()->id, 'status' => 0, 'deleted_by' => null])->count();
            });

        // $contacts = User::select('users.id', 'users.profile_image', 'users.user_name', 'users.user_name', 'users.created_at')->join('contacts', function ($join) {
        //             $join->on('contacts.user_1', '=', 'users.id')
        //                 ->orOn('contacts.user_2', '=', 'users.id');
        //         })
        //     ->whereRaw('(`contacts`.`user_1` = ' . $myID . ' and `contacts`.`status` = 1) OR (`contacts`.`user_2` = ' . $myID . ' and `contacts`.`status` = 1)')
        //     ->where('users.id', '!=', $myID)
        //     ->limit($request->limit ?? 10)
        //     ->offset($request->offset ?? 0)
        //     ->latest()
        //     ->get();

        return new ContactsResource($contacts);
    }

    /**
     * get group contact admin
     */
    public function getChatGroupHead(Request $request)
    {
        $input = $request->all();

        $userId = $input['userID'];

        $lastGroupId = $input['lastGroupId'];
        $limit = $input['limit'];

        $groups = GroupUser::with(['group' => function ($q) use ($lastGroupId) {
            if ($lastGroupId) {
                $q->where('groups.id', '<', $lastGroupId);
            }
        }])
            ->whereUserId($input['userID'])
            ->limit($limit)
            ->orderBy('group_id', 'DESC')
            ->get()->toArray();

        $result = [];


        foreach ($groups as $key => $group) {
            $group_id = Group::find($group['group_id']);
            $admin = User::find($group['group']['created_by']);

            $result[$key]['groupId'] = $group['group_id'];
            $result[$key]['groupName'] = $group['group']['name'];
            $result[$key]['groupDescription'] = $group['group']['description'];
            $result[$key]['groupImage'] =  getS3File((!empty($group['group']['icon'])) ? $group['group']['icon'] : 'images/avatar.png');
            $result[$key]['adminName'] = (!empty($admin)) ? $admin->full_name : "";
            $result[$key]['adminID'] = (!empty($admin)) ? $admin->id : "";
            $result[$key]['users'] = $group_id->groupUsers->toArray();

            $grpSMS = GroupChat::where('group_id', $group['group_id'])->where('from_id', '<>', $userId)->pluck('id')->toArray();
            $groupCount = ReadedGroupChat::where('group_id', $group['group_id'])->where('user_id', $userId)->count();
            $result[$key]['unread'] = (count($grpSMS)) - $groupCount;
        }
        return response()->json(['status' => 1, 'message' => 'success', 'data' => $result]);
        // echo json_encode($result);die();
    }
    /**
     * get messages for contacts and groups
     */
    public function getMessages(Request $request)
    {

        $input = $request->all();

        $id = $input['actionID'];
        $userId = $input['userID'];
        $limit = $input['limit'];

        $lastIds = (isset($input['lastMessageIds']) && $input['lastMessageIds']) ? $input['lastMessageIds'] : 0;

        if ($input['type'] == 'friend') {
            $modal = new Chat();
            $chats = $modal->getFriendConversation($id, $lastIds, $userId, $limit);

            $result = [];

            foreach ($chats as $key => $chat) {
                $from = User::find($chat['from_id']);
                $result[$key]['id'] = $chat['id'];
                $result[$key]['messageType'] = $chat['type'];
                $result[$key]['message'] = $chat['message'];
                $result[$key]['message'] = $chat['message'];
                $result[$key]['isDeleted'] = $chat['is_deleted'];
                $result[$key]['deletedBy'] = json_decode($chat['deleted_by']);
                $result[$key]['isMine'] = ($chat['from_id'] == $userId) ? 'Yes' : 'No';
                $result[$key]['fromName'] = (!empty($from)) ? $from->full_name : "";
                $result[$key]['fromImage'] = getConGroImg('contact', $from->id);
                $result[$key]['dateTime'] = $chat->created_at->diffForHumans();
            }

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $result]);
        } else if ($input['type'] == 'group') {
            // if ($lastIds) {
            //     $lastIds = explode(',', $lastIds);
            //     $chats = GroupChat::where('group_id', $id)->whereNotIn('id', $lastIds)->limit($limit)->orderby('id', 'DESC')->get();
            // } else {
            //     $chats = GroupChat::where('group_id', $id)->orderby('id', 'DESC')->limit($limit)->get();
            // }
            $lastId = $input['lastMessageIds'];

            $where = '( find_in_set(' . $userId . ',deleted_by) is null or find_in_set(' . $userId . ',deleted_by) = 0 )';

            $chats = GroupChat::where('group_id', $id)->where('id', '>', $lastId)->whereRaw($where)->orderby('id', 'DESC')->get();

            $result = [];
            $readArray = [];
            $groupReadModal = new ReadedGroupChat();
            foreach ($chats as $key => $chat) {
                $from = User::find($chat['from_id']);

                $result[$key]['messageType'] = $chat['type'];
                $result[$key]['id'] = $chat['id'];
                $result[$key]['message'] = $chat['message'];
                $result[$key]['isDeleted'] = $chat['is_deleted'];
                $result[$key]['deletedBy'] = json_decode($chat['deleted_by']);
                $result[$key]['isMine'] = ($chat['from_id'] == $userId) ? 'Yes' : 'No';
                $result[$key]['fromId'] = (!empty($from)) ? $from->id : "";
                $result[$key]['fromName'] = (!empty($from)) ? $from->full_name : "";
                $result[$key]['fromImage'] = getConGroImg('contact', $from->id);
                $result[$key]['dateTime'] = $chat->created_at->diffForHumans();

                if ($chat['from_id'] == $userId) {
                    continue;
                }
                $readArray['group_id'] = (int) $id;
                $readArray['sms_id'] = $chat['id'];
                $readArray['user_id'] = $userId;
                $readArray['created_at'] = date('Y-m-d h:i:s');
                $readArray['updated_at'] = date('Y-m-d h:i:s');
                $already = ReadedGroupChat::where(['group_id' => (int) $id, 'sms_id' => $chat['id'], 'user_id' => $userId])->first();
                if (empty($already)) {
                    $groupReadModal->create($readArray);
                }
            }

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $result]);
        }
    }
    /**
     * send friend request api
     */
    public function sendFriendRequest(Request $request)
    {
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $request->user_id)->pluck('fcm_token')->toArray();

        if (request()->lang == 'english') {
            $acceptReqMsg = 'Friend Request sent successfully!';
            $unfriendReqMsg = 'Unfriend successfully!';
        } else {
            $acceptReqMsg = '!دوستی کی درخواست کامیابی کے ساتھ بھیج دی گئی';
            $unfriendReqMsg = '!کامیابی سے دوستی ختم ہو گئی';
        }
        if ($request->has('unfriend') && isset($request->unfriend) && !empty($request->unfriend)) {
            $contact = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $request->user_id
            ])->orWhere('user_1', $request->user_id)->where('user_2', auth()->user()->id)->first();

            if ($contact->exists()) {
                $contact->update(['status' => 2]);
                return response()->json(['status' => 1, 'message' => $unfriendReqMsg]);
            }
        }

        if ($request->has('again_friend') && isset($request->again_friend) && !empty($request->again_friend)) {
            $contact = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $request->user_id
            ])->orWhere('user_1', $request->user_id)->where('user_2', auth()->user()->id)->first();

            $contact->update(['user_1' => auth()->user()->id, 'user_2' => $request->user_id, 'status' => 0]);

            //push nottification
            $title = auth()->user()->full_name;
            $user = User::find($request->user_id);

            if($user->lang == 'english') {
                $body=auth()->user()->full_name.' sent you a friend request';
            }else{
                $body = auth()->user()->full_name.' نے آپ کو ایک دوست کی درخواست بھیجی ہے';
            }

            $data = [
                'type' => 'send-friend-request',
                'data_id' => $request->user_id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($request->user_id,$title,$body,'send-friend-request','user_id',$request->user_id,'key2','val2','key3','val3');

            return response()->json(['status' => 1, 'message' => $acceptReqMsg]);
        }

        Contact::updateOrCreate(['user_1' => auth()->user()->id, 'user_2' => $request->user_id], [
            'user_1' => auth()->user()->id,
            'user_2' => $request->user_id
        ]);

        //push nottification
        $title = auth()->user()->full_name;
        $user = User::find($request->user_id);

        if($user->lang == 'english') {
            $body=auth()->user()->full_name.' sent you a friend request';
        }else{
            $body = auth()->user()->full_name.' نے آپ کو ایک دوست کی درخواست بھیجی ہے';
        }

        $data = [
            'type' => 'send-friend-request',
            'data_id' => auth()->user()->user_name,
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
        // sendNotificationrToUser($request->user_id,$title,$body,'send-friend-request','user_id',auth()->user()->user_name,'key2','val2','key3','val3');

        return response()->json(['status' => 1, 'message' => $acceptReqMsg]);
    }
    /**
     * send friend request api
     */
    public function friendRequests(Request $request)
    {
        $input = $request->all();

        $user = User::find($input['user_id']);

        $requests = $user->getFriendRequestApi($input['lastUserID'], $input['limit']);

        $response = [];

        foreach ($requests as $key => $frequest) {
            $response[$key]['request_id'] = $frequest['conId'];
            $response[$key]['userId'] = $frequest['id'];
            $response[$key]['username'] = $frequest['full_name'];
            $response[$key]['userImage'] = getConGroImg('contact', $frequest['id']);
        }

        return response()->json($response);
    }
    /**
     * get available contacts
     */
    public function availableContacts(Request $request)
    {
        $input = $request->all();

        $user = User::find($input['user_id']);

        $contacts = $user->apiGetAvailableUsersForFR($input['lastUserID'], $input['limit']);
        $fr_contacts = $user->getYourSentRequests();

        $response = [];

        foreach ($contacts as $key => $contact) {
            $response[$key]['userId'] = $contact['id'];
            $response[$key]['username'] = $contact['full_name'];
            $response[$key]['userImage'] = getConGroImg('contact', $contact['id']);
            // Check if the contact ID exists in fr_contact_ids array
            $response[$key]['fr_sent'] = in_array($contact['id'], $fr_contacts) ? 1 : 0;
        }

        return response()->json(['status' => 1, 'message' => 'success', 'data' => $response]);
        //    echo json_encode($response);die();
    }
    /**
     * get response To friend request
     */
    public function responseToFriendRequest(Request $request)
    {
        $input = $request->all();

        $contact = Contact::find($input['request_id']);
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $request->user_id)->pluck('fcm_token')->toArray();

        $response = [];
        if (!empty($contact)) {
            if ($input['response']) {
                $contact->update(['status' => $input['response']]);
                $response['status'] = 1;
                $response['message'] = 'You both are friend now.';
                $title = auth()->user()->full_name;
                $user = User::find($request->user_id);

                if($user->lang == 'english') {
                    $body=auth()->user()->full_name.' accept your friend request';
                }else{
                    $body = auth()->user()->full_name.' نے آپ کی دوست کی درخواست قبول کر لی ہے';
                }

                $data = [
                    'type' => 'send-friend-request',
                    'data_id' => $request->user_id,
                ];
                $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
                // sendNotificationrToUser($request->user_id,$title,$body,'send-friend-request','user_id',$request->user_id,'key2','val2','key3','val3');
            } else {
                $contact->delete();
                $response['status'] = 1;
                $response['message'] = 'Friend Request Removed.';
            }
        } else {
            $response['status'] = 0;
            $response['message'] = 'Contact not found.';
        }

        echo json_encode($response);
        die();
    }
    /**
     *send message api
     */
    public function sendMessage(Request $request)
    {
        $input = $request->all();

        $userId = $input['user_id'];

        $data = [];

        $data['from_id'] = $userId;

        if ($input['message_type'] == 2) {
            // $path = 'voices/user-voices';
            // $voiceName = 'voice' . time() . '.' . $request->file->extension();
            // if ($request->file->move(public_path($path), $voiceName)) {
            //     $path = $path . "/" . $voiceName;
            // }
            $voiceName = 'voice' . time() . '.' . $request->file->extension();
            $path = $request->file->storeAs(
                'voices/user-voices',
                $voiceName,
                's3'
            );
            $data['message'] = $path;
            $data['type'] = 2;
        } else if ($input['message_type'] == 3) {
            // $path = 'attachments/user-attachments';
            // $attachName = 'attachment' . time() . '.' . $request->file->extension();
            // if ($request->file->move(public_path($path), $attachName)) {
            //     $path = $path . "/" . $attachName;
            // }
            $attachName = 'attachment' . time() . '.' . $request->file->extension(); // a unique file name
            $path = $request->file->storeAs(
                'storage/attachments/user-attachments',
                $attachName,
                's3'
            );
            $data['message'] = $path;
            $data['type'] = 3;
        } else {
            $data['message'] = $input['message'];
        }

        $isSend = 0;
        if ($input['request_type'] == 'friend') {
            $data['to_id'] = $input['action_id'];
            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $data['to_id'])->pluck('fcm_token')->toArray();
            $modal = new Chat();
            $modal->fill($data);
            $isSend = $modal->save();

            //push contact nottification
            $fromUser = User::where('id', $data['from_id'])->first();
            $title = $fromUser->full_name;
            $user = User::find($data['to_id']);

            if($user->lang == 'english') {
                $body=auth()->user()->full_name.' sent you a message';
            }else{
                $body = auth()->user()->full_name.' نے آپ کو ایک پیغام بھیجا ہے';
            }
            $data = [
                'type' => 'friend',
                'data_id' => $data['from_id']
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($data['to_id'],$title,$body,'friend','id',$data['from_id'],'image',$fromUser->profile_image,'name',$fromUser->full_name);
        } else if ($input['request_type'] == 'group') {
            $data['group_id'] = $input['action_id'];
            $request->merge(['id' => $input['action_id']]);
            $modal = new GroupChat();
            $modal->fill($data);
            $isSend = $modal->save();

            //push group notifications
            $group = Group::where('id', $input['action_id'])->first();
            $groupUsers = GroupUser::where('group_id', $input['action_id'])->where('user_id', '!=', $data['from_id'])->pluck('user_id');
            $fromUser = User::where('id', $data['from_id'])->pluck('full_name')->first();
            $admin = User::find($group->created_by);
            $body = $fromUser . ' sent a message';
            $firebaseToken = User::whereNotNull('fcm_token')->whereIn('id', $groupUsers)->pluck('fcm_token')->toArray();

            $title = $group->name;
            $data = [
                'type' => 'group',
                'data_id' => $group->id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToGroupUser($groupUsers, $group->name, $body, 'group', 'adminName', $admin->full_name, 'image', getS3File($group->icon), 'id', $input['action_id'], 'name', $group->name);
        }

        $response = [];
        if ($isSend) {
            $response['status'] = 1;
            $response['message'] = 'Message Sent Successfully.';
        } else {
            $response['status'] = 0;
            $response['message'] = 'Semothing went wrong.';
        }

        return response()->json($response);
    }
    /**
     *create group api
     */
    function createGroup(Request $request)
    {
        $input = $request->all();
        $participants = $input['participants'];
        $participants = explode(',', $participants);
        $userId = $input['userId'];
        array_push($participants, $userId);

        $id = $input['group_id'];

        $data = [];
        if (isset($input['groupImage'])) {
            $path = uploadS3File($request, 'images/group-icons', 'groupImage', 'group', $filename = null);
            $data['icon'] = $path;
            // $path = 'images/group-icons';
            // $imageName = 'group' . uniqid() . '.' . $request->groupImage->extension();
            // if ($request->groupImage->move(public_path($path), $imageName)) {
            //     $data['icon'] = $path . '/' . $imageName;
            // }
        }

        $data['name'] = $input['groupName'];
        $data['description'] = $input['groupDescription'];
        $data['created_by'] = $userId;
        $data['status'] = 1;

        $response = [];

        if ($id) {
            $message = 'Group Updated Successfully.';
            $modal = Group::find($id);
        } else {
            $message = 'Group Created Successfully.';
            $modal = new Group();
        }

        $modal->fill($data);
        $isSave = $modal->save();


        if ($isSave) {
            $data = [];
            foreach ($participants as $key => $user) {
                $data[$key]['group_id'] = $modal->id;
                $data[$key]['user_id'] = (int) $user;
                $data[$key]['is_admin'] = (!$key) ? 0 : 1;
                $data[$key]['status'] = 1;
                $data[$key]['created_at'] = date('Y-m-d h:i:s');
                $data[$key]['updated_at'] = date('Y-m-d h:i:s');
            }

            if ($id) {
                $GroupUsermodal = GroupUser::where('group_id', $id)->delete();
            }

            GroupUser::insert($data);

            $response['status'] = 1;
            $response['message'] = $message;
            $response['data'] = ['groupId' => $modal->id];
        } else {
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';
        }
        return response()->json(['status' => 1, 'message' => 'success', 'data' => $response]);
        // echo json_encode($response);
    }

    /**
     *Deletes a chat group.
     */
    public function deleteGroup(Request $request)
    {
        $input = $request->all();

        $groupID = (isset($input['groupID'])) ? $input['groupID'] : 0;

        $userID = Auth::user()->id;

        $response = [];

        if ($groupID) {
            $group = Group::find($groupID);

            if (!empty($group)) {
                if ($userID == $group->created_by) {
                    $icon = $group->icon;
                    deleteS3File($icon);
                    // File::delete(asset($icon));

                    $group->groupUsers()->delete();
                    $group->groupSms()->delete();
                    $group->delete();

                    $response['status'] = 1;
                    $response['message'] = trans('app.group-deleted');
                } else {
                    $response['status'] = 0;
                    $response['message'] = trans('app.group-delete-not-authorize');
                }
            } else {
                $response['status'] = 0;
                $response['message'] = trans('app.group-not-found');
            }
        } else {
            $response['status'] = 0;
            $response['message'] = trans('app.group-not-found');
        }

        return response()->json(['status' => 1, 'message' => 'success', 'data' => $response]);
    }

    /**
     *Deletes a conversation with a specific user or group.
     */
    public function deleteConversation(Request $request)
    {
        $input = $request->all();
        $myID = Auth::user()->id;
        $actionID = $input['actionID'];
        $type = $input['type'];
        $response = [];

        if ($type == 'contact') {
            $where = '(`chats`.`from_id` = ' . $myID . ' and `chats`.`to_id` = ' . $actionID . ') OR (`chats`.`to_id` = ' . $myID . ' and `chats`.`from_id` = ' . $actionID . ')';

            $chats = Chat::select('*')->whereRaw($where)->get();

            foreach ($chats as $chat) {
                $deleteBy = (empty($chat['deleted_by'])) ? $myID : $chat['deleted_by'] . ',' . $myID;
                $chat->update(['deleted_by' => $deleteBy]);
            }

            if (count($chats)) {
                $response['status'] = 1;
                $response['message'] = __('app.deleteed-chat');
            } else {
                $response['status'] = 0;
                $response['message'] = __('app.no-message');
            }
        } else {
            $chats = GroupChat::where(['group_id' => $actionID])->get();

            foreach ($chats as $chat) {
                $deleteBy = (empty($chat['deleted_by'])) ? $myID : $chat['deleted_by'] . ',' . $myID;
                $chat->update(['deleted_by' => $deleteBy]);
            }

            if (!empty($chats)) {
                $response['status'] = 1;
                $response['message'] = __('app.deleteed-chat');
            } else {
                $response['status'] = 0;
                $response['message'] = __('app.no-message');
            }
        }

        return response()->json(['status' => 1, 'message' => 'success', 'data' => $response]);
    }

    /**
     *Deletes a specific message with a specific user or group.
     */
    public function deleteSelectedMessage(Request $request)
    {
        $messageIds = $request->input('message_ids');
        $bit = $request->input('is_deleted');
        $deleted_by = $request->input('delete_by');
        $is_group_type = $request->input('message_type');

        if ($is_group_type == 'friend') {
            $chat = Chat::whereIn('id', $messageIds)->first();

            if ($chat) {
                $existingDeletedBy = $chat->deleted_by;
                $existingDeletedByArray = json_decode($existingDeletedBy, true) ?: [];
                $newDeletedByArray = array_merge($existingDeletedByArray, [$deleted_by]);

                DB::table('chats')
                    ->whereIn('id', $messageIds)
                    ->update([
                        'is_deleted' => $bit,
                        'deleted_by' => json_encode($newDeletedByArray)
                    ]);
            }
        } else {

            $chat = GroupChat::whereIn('id', $messageIds)->first();
            if ($chat) {
                $existingDeletedBy = $chat->deleted_by;
                $existingDeletedByArray = json_decode($existingDeletedBy, true) ?: [];
                $newDeletedByArray = array_merge($existingDeletedByArray, [$deleted_by]);

                GroupChat::whereIn('id', $messageIds)
                    ->update(['is_deleted' => $bit, 'deleted_by' => json_encode($newDeletedByArray)]);
            }
        }
        return response()->json(['message' => 'Deleted Message Successfully']);
    }
}
