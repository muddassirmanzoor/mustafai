<div class="chat-code">
    <button class="icon__menu__open" onclick="closeNav()">
        <i class="fa fa-align-center toggler-bars"></i>
    </button>
    <div>
        <div class="chat-sidebar" id="navbar">
            <span class="icon__menu__close" onclick="openNav()" style="z-index:99999;">
                <i class="fa fa-times close-icon" aria-hidden="true"></i>
            </span>
            <div class="d-flex justify-content-between multi-chat-btn-list">
                <div class="d-flex">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active contact-group-sec" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">{{ __('app.friends-title') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link contact-group-sec" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab"
                                aria-controls="pills-profile" aria-selected="false">{{ __('app.group-title') }}</button>
                        </li>
                    </ul>
                </div>
                <div class="d-flex">
                    <button type="button" class="btn add-more-btn" onclick="addContactGroup($(this))">+</button>
                </div>
            </div>
            <div class="px-3">
                <input type="text" class="form-control" id="search-section" onkeyup="searchSection()">
            </div>
            <div class="chat-tab-content tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <ul class="friends-list">
                        <!-- Friends -->
                        {{-- {{ dd($contacts) }} --}}
                        @foreach ($contacts->unique() as $contact)
                            @if ($contact->id == auth()->user()->id)
                                @continue;
                            @endif
                            <li class="read contacts-groups-list {{ ($contact->status==2)?'no-more-friend':'' }}" id="contact_{{ $contact->id }}" onclick="openChatBox('friend',{{ $contact->id }})">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex d-flex align-items-center flex-fill unfriend-seen">
                                        <figure class="mb-0 me-2 user-img d-flex">
                                            <img src="{{ getConGroImg('contact', $contact->id) }}" alt=""
                                                class="img-fluid" />
                                        </figure>
                                        <div class="d-flex profile-tag-line  flex-column flex-fill chat-headlines">
                                            <h6>{{ $contact->user_name }}</h6>
                                            {{-- <p class="tag-line-text">{{ $contact->tagline_english }}</p> --}}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex">
                                            <span class="sms-counts" id="contact-count-{{$contact->id}}" style="display: none;">0</span>
                                        </div>

                                        <div class="d-flex justify-content-center chat-menu-drop">
                                            <div class="dropdown">
                                                <button class="" type="button" id="drop-contact-down-{{ $contact->id }}" onclick="openActionsSection($(this))">
                                                   <strong>...</strong>
                                                </button>
                                                <ul class="chat-drop-menu dropdown-menu dropdown-menu-actions" aria-labelledby="drop-contact-down-{{ $contact->id }}">
                                                    <li><a class="dropdown-item" href="{{ route('user.profile', hashEncode($contact->id)) }}">{{ __('app.profile') }}</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteConversation({{ $contact->id }},'contact')">{{ __('app.delete-conversation') }}</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <ul class="friends-list" id="groups-sidebar">
                        <!-- Groups -->
                        @foreach ($groups->unique() as $group)
                            <li class="read d-flex align-items-start contacts-groups-list" id="group_{{ $group->group->id }}" onclick="openChatBox('group',{{ $group->group->id }})">
                                <div class="d-flex align-items-center flex-fill ">
                                    <figure class="mb-0 me-3 user-img d-flex">
                                        <img src="{{ getConGroImg('group', $group->group->id) }}" alt=""
                                            class="img-fluid" />
                                    </figure>
                                    <div class="d-flex flex-column flex-fill chat-headlines">
                                        <h6>{{ $group->group->name }}</h6>
                                        <p>{{ $group->group->description }}</p>
                                    </div>
                                </div>

                                <div class="d-flex flex-column">
                                    <div class="d-flex">
                                        <span class="sms-counts count-group" id="group-count-{{$group->group->id}}" style="display: none;">0</span>
                                    </div>
                                    <div class="d-flex justify-content-center chat-menu-drop">
                                        <div class="dropdown">
                                            <button class="dropdown-toggle" type="button" id="drop-group-down-{{ $group->group->id }}" data-bs-toggle="dropdown" aria-expanded="false" onclick="openActionsSection($(this))">
                                                <strong>...</strong>
                                            </button>
                                            <ul class="chat-drop-menu dropdown-menu dropdown-menu-actions" aria-labelledby="drop-group-down-{{ $group->group->id }}">
                                                @if(Auth::user()->id == $group->group->created_by)
                                                    <li>
                                                        <a class="dropdown-item" href="javsscript:void(0)" onclick="addContactGroup($(this),{{$group->group->id}})">{{ __('app.edit-group') }}</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deleteConversation({{ $group->group->id }},'group')">{{ __('app.delete-conversation') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </li>

                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
