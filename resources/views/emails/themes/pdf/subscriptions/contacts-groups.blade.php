<div class="main-section">
    @php
        $contacts = Auth::user()->getFriends();
        $groups = Auth::user()->myGroups;
    @endphp
    <div class="custom-select-wrapper">
        <div class="input-wrapper">
            <div class="d-flex align-items-center">
                <span class="custom-select d-flex align-items-center">
                    <i class="fa fa-users me-2" aria-hidden="true"></i>
                    <span class="">{{__('app.contacts-groups')}}</span></span>
            </div>
            <div class="contact-dropdown" id="style-1">
                <div class="contacts-list postion-relative">
                    <div class="contact-heading d-flex align-items-center justify-content-between">
                        <h4 class="">{{ __('app.contacts') }}</h4>
                        {{-- <i class="fa fa-ellipsis-h dot-rotate"></i> --}}
                    </div>
                    <ul class="ul-style">
                        @foreach ($contacts->unique() as $contact)
                            @if ($contact->id == auth()->user()->id)
                                @continue;
                            @endif
                             <a href="{{route('user.chats',['id'=>$contact->id,'type'=>'user'])}}" >
                                    <li>
                                        <div class="d-flex align-items-center">
                                            <figure class="mb-0 me-2 contact-img">
                                                <img src="{{ getS3File($contact->profile_image)  }}" alt=""
                                                    class="start-a-post-profile img-fluid">
                                            </figure>

                                            <span class="contact-name">{{ $contact->user_name }}</span>
                                        </div>
                                    </li>
                                </a>
                        @endforeach
                    </ul>
                    <div class="mb-2">
                        <div class="hr-line"></div>
                    </div>
                </div>
                <div class="contacts-list">
                    <div class="contact-heading d-flex align-items-center justify-content-between">
                        <h4 class="">{{ __('app.groups') }}</h4>
                        {{-- <i class="fa fa-ellipsis-h dot-rotate"></i> --}}
                    </div>
                    <ul class="ul-style">
                        @foreach ($groups->unique() as $group)
                            <a href="{{route('user.chats',['id'=>$group->group->id,'type'=>'group'])}}" >
                                <li>
                                    <div class="d-flex align-items-center">
                                        <figure class="mb-0 me-2 contact-img">
                                            <img src="{{ getS3File($group->group->icon) }}" alt="" class="start-a-post-profile img-fluid">
                                        </figure>
                                        <span class="contact-name">{{ $group->group->name }}</span>
                                    </div>
                                </li>
                            </a>

                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
