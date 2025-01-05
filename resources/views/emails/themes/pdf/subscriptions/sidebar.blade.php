@php
$url = \Illuminate\Support\Facades\Route::currentRouteName();
@endphp

<div>
    <button class="icon__menu__open" onclick="closeNav()">
        <i class="fa fa-align-center toggler-bars"></i>
    </button>
    <span class="icon__menu__close" onclick="openNav()" style="z-index:99999;">
        <i class="fa fa-times close-icon" aria-hidden="true"></i>
    </span>
    <div class="dashborad-sidebar">
        <ul class="list dashboard-list p-0">
            @if(have_permission('View-News-Feed-Posts'))

                <li class="list-item {{ $url === 'user.dashboard' ? 'active' : '' }}">
                    <a href="{{ route('user.dashboard') }}" class="d-flex align-items-center">
                        <span>
                            <img class="simple-icon" src="{{ asset('user/images/news-feed.svg') }}">
                            <img class="hov-icon" src="{{ asset('user/images/hov-newsfeed.svg') }}">
                        </span>
                    {{__('app.news-feed')}}
                    </a>
                </li>
            @endif
            @if(have_permission('View-User-List'))
                            <li class="list-item {{ $url === 'user.list' ? 'active' : '' }}">
                                <a href="{{ route('user.list') }}" class="d-flex align-items-center">
                        <span>
                            <img class="simple-icon" src="{{ asset('user/images/userlist.svg') }}">
                            <img class="hov-icon" src="{{ asset('user/images/hov-userlist.svg') }}">
                        </span>
                                    {{__('app.user-list')}}

                                </a>
                            </li>
            @endif
            @if(have_permission('View-Library'))

                <li class="list-item {{ $url === 'user.library' ? 'active' : '' }}">
                    <a href="{{ route('user.library') }}" class="d-flex align-items-center">
                        <span>
                            <img class="simple-icon" src="{{ asset('user/images/mustafai-library.svg') }}">
                            <img class="hov-icon" src="{{ asset('user/images/mustafai-library-hover.svg') }}">
                        </span>
                        {{__('app.library')}}

                    </a>
                </li>
            @endif
            @if(have_permission('View-Job-Bank'))

            <li class="list-item {{ $url === 'user.job-bank' ? 'active' : '' }}">
                <a href="{{ route('user.job-bank') }}" class="d-flex align-items-center">
                    <span>
                        <img class="simple-icon" src="{{ asset('user/images/job-bank.svg') }}">
                        <img class="hov-icon" src="{{ asset('user/images/hov-job-bank.svg') }}">
                    </span>
                    {{__('app.job-bank')}}
                </a>
            </li>
            @endif
            @if(have_permission('View-Blood-Bank'))

            <li class="list-item {{ $url === 'user.blood-bank' ? 'active' : '' }}">
                <a href="{{ route('user.blood-bank') }}" class="d-flex align-items-center"> <span>
                        <img class="simple-icon" src="{{ asset('user/images/blood-bank.svg') }}">
                        <img class="hov-icon" src="{{ asset('user/images/hov-blood-bank .svg') }}">
                    </span>
                    {{__('app.blood-bank')}}
                </a>
            </li>
            @endif
            @if(have_permission('View-Mustafai-Store'))

            <li class="list-item {{ $url === 'user.store' ? 'active' : '' }}">
                <a href="{{ route('user.store') }}" class="d-flex align-items-center"> <span>
                        <img class="simple-icon" src="{{ asset('user/images/store.svg') }}">
                        <img class="hov-icon" src="{{ asset('user/images/hov-store.svg') }}">
                        </span>
                    {{__('app.mustafai-store')}}
                </a>
            </li>
            @endif

            @if(have_permission('View-My-Orders'))
            <li class="list-item {{ $url === 'user.my-orders' ? 'active' : '' }}">
                <a href="{{ url('user/my-orders') }}" class="d-flex align-items-center"> <span>
                        <img class="simple-icon" src="{{ asset('user/images/buying.svg') }}">
                        <img class="hov-icon" src="{{ asset('user/images/buying-hover.svg') }}">

                    </span>
                    {{__('app.my-orders')}}
                </a>

            </li>
            @endif
            <!-- <li class="list-item">
                <a href="{{ route('user.store') }}" class="d-flex align-items-center"> <span>
                        <img class="simple-icon" src="./images/library.svg">
                        <img class="hov-icon" src="./images/hov-library.svg">
                    </span>Mustafai Store</a>
            </li> -->
            @if(have_permission('View-Business-Booster'))
            @php
                $type=1;
                if(!have_permission('View-Upcoming-Business-Plans')){
                    $type=2;
                }
                if(!(have_permission('View-Upcoming-Business-Plans'))&&!(have_permission('View-Applied-Plans'))){
                    $type=3;
                }
            @endphp
            <li class="list-item {{ $url === 'user.busines-plan' ? 'active' : '' }}">
                <a href="{{ route('user.busines-plan').'?type='.$type }}" class="d-flex align-items-center"> <span>
                        <img class="simple-icon" src="{{ asset('user/images/business-booster.svg') }}">
                        <img class="hov-icon" src="{{ asset('user/images/business-bootser-hover.svg') }}">
                    </span>
                    {{__('app.busines-booster')}}
                </a>

            </li>
            @endif
            @if(have_permission('View-My-Donations'))

            <li class="list-item {{ $url === 'user.donation.details' ? 'active' : '' }}">
                <a href="{{ url('user/donation-details') }}" class="d-flex align-items-center"> <span>
                    <img class="simple-icon" src="{{ asset('user/images/donate-help.svg') }}">
                    <img class="hov-icon" src="{{ asset('user/images/donate-help-hover.svg') }}">

                    </span>
                    {{__('app.my-donations')}}
                </a>

            </li>
            @endif

            @if(have_permission('View-My-Subscription'))
                <li class="list-item {{ $url === 'user.my-subscriptions' ? 'active' : '' }}">
                    <a href="{{ url('user/my-subscriptions') }}" class="d-flex align-items-center"> <span>
                            <img class="simple-icon img-fluid" src="{{ asset('user/images/sub.svg') }}">
                            <img class="hov-icon img-fluid" src="{{ asset('user/images/subs-hover.svg') }}">
                        </span>
                         {{__('app.my-subscription')}}
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(".icon__menu__open").click(function() {
        $(this).css("display", "none");
        $(".icon__menu__close").css("display", "block");
        $(".dashborad-sidebar").css("transform", "translateX(0)");
    });
    $(".icon__menu__close").click(function() {
        $(this).css("display", "none");
        $(".icon__menu__open").css("display", "block");
        $(".dashborad-sidebar").css("transform", "translateX(-240px)");
    });
</script>
