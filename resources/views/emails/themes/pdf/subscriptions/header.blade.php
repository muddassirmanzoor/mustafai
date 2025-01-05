@php
    $query = [];
    $query = array_merge(getQuery(App::getLocale(), ['title']),['id']);
    $headlines = App\Models\Admin\Headline::select($query)->where('status', 1)->get();

    $query = [];
    $query = getQuery(App::getLocale(), ['name']);
    $query[] = 'link';
    $query[] = 'id';
    $image=getSettingDataHelper('logo')=='' ? asset('assets/home/images/site-logo.png') : getS3File(getSettingDataHelper('logo'));
@endphp
<header class="header admin-header">
    <div class="dashboad-ticker ticker-wrapper-h">
        <div class="heading text-red">{{__('app.breaking-news')}}</div>
        <ul class="news-ticker-h">
            <li>
                @foreach($headlines as $headline)
                    <a href="{{ route('headlines', $headline->id) }}">{{ $headline->title }}</a>
                @endforeach
            </li>
        </ul>
    </div>
    <div class="dashboard-header bottom-header">
        <div class="container-fluid container-width">
            <nav class="navbar navbar-expand-lg navbar-light navbar-bg">
                <div class="d-flex align-items-center nav-left">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <div class="logo">
                            <img src="{{($image)}}" alt="image not found"
                                 class="img-fluid"/>
                        </div>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="header-form">
                        <div class="header-search">
                            <span class="search-icon fa fa-search"></span>
                            <input type="text" name="q" class="form-control border-0 shadow-none w-100"
                                   placeholder="{{__('app.search')}}" id="search-input">
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse header-list" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.dashboard') }}">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    {{__('app.home')}}
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.donate') }}">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                    {{__('app.donate')}}
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.friend-requests')}}">
                                <span class="friend-request-count"
                                      style="display: none">{{ auth()->user()->getFriendRequests()->count() }}</span>
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    {{__('app.request')}}
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.chats')}}">
                                <span class="chat-count"
                                      style="display: none">{{ auth()->user()->unreadChat()->count() }}</span>
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <i class="fa fa-comment" aria-hidden="true"></i>
                                    {{__('app.chat')}}
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown notifications">
                                <a onclick="loadNotifications(this)"
                                   class="nav-link dropdown-toggle d-flex flex-column justify-content-center align-items-center"
                                   data-bs-toggle="dropdown" type="button" id="dropdownMenuButton1"
                                   aria-expanded="false">
                                    <span class="notifications-count total_unread_notifications"
                                          style="display: none">{{ auth()->user()->unreadNotifications()->count() }}</span>
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    {{__('app.notification')}}
                                </a>
                                <ul class="notifications-list dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <h6>{{__('app.notification')}}</h6>
                                    <div class="mesages-detail notification_listing">
                                        <li class="notifications_loading text-center">{{__('app.loading')}}...</li>
                                        {{-- dynamic notifcations append here--}}
                                    </div>
                                    <a class="d-flex justify-content-center align-items-center me-2"
                                       href="javascript:void(0)"
                                       onclick="readNotifications()">{{__('app.mark-all-as-read')}}</a>
                                    <a href="{{ route('user.notifications') }}"
                                       class="show-all">{{__('app.show-all-notifications')}}</a>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="header-buttons">
                    <div class="language-bar dropdown">
                        <button class="nav-link dropdown-toggle m-0" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            @if(session()->get('locale') == 'english')
                                <img src="{{ asset('images/flags/english.png') }}"
                                     height="30px" width="30px"
                                     style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5"/>
                                <span class="d-sm-block d-none">
                                    {{__('app.english')}}
                                </span>
                            @elseif(session()->get('locale') == 'urdu')
                                <img src="{{ asset('images/flags/urdu.png') }}" height="30px"
                                     width="30px"
                                     style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5"/>
                              <span class="d-sm-block d-none">
                                {{__('app.urdu')}}
                              </span>
                            @elseif(session()->get('locale') == 'arabic')
                                <img src="{{ asset('images/flags/arabic.png') }}"
                                     height="30px" width="30px"
                                     style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5"/>
                                <span class="d-sm-block d-none">
                                    {{__('app.arabic')}}
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            @foreach(activeLangs() as $key=>$val)
                            <li class="changeLang">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <span class="lang-name" data-val="{{strtolower( $val->key )}}">{{ $val->{'name_'.App::getLocale() } }}</span>
                                    <img src="{{ asset('images/flags/'.strtolower($val->key) .'.png') }}" height="30px" />
                                </a>
                            </li>
                            @endforeach
                            {{-- <li class="changeLang">
                                <a class="dropdown-item" href="#">
                                    <span class="lang-name" data-val="english">{{__('app.english')}}</span>
                                    <img src="{{ asset('images/flags/english.png') }}" height="30px"/>
                                </a>
                            </li>
                            <li class="changeLang">
                                <a class="dropdown-item" href="#">
                                    <span class="lang-name" data-val="urdu">{{__('app.urdu')}}</span>
                                    <img src="{{ asset('images/flags/urdu.png') }}" height="30px"/>
                                </a>
                            </li>
                            <li class="changeLang">
                                <a class="dropdown-item" href="#">
                                    <span class="lang-name" data-val="arabic">{{__('app.arabic')}}</span>
                                    <img src="{{ asset('images/flags/arabic.png') }}" height="30px"/>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="profile-bar dropdown">
                        <button class="nav-link dropdown-toggle m-0 p-0" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <figure class="mb-0 me-2 profile-img">
                                <img src="{{ getS3File(auth()->user()->profile_image) }}" alt=""
                                     class="img-fluid">
                            </figure>
                            <div class="profile-name d-md-block">
                                {{-- <span class="d-flex">Hello</span> --}}
                                <p>{{ auth()->user()->{'user_name_'.app()->getLocale()} }}</p>
                                {{-- <p>{{ auth()->user()->user_name }}</p> --}}
                            </div>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li class="px-2 pb-2 relative">
                                <div class="profile-name d-flex justify-content-between">
                                    <a href="{{URL('user/profile')}}">
                                        <p>{{__('app.profile')}}</p>
                                    </a>
                                    <i class="fa fa-user-plus absolute--icon" aria-hidden="true"></i>
                                </div>
                            </li>
                            <li class="px-2 pb-2 relative">
                                <div class="profile-name d-flex justify-content-between">
                                    <a href="{{URL('user/profile-settings')}}">
                                        <p>{{__('app.profile-settings')}}</p>
                                    </a>
                                    <i class="fa fa-cog absolute--icon" aria-hidden="true"></i>
                                </div>
                            </li>
                            @if(have_permission('View-Activity'))
                            <li class="px-2 pb-2 relative">
                                <div class="profile-name d-flex justify-content-between">
                                    <a href="{{ route('user.activity') }}">
                                        <p>{{__('app.activity')}}</p>
                                    </a>
                                    <i class="fa fa-list-ul absolute--icon" aria-hidden="true"></i>
                                </div>
                            </li>
                            @endif
                            <li class="px-2 pb-2 relative">
                                <div class="profile-name d-flex justify-content-between">
                                    <form id="logout-form" class="form-wraper w-100" action="{{ route('logout') }}"
                                          method="POST">
                                        @csrf
                                        <div class="d-flex justify-content-between w-100">
                                            <button type="submit" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                {{__('app.logout')}}
                                                <i class="fa fa-sign-out text-green absolute--icon" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        function readNotification(_this) {
            let notificationId = $(_this).attr('data-notification-id');

            $.ajax({
                type: "POST",
                url: "{{route('notification.read')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'id': notificationId,
                },
                success: function (result) {
                    if (result.status == 200) {
                        $('.total_unread_notifications').text('');
                        $('.total_unread_notifications').text(result.data);
                    }
                }
            });
        }

        function readNotifications() {
            $.ajax({
                type: "GET",
                url: "{{route('notifications.read')}}",
                success: function (result) {
                    if (result.status == 200) {
                        $('.total_unread_notifications').text('');
                        $('.total_unread_notifications').text(result.data);
                    }
                }
            });
        }

        function loadNotifications(_this) {
            $.ajax({
                type: "GET",
                url: "{{route('user.header-notifications')}}",
                success: function (result) {
                    if (result.status === 200) {
                        $('.notification_listing').html(result.data)
                    }
                }
            });
        }

        $("#search-input").keypress(function (e) {
            if (e.which == 13) {
                if ($('#search-input').val()) {
                    search();
                }
            }
        });

        function search() {
            // console.log('search');
            var search = $('#search-input').val();
            var baseurluser="{{url('/user')}}";
            window.location.href =baseurluser+'/search?q=' + encodeURIComponent(search);
        }

    </script>
@endpush
