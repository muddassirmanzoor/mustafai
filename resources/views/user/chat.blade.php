@extends('user.layouts.layout')
@section('content')
    <section class="chat-card">

        <div class="message-box-header">
            <a href="javascript:void(0)" id="chat-info" class="btn chat-user-name" style="display:none;"></a>
        </div>
        <input type="hidden" id="lastMessageId" value="0">
        <input type="hidden" id="contact_id" data-attr="{{$type}}" value="{{ $c_id }}">

        <div id="message-box" style="display: none;">
            <i class="fa fa-list-ul "  id = "selec_message"></i>
            {{-- <i class="fa fa-trash" id = "delete_message" ></i> --}}
            {{-- <i class="fa fa-list-ul "  id = "deleteButton"></i> --}}
            <i class="fa fa-trash ms-2 text-red" id = "delete_messages" style="display: none"></i>
            {{-- <button id="delete_messages">Delete Content</button> --}}

            <ul class="chat-msg dash-common-card message-box" id="messages-list">
                <li>show previous</li>
            </ul>

            <div class="typinfg-area d-flex" id="chat-form-section">
                <form id="send-msg-form">
                    <input class="form-control form-control-lg chat-section enable" type="text" name="message"
                        aria-label=".form-control-lg example" id="chat-msg" required autocomplete="off" />

                    <button type="button" id="recordButton" class="voice-section voice-section-btn disable">{{ __('app.record') }}</button>
                    <button type="button" id="pauseButton" class="voice-section voice-section-btn disable"
                        disabled>{{ __('app.pause') }}</button>
                    <button type="button" id="stopButton" class="voice-section voice-section-btn disable"
                        disabled>{{ __('app.stop') }}</button>
                    <ol id="recordingsList" class="voice-section disable"></ol>
                    <div id="is-recording" class="voice-section disable">
                        <div class="recording recording-container" style="visibility:hidden;">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                    </div>
                </form>

                <span class="link-assets"id="attach-section">
                    <button type="button" class="link-btn btn" onclick="attachAsset()">
                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                    </button>
                </span>

                <span class="link-assets send-sms-btn" id="send-sms-btn">
                    <button type="button" class="link-btn btn" id="send-msg-btn" onclick="sendMessage()">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </button>
                </span>

                <span class="link-assets" id="send-voice-btn" style="display:none;">
                    <button type="button" class="link-btn btn">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </button>
                </span>

                <div class="say-something">
                    <button id="chat-mike-btn" type="button" class="btn mike-btn" data-attr-sec="voice"
                        onclick="openChatVoiceSections($(this))">
                        <i class="fa fa-microphone" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="empty-message-box" class="mt-4 justify-content-center align-items-center">
            <h5 class="text-center">{{ __('app.message-here-title') }}</h5>
        </div>
    </section>

    <div class="modal fade library-detail common-model-style" role="dialog" id="add-contact-group">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="add-contact-group-heading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-danger pull-right" id="del-group-btn" onclick="deleteGroup()">{{ __('app.delete') }}</button>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-12">
                            <form id="create-group-contact" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="" id="type">
                                <div class="row" id="contact-fields" style="display:none">
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ __('app.search') }}</label>
                                            <select class="form-control" name="users[]" id="search-users"
                                                style="width: 100%;" required multiple>
                                                <option></option>
                                                @foreach ($availableUsers as $user)
                                                    <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label id="search-users-error" class="error" for="search-users"></label>
                                    </div>
                                </div>
                                <div class="row" id="group-fields" style="display:none">
                                    <input type="hidden" id="group-id" name="group_id" value="">
                                    <div class="col-lg-12">
                                        <figure class="mb-0 me-3 user-img d-flex">
                                            <img id="group-icon" src="{{ getConGroImg('group', 'empty') }}" alt="" class="img-fluid" />
                                        </figure>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="exampleInputPassword1">{{ __('app.choose-icon') }}</label>
                                        <div class="form-group ">
                                            <input type="file" class="form-control custom-file-input" name="icon" accept="image/png, image/gif, image/jpeg" onchange="preview()">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ __('app.group-name') }}</label>
                                            <input type="text" class="form-control" id="group-name" name="name"
                                                placeholder="{{ __('app.group-name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ __('app.description') }}</label>
                                            <textarea name="description" id="grp-description" cols="30" rows="3" placeholder="{{ __('app.description') }}" class="form-control" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ __('app.add-participent') }}</label>
                                            <select name="participants[]" id="add-participant" style="width: 100%;"
                                                required multiple>
                                                <option></option>
                                                @foreach ($contacts as $contact)
                                                    @if ($contact->id == auth()->user()->id)
                                                        @continue;
                                                    @endif
                                                    <option value="{{ $contact->id }}">{{ $contact->user_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label id="add-participant-error" class="error" for="add-participant"></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="green-hover-bg theme-btn"
                        onclick="createContactGroup($(this))">{{ __('app.create') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade library-detail common-model-style" role="dialog" id="attach-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="attach-modal-heading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-12">
                            <form id="attach-form" enctype="multipart/form-data">
                                <input type="file" name="file" value="" id="attach-file" onchange="filterAsset($(this))" required>
                            </form>
                        </div>
                    </div>
                    <div  style="display: none" class="progress mt-3" style="height: 25px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                    </div>
                </div>



                <div class="modal-footer">
                    {{-- <button type="button" class="green-hover-bg theme-btn" --}}
                        {{-- onclick="attachAsset(2)">Send</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/admin/dist/js/resumable.js')}}"></script>
    @include('user.scripts.chat-script')
    @include('user.scripts.chat-script-audio-recorder')
    @include('user.scripts.chat-script-audio-app')
    <script src="{{asset('assets/chat/chat_delete.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2"></script>
    <script>
        function preview() {
            $('#group-icon').attr("src", URL.createObjectURL(event.target.files[0]));
        }
    </script>
@endpush
