<div id="user-info" data-user-id="{{ auth()->id() }}" style="display: none"></div>
<div id="chat-box-inputs">
    <input type="hidden" name="open_group_id" id="open_group_id" value="{{ $group_id }}">
    <input type="hidden" name="open_friend_id" id="open_friend_id" value="{{ $reciever }}">
    <input type="hidden" name="open_type" id="open_type" value="{{ $type }}">

    <form action="" id="message-section-fields">
        <input type="hidden" id="group_id" value="{{ $group_id }}" name="group_id">
        <input type="hidden" id="reciever_id" value="{{ $reciever }}" name="id">
        <input type="hidden" id="type" value="{{ $type }}" name="type">
    </form>
</div>
@php
    $authUserId = auth()->id();
@endphp
@if (count($chats))
    <!-- <li class="d-flex justify-content-center align-items-center">show previous</li> -->
    @foreach ($chats as $key => $chat)
        @php
            $deletedByArray = json_decode($chat->deleted_by, true);
            $readClass = 'message-unreaded';
            if ($type == 'friend') {
                $readClass = $chat->status ? 'message-readed' : 'message-unreaded';
            } else {
                $readed = App\Models\Chat\ReadedGroupChat::where(['group_id' => $group_id, 'sms_id' => $chat->id])->first();
                if (!empty($readed)) {
                    $readClass = 'message-readed';
                }
            }
            
            $extesnstionAssetArray = [
                'JPG' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pic.png') . '" alt="Chat-Attachment">',
            
                'PNG' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pic.png') . '" alt="Chat-Attachment">',
            
                'GIF' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pic.png') . '" alt="Chat-Attachment">',
            
                'JPEG' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pic.png') . '" alt="Chat-Attachment">',
            
                'WEBP' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pic.png') . '" alt="Chat-Attachment">',
            
                'PDF' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/pdf.png') . '" alt="Chat-Attachment">',
            
                'EXE' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/exe.png') . '" alt="Chat-Attachment">',
            
                'DOCX' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/doc.png') . '" alt="Chat-Attachment">',
            
                'DOC' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/doc.png') . '" alt="Chat-Attachment">',
            
                'PPT' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/ppt.png') . '" alt="Chat-Attachment">',
            
                '3GPP' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'MP4' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'M4V' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'SVI' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'FLV' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'WEBM' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'MKV' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'VOB' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            
                'AVI' => '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/video.png') . '" alt="Chat-Attachment">',
            ];
            
            $other = '<img style="width:50px;height:50px;" class="chat-box-icons" src="' . asset('chat-icons/other.png') . '" alt="Chat-Attachment">';
            $icon = '';
            if ($chat->type == 3) {
                $extension = explode('.', $chat->message);
                $extension = $extension[1];
            
                $source = isset($extesnstionAssetArray[strtoupper($extension)]) ? $extesnstionAssetArray[strtoupper($extension)] : $other;
            }
        @endphp

        @if ($chat->from_id == $sender)
            <li class="sending-detial message_detail  @if (isset($chat->group_id)) group_chat @endif "
                data-id="{{ $chat->id }}">
                <div class="receive-wraper">
                    {{-- <span class="chat-user-name">{{ $chat->user->user_name }}</span> --}}
                    @if (
                        !empty($group_id) and
                            ($chat->is_deleted == 2 or
                                $chat->is_deleted == 0 or
                                $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true))))
                        <p class="chat-user-name">Me</p>
                    @endif
                    @if ($chat->type == 1)
                        {{-- <p class="receive-msg common-chat-text text-white "> --}}
                        @if ($chat->is_deleted == 0)
                            {{-- Not deleted --}}
                            <p class="receive-msg common-chat-text text-white ">
                                {{ $chat->message }}
                            </p>
                        @elseif ($chat->is_deleted == 1 && $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                            {{-- delete for me --}}
                            <p class="receive-msg common-chat-text text-white ">
                                {{ $chat->message }}
                            </p>
                        @elseif($chat->is_deleted == 2)
                            {{-- delete for both --}}
                            <p class="receive-msg common-chat-text text-white ">
                                This Message was Deleted
                            </p>
                        @endif

                        {{-- </p> --}}
                    @elseif($chat->type == 2)
                        <p class="receive-msg common-chat-text text-white">
                            @if ($chat->is_deleted == 0)
                                <audio controls>
                                    <source src="{{ getS3File($chat->message) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @elseif ($chat->is_deleted == 1 && $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                                {{ $chat->message }}
                            @elseif($chat->is_deleted == 2)
                                This message was deleted
                            @endif
                        </p>
                    @elseif($chat->type == 3)
                        <p class="receive-msg d-flex align-items-center common-chat-text text-white">
                            <a href="javascript:void(0)">
                                {{-- <i class="fa fa-paperclip" aria-hidden="true" style="color: #fff"></i> --}}
                                {!! $source !!}
                            </a>
                            <span id="download " href="{{ getS3File($chat->message) }}"
                                onclick="downloadAttach($(this))"><i class="fa fa-download ms-2" aria-hidden="true"></i>
                            </span>
                            <span class="download-progress-container" style="width: 100%;"><span
                                    class="download-progress"></span></span>
                        </p>
                    @endif
                    <div class="check_checkbox_div delet-chat-box2">
                        @if (
                            $chat->is_deleted == 0 or
                                $chat->is_deleted == 1 and $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                            <input type="checkbox" value={{ $chat->id }} class="check_message_box sending_check"
                                style="display: none">
                        @endif
                    </div>
                </div>
                @if (
                    $chat->is_deleted == 0 or
                        $chat->is_deleted == 2 or
                        $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                    <p class="noticed-time tick-{{ $chat->id }}" data-time="{{ $chat->created_at }}">
                        <span class="time-sec">{{ $chat->created_at->diffForHumans() }}</span>
                        <span class="{{ $readClass }}"><i class="fa fa-check" aria-hidden="true"></i></span>
                    </p>
                @endif

            </li>
        @else
            <li class="receving-detail message_detail @if (isset($chat->group_id)) group_chat @endif"
                data-id="{{ $chat->id }}">
                <div class="receive-wraper">
                    <div class="check_checkbox_div delet-chat-box">
                        @if (
                            $chat->is_deleted == 0 or
                                $chat->is_deleted == 1 and $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                            <input type="checkbox" value={{ $chat->id }} class="check_message_box recieving_check"
                                style="display: none">
                        @endif
                    </div>
                    @if (isset($chat->group_id))
                        @if (
                            !empty($group_id) and
                                ($chat->is_deleted == 2 or
                                    $chat->is_deleted == 0 or
                                    $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true))))
                            <p class="chat-user-name">{{ !empty($chat->user->user_name) ? $chat->user->user_name : '' }}
                            </p>
                        @endif
                    @endif
                    @if ($chat->type == 1)
                        {{-- <p class="sending-msg common-chat-text"> --}}
                        @if ($chat->is_deleted == 0)
                            {{-- Not deleted --}}
                            <p class="sending-msg common-chat-text">
                                {{ $chat->message }}
                            </p>
                        @elseif($chat->is_deleted == 1 and $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                            {{-- delete for me --}}
                            <p class="sending-msg common-chat-text">
                                {{ $chat->message }}
                            </p>
                        @elseif($chat->is_deleted == 2)
                            {{-- delete for both --}}

                            @if ($chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                                <p class="sending-msg common-chat-text">
                                    This Message was Deleted
                                </p>
                            @endif
                        @endif
                        </p>
                    @elseif($chat->type == 2)
                        <p class="sending-msg common-chat-text ">
                            @if ($chat->is_deleted == 0)
                                <audio controls>
                                    <source src="{{ getS3File($chat->message) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @elseif($chat->is_deleted == 1)
                                @if ($chat->deleted_by !== $chat->from_id)
                                    {{ $chat->message }}
                                @endif
                            @elseif($chat->is_deleted == 2)
                                {{-- delete for both --}}
                                This Message was Deleted
                            @endif
                        </p>
                    @elseif($chat->type == 3)
                        <p
                            class="sending-msg common-chat-text d-flex flex-column justify-content-center align-items-center">
                            <a href="javascript:void(0)">
                                {{-- <i class="fa fa-paperclip" aria-hidden="true" style="color: #fff"></i> --}}
                                {!! $source !!}
                            </a>
                            <span id="download" href="{{ getS3File($chat->message) }}"
                                onclick="downloadAttach($(this))"><i class="fa fa-download mt-1" aria-hidden="true"></i>
                            </span>
                            <span class="download-progress-container" style="width: 100%;"><span
                                    class="download-progress"></span></span>
                        </p>
                    @endif
                </div>
                @if (
                    $chat->is_deleted == 0 or
                        $chat->is_deleted == 2 or
                        $chat->deleted_by && !in_array(auth()->id(), json_decode($chat->deleted_by, true)))
                    <p class="noticed-time text-end tick-{{ $chat->id }}" data-time="{{ $chat->created_at }}">
                        <span class="time-sec">{{ $chat->created_at->diffForHumans() }}</span>
                        <span class="{{ $readClass }}"><i class="fa fa-check" aria-hidden="true"></i></span>
                    </p>
                @endif
            </li>
        @endif
    @endforeach
@elseif(!$last_id)
    <li class="sending-detial" id="no-conversation">
        <p class="sending-msg common-chat-text">{{ __('app.chat-not started') }}</p>
    </li>
@endif
