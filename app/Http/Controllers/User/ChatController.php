<?php

namespace App\Http\Controllers\User;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Notification;
use App\Models\Chat\Chat;
use App\Models\Chat\Contact;
use App\Models\Chat\Group;
use App\Models\Chat\GroupChat;
use App\Models\Chat\GroupUser;
use App\Models\Chat\ReadedGroupChat;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Auth;
use File;
use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Storage;
use Session;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    /**
     *Display chat screen with user data.
     */
    public function getChatScreen(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            $type = $request->type;
        } else {
            $id = 'ss';
            $type = '';
        }
        $data = [];
        $data['contacts'] = Auth::user()->getFriends();
        $data['availableUsers'] = Auth::user()->getAvailableUsersForFR();
        $data['groups'] = Auth::user()->myGroups;
        $data['c_id'] = $id;
        $data['type'] = $type;

        return view('user.chat', $data);
    }

    /**
     *Creates a contact group and handles friend requests.
     */
    public function createContactGroup(Request $request)
    {
        $input = $request->all();

        $userId = Auth::user()->id;
        $response = [];

        if ($input['type'] == 1) {
            $data = [];

            foreach ($input['users'] as $key => $user) {

                $already = Contact::whereRaw('(user_1=' . $userId . ' and user_2=' . (int) $user . ') or (user_2=' . $userId . ' and user_1=' . (int) $user . ') and (status=0 or status=2)')->first();

                if (!empty($already)) {
                    $already->update(['status' => 0]);
                } else {
                    $data[$key]['user_1'] = $userId;
                    $data[$key]['user_2'] = (int) $user;
                    $data[$key]['status'] = 0;
                    $data[$key]['created_at'] = date('Y-m-d h:i:s');
                    $data[$key]['updated_at'] = date('Y-m-d h:i:s');
                }
            }

            if (count($data)) {
                Contact::insert($data);
            }

            $response['status'] = 1;
            $response['type'] = 1;
            $response['message'] = __('app.friend-req-sent');
        } else if ($input['type'] == 2) {
            $id = $input['group_id'];

            $data = [];
            if (isset($input['icon'])) {
                $path = uploadS3File($request, 'images/group-icons', 'icon', 'icon', $filename = null);
                $data['icon'] = $path;
                // $path = 'images/group-icons';
                // $imageName = 'group' . uniqid() . '.' . $request->icon->extension();
                // if ($request->icon->move(public_path($path), $imageName)) {
                //     $data['icon'] = $imgPath = $path . '/' . $imageName;
                // }
            }

            $data['name'] = $input['name'];
            $data['description'] = $input['description'];
            $data['created_by'] = $userId;
            $data['status'] = 1;

            if ($id) {
                $modal = Group::find($id);
            } else {
                $modal = new Group();
            }

            $modal->fill($data);
            $modal->save();

            $html = '';
            if (!empty($modal)) {
                $html = '<li id="group_' . $modal->id . '" class="read d-flex align-items-start contacts-groups-list" onclick="openChatBox("group",' . $modal->id . ')">
                            <div class="d-flex align-items-center flex-fill">
                                <figure class="mb-0 me-3 user-img d-flex">
                                    <img src="' . getConGroImg('group', $modal->id) . '" alt="" class="img-fluid" />
                                </figure>
                                <div class="d-flex flex-column flex-fill chat-headlines">
                                    <h6>' . $data['name'] . '</h6>
                                    <p>' . $data['description'] . '</p>
                                </div>
                            </div>';

                $html .= '<div class="d-flex flex-column">
                                    <div class="d-flex">
                                        <span class="sms-counts count-group" id="group-count-' . $modal->id . '" style="display: none;">0</span>
                                    </div>
                                    <div class="d-flex justify-content-center chat-menu-drop">
                                        <div class="dropdown">
                                            <button class="dropdown-toggle" type="button" id="drop-group-down-' . $modal->id . '" data-bs-toggle="dropdown" aria-expanded="false" onclick="openActionsSection($(this))">
                                                <strong>...</strong>
                                            </button>
                                            <ul class="chat-drop-menu dropdown-menu dropdown-menu-actions" aria-labelledby="drop-group-down-' . $modal->id . '">';

                if (Auth::user()->id == $modal->created_by) {
                    $html .= '<li>
                            <a class="dropdown-item" href="javsscript:void(0)" onclick="addContactGroup($(this),' . $modal->id . ')">' . trans('app.edit-group') . '</a>
                        </li>';
                }

                $html .= '<li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="deleteConversation(' . $modal->id . ',"group")">' . trans('app.delete-conversation') . '</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>';

                $data = [];
                array_push($input['participants'], $userId);
                foreach ($input['participants'] as $key => $user) {
                    $data[$key]['group_id'] = $modal->id;
                    $data[$key]['user_id'] = (int) $user;
                    $data[$key]['is_admin'] = (!$key) ? 1 : 0;
                    $data[$key]['status'] = 1;
                    $data[$key]['created_at'] = date('Y-m-d h:i:s');
                    $data[$key]['updated_at'] = date('Y-m-d h:i:s');
                }

                if ($id) {
                    $modal = GroupUser::where('group_id', $id)->delete();
                }

                GroupUser::insert($data);
            }

            $response['status'] = 1;
            $response['html'] = $html;
            $response['type'] = 2;
            if ($id) {
                $response['message'] = __('app.group-updated');
                $response['groupID'] = $id;
            } else {
                $response['message'] = __('app.group-created');
                $response['groupID'] = 0;
            }
        }

        $response['users'] = Auth::user()->getAvailableUsersForFR()->toArray();
        $response['groups'] = Auth::user()->myGroups;
        echo json_encode($response);
        exit();
    }

    /**
     *Retrieves friend requests for the user.
     */
    public function getFriendRequests()
    {
        $data = [];
        $data['requests'] = Auth::user()->getFriendRequests();
        return view('user.friend-requests', $data);
    }

    /**
     *Responds to a friend request (accepts or denies).
     */
    public function responseRequest(Request $request)
    {
        $input = $request->all();
        $requestId = $input['id'];

        $modal = Contact::find($requestId);
        $toUser = User::find($modal->user_1);

        $response = [];

        if ($input['type']) {
            $modal->update(['status' => 1]);
            $response['status'] = 1;
            $response['message'] = __('app.req-accepted');
            $this->sendNotification($toUser, 'accepted');
        } else {
            $modal->delete();
            $response['status'] = 1;
            $response['message'] = __('app.req-denied');
            $this->sendNotification($toUser, 'rejected');
        }

        echo json_encode($response);
        exit();
    }

    /**
     *Retrieves chat messages for a specific user or group.
     */
    public function getChats(Request $request)
    {
        $input = $request->all();
        $data = [];
        $response = [];

        $id = $input['id'];
        $userId = Auth::user()->id;

        $response['notification_contacts'] = Auth::user()->getContactCounters();
        $groups = Auth::user()->myGroups;
        $groupsCounts = [];
        foreach ($groups as $key => $group) {
            $groupSms = GroupChat::where('group_id', $group->group->id)->where('from_id', '<>', $userId)->pluck('id')->toArray();
            // ->whereNotIn('sms_id',$groupSms)
            $count = ReadedGroupChat::where('group_id', $group->group->id)->where('user_id', $userId)->count();

            $groupsCounts[$key]['id'] = $group->group->id;
            $groupsCounts[$key]['count'] = (count($groupSms)) - $count;
        }
        $response['notification_groups'] = $groupsCounts;

        if ($input['type'] == 'friend') {
            $modal = new Chat();

            $lastMessage = $input['last_message'];
            // $chats = $modal->getFriendConversation($id, $lastId);
            $chats = $modal->getFriendChat($id, $lastMessage);

            if (count($chats)) {
                $lastMessage = $chats[count($chats) - 1]['id'];
                // session()->put('last_message_id', $chats[count($chats)-1]['id']);
            }

            $data['chats'] = $chats;
            $data['sender'] = $userId;
            $data['reciever'] = $id;
            $data['group_id'] = '';
            $data['type'] = $input['type'];
            $data['last_id'] = $lastMessage;

            // dd($data['chats']);

            $html = (string) View('user.partials.chat-box-partial', $data);

            // $formHtml = '';
            // $contact = Contact::whereRaw('(user_1=' . $id . ' and user_2=' . $userId . ') or ((user_2=' . $id . ' and user_1=' . $userId . '))')->first();

            // $conStatus = $contact['status'];

            // if (!empty($contact) && $conStatus == 1) {
            //     $formHtml = (string) View('user.partials.chat-form-partial', $data);
            // }
            // $response['form_html'] = $formHtml;

            $response['req_type_id'] = 'contact_' . $id;
            $response['html'] = $html;
            $response['readed'] = Chat::where(['from_id' => $userId, 'to_id' => $id, 'status' => 1])->pluck('id')->toArray();
            $response['notification'] = auth()->user()->unreadNotifications()->count();
            $response['chat'] = auth()->user()->unreadChat()->count();
            $response['friend_request'] = auth()->user()->getFriendRequests()->count();
            $response['last_id'] = $lastMessage;
            echo json_encode($response);
            exit();
        } else if ($input['type'] == 'group') {
            $lastId = $input['last_message'];

            $where = '( find_in_set(' . $userId . ',deleted_by) is null or find_in_set(' . $userId . ',deleted_by) = 0 )';

            $chats = GroupChat::where('group_id', $id)->where('id', '>', $lastId)
                // ->whereRaw($where)
                ->orderby('id', 'ASC')->get();

            $readArray = [];

            if (count($chats)) {
                $lastId = $chats[count($chats) - 1]['id'];
                $groupReadModal = new ReadedGroupChat();

                foreach ($chats as $key => $chat) {
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
            }

            $data['chats'] = $chats;
            $data['sender'] = $userId;
            $data['reciever'] = '';
            $data['group_id'] = $id;
            $data['type'] = $input['type'];
            $data['last_id'] = $lastId;

            $html = (string) View('user.partials.chat-box-partial', $data);

            // if (!$input['last_message'])
            // {
            //     $formHtml = '';
            //     $formHtml = (string) View('user.partials.chat-form-partial', $data);
            //     $response['form_html'] = $formHtml;
            // }

            $response['req_type_id'] = 'group_' . $id;
            $response['html'] = $html;
            $response['readed'] = [];
            $response['notification'] = auth()->user()->unreadNotifications()->count();
            $response['chat'] = auth()->user()->unreadChat()->count();
            $response['friend_request'] = auth()->user()->getFriendRequests()->count();
            $response['last_id'] = $lastId;

            echo json_encode($response);
            exit();
        }
    }



    /**
     *Sends a message to a friend or a group.
     */
    public function sendMessage(Request $request)
    {

        $input = $request->all();
        $userId = Auth::user()->id;
        $toId = $input['id'];
        if ($input['type'] == 'friend') {
            $contact = Contact::whereRaw('(user_1=' . $toId . ' and user_2=' . $userId . ') or ((user_2=' . $toId . ' and user_1=' . $userId . '))')->first();

            $conStatus = $contact['status'];

            if (empty($contact) || $conStatus != 1) {
                // $this->getChats($request);
                exit();
            }
        }

        $data = [];

        $data['from_id'] = $userId;

        if (isset($input['voice_data'])) {
            // $path = 'voices/user-voices';
            // $voiceName = 'voice' . time() . '.' . $request->voice_data->extension();
            // if ($request->voice_data->move(public_path($path), $voiceName)) {
            //     $path = $path . "/" . $voiceName;
            // }
            $voiceName = 'voice' . time() . '.' . $request->voice_data->extension();
            $path = $request->voice_data->storeAs(
                'voices/user-voices',
                $voiceName,
                's3'
            );
            $data['message'] = $path;
            $data['type'] = 2;
        } else if (isset($input['attachment'])) {
            // $path = 'attachments/user-attachments';
            // $attachName = 'attachment' . time() . '.' . $request->attachment->extension();
            // if ($request->attachment->move(public_path($path), $attachName)) {
            //     $path = $path . "/" . $attachName;
            // }
            // $data['message'] = $path;
            $data['message'] = $input['message'];
            $data['type'] = 3;
        } else {
            $data['message'] = $input['message'];
        }

        if ($input['type'] == 'friend') {
            $data['to_id'] = $input['id'];
            $modal = new Chat();
            $modal->fill($data);
            $isSend = $modal->save();

            //push contact nottification
            $fromUser = User::where('id', $data['from_id'])->first();
            $title = $fromUser->full_name;
            $body = $fromUser->full_name . 'sent you a message';
            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $data['to_id'])->pluck('fcm_token')->toArray();

            $data = [
                'type' => 'friend',
                'data_id' =>  $data['from_id']
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($data['to_id'], $title, $body, 'friend', 'id', $data['from_id'], 'image', $fromUser->profile_image, 'name', $fromUser->full_name);
        } else if ($input['type'] == 'group') {
            $data['group_id'] = $input['group_id'];
            $request->merge(['id' => $input['group_id']]);
            $modal = new GroupChat();
            $modal->fill($data);
            $isSend = $modal->save();

            //push group notifications
            $group = Group::where('id', $input['group_id'])->first();
            $groupUsers = GroupUser::where('group_id', $input['group_id'])->where('user_id', '!=', $data['from_id'])->pluck('user_id');
            $fromUser = User::where('id', $data['from_id'])->pluck('full_name')->first();
            $admin = User::find($group->created_by);
            $body = $fromUser . ' sent a message';

            $title= $group->name;
            $firebaseToken = User::whereNotNull('fcm_token')->whereIn('id', $groupUsers)->pluck('fcm_token')->toArray();

            $title = $group->name;
            $data = [
                'type' => 'group',
                'data_id' => $group->id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToGroupUser($groupUsers, $group->name, $body, 'group', 'adminName', $admin->full_name, 'image', getS3File($group->icon), 'id', $input['group_id'], 'name', $group->name);
        }

        if ($isSend) {
            // $this->getChats($request);
        }
    }

    /**
     *Sends a notification to a user after a friend request is accepted or rejected.
     */
    public function sendNotification($toUser, $statusString)
    {
        if ($statusString == 'accepted') {
            $statusStringEn = 'accepted';
            $statusStringUr = 'نے آپ کی درخواست قبول کر لی ';
            $statusStringAr = 'قبل طلبك ';
        } else {
            $statusStringEn = 'rejected';
            $statusStringUr = 'نےآپ کی درخواست کو مسترد کر دیا ';
            $statusStringAr = 'رفض طلبك ';
        }


        $notification = Notification::create([
            'title' => auth()->user()->user_name . ' has ' . $statusStringEn . ' your request',
            'title_english' => auth()->user()->user_name . ' has ' . $statusStringEn . ' your request',
            'title_urdu' => auth()->user()->user_name . $statusStringUr,
            'title_arabic' => auth()->user()->user_name . $statusStringAr,
            'link' => route('user.chats'),
            'module_id' => 36,
            'right_id' => 136,
            'ip' => request()->ip()
        ]);
        $toUser->notifications()->attach($notification->id, ['from_id' => auth()->user()->id]);
    }

    /**
     *Retrieves contact group information.
     */
    public function getContactGroup(Request $request)
    {
        $input = $request->all();

        $groupId = $input['groupID'];

        $data = [];

        $data['group'] = Group::find($groupId);
        $data['group_users'] = $data['group']->groupUsers->toArray();


        echo json_encode($data);
        exit();
    }

    /**
     *Saves files uploaded via AJAX (used for attachments).
     */
    public function saveFilesAjax(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            // file not uploaded
            echo "file is not uploaded";
        }

        $fileReceived = $receiver->receive(); // receive file

        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = 'attachment' . time() . '.' . $extension; // a unique file name
            $path = $file->storeAs(
                'storage/attachments/user-attachments',
                $fileName,
                's3'
            );
            // $fileName = 'attachment' . time() . '.' . $extension; // a unique file name
            // $disk = Storage::disk(config('filesystems.default'));
            // $disk->putFileAs('public/attachments/user-attachments', $file, $fileName);
            // $path = ('storage/attachments/user-attachments/' . $fileName);

            // delete chunked file
            unlink($file->getPathname());
            return [
                'path' => $path,
                'filename' => $fileName,
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true,
        ];
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

        echo json_encode($response);
        die();
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

        echo json_encode($response);
        die();
    }
    /**
     *Deletes a specific message with a specific user or group.
     */
    public function deleteSelectedMessage(Request $request)
    {
        $messageIds = $request->input('message_ids');
        $bit = $request->input('bit');
        $deleted_by = $request->input('delete_by');
        $is_group_type = $request->input('message_type');

        if ($is_group_type == 'false') {
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
