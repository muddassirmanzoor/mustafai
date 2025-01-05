<!--Header -->
@php
$query = [];
$query = getQuery(App::getLocale(), ['title']);
$query[] = 'headline_order';
$query[] = 'id';
$headlines = App\Models\Admin\Headline::select($query)->where('status', 1)->orderBy(DB::raw('ISNULL(headline_order), headline_order'))->get();

$query = [];
$query = getQuery(App::getLocale(), ['name']);
$query[] = 'link';
$query[] = 'id';
$menues = App\Models\Admin\HeaderSetting::select($query)
->where('parent_id', null)
->orderBy('order')
->get();
$url_1 = Request::segment(1);
$url_2 = Request::segment(3);
$url_3 = Request::segment(4);
$image = getSettingDataHelper('logo') == '' ? asset('assets/home/images/site-logo.png') : getS3File(getSettingDataHelper('logo'));
$country_codes= App\Models\Admin\CountryCode::all();
@endphp


<header class="header">
    <div class="top-header banner-top-header"></div>
    <div class="bottom-header">
        <div class="container-fluid container-width">
            <nav class="navbar navbar-expand-lg navbar-light navbar-bg">
                <a class="navbar-brand" href="{{ URL('/') }}">
                    <div class="logo">
                        <img loading="lazy" src="{{ ($image) }}" alt="image not found" class="img-fluid" />
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse header-list" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        @php
                        $moreHTML = '';

                        @endphp

                        @foreach ($menues as $key => $menu)
                        @if (count($menu->getChilds) == 0 && $key < 4) {{-- {{$url_1}} --}} <li class="nav-item">
                            <a class="nav-link {{ activeNav($menu->link) }}" id="data_{{ str_replace('/', '', $menu->link) }}" data-info="simple_link" href="{{ URL($menu->link) }}">{{ $menu->name }}</a>
                            </li>
                            @elseif(count($menu->getChilds) > 0 && $key < 4) <li class="nav-item custom-design-drop-down ">
                                <div class="about-drop-down">
                                    <div id="adropDown" class="drop-down__button ">
                                        <a class="nav-link d-flex align-items-center {{ activeNav($menu->name, $menu->id, 'submenus') }} for_active_drop">
                                            {{ $menu->name }} <span class="next00"></span></a>
                                    </div>
                                    <div class="drop-down__menu-box">
                                        <ul class="drop-down__menu">
                                            @foreach ($menu->getChilds as $subPage)
                                            @php $moreLinkName = 'name_'.session()->get('locale'); @endphp
                                            <li>
                                                <a href="{{ URL($subPage->link) }}" class="drop-down__item" title="" id="data_{{ str_replace('/', '', $subPage->link) }}" data-info="drop_dowm"> {{ $subPage->{$moreLinkName} }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                </li>
                                @elseif($key >= 4)
                                @if (count($menu->getChilds) == 0)
                                @php
                                $moreHTML .= '<li><a href="' . URL($menu->link) . '" class="drop-down__item" title="" id="data_' . str_replace('/', '', $menu->link) . '" data-info="drop_dowm">' . $menu->name . '</a></li>';
                                @endphp
                                @elseif(count($menu->getChilds) > 0)
                                @php
                                $innerLis = '';
                                foreach ($menu->getChilds as $subMenue) {
                                $moreLinkName = 'name_' . session()->get('locale');
                                $innerLis .= '<li><a class="drop-down__item" href="' . URL($subMenue->link) . '" title="">' . $subMenue->{$moreLinkName} . '</a></li>';
                                }
                                $moreHTML .= '<li class="menu-item-has-children">';
                                    $moreHTML .= '<a class="drop-down__item" href="javascript:void(0)" title="">' . $menu->name . '</a>';
                                    $moreHTML .= '<ul> ' . $innerLis . ' </ul>';
                                    $moreHTML .= '</li>';
                                @endphp
                                @endif
                                @endif
                                @endforeach

                                @if (!empty($moreHTML))
                                <li class="nav-item custom-design-drop-down">
                                    <div class="about-drop-down">
                                        <div id="adropDown" class="drop-down__button">
                                            <a class="nav-link d-flex align-items-center {{ activeNav('more') }} for_active_drop">
                                                {{ __('app.header-more') }} <span class="next00"></span></a>
                                        </div>
                                        <div class="drop-down__menu-box">
                                            <ul class="drop-down__menu">
                                                {!! $moreHTML !!}
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @endif

                    </ul>
                </div>
                <div class="header-buttons {{ Auth::check() ? 'after-login-hd-button-style' : 'before-login' }}" id='header-buttons'>
                    <div class="language-bar dropdown">
                        <button class="nav-link dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (session()->get('locale') == 'english')
                            <img loading="lazy" src="{{ asset('images/flags/english.png') }}" class="d-sm-block d-none" height="30px" width="30px" style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5" />
                            {{ __('app.english') }}
                            @elseif(session()->get('locale') == 'urdu')
                            <img loading="lazy" src="{{ asset('images/flags/urdu.png') }}" class="d-sm-block d-none" height="30px" width="30px" style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5" />
                            {{ __('app.urdu') }}
                            @elseif(session()->get('locale') == 'arabic')
                            <img loading="lazy" src="{{ asset('images/flags/arabic.png') }}" class="d-sm-block d-none" height="30px" width="30px" style="border-radius: 50%; margin-right: 5px; border: 3px solid #b8c0d5" />
                            {{ __('app.arabic') }}
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            @foreach(activeLangs() as $key=>$val)
                            <li class="changeLang">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <span class="lang-name" data-val="{{strtolower( $val->key )}}">{{ $val->{'name_'.App::getLocale() } }}</span>
                                    <img loading="lazy" src="{{ asset('images/flags/'.strtolower($val->key) .'.png') }}" height="30px" />
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="search-wrapper">
                        <input type="text" class="form-control" placeholder="{{ __('app.search') }}" id="search-input" />
                        <span class="search-icon fa fa-search" id="search-now"></span>
                    </div>
                    @if (Auth::check())
                    <form id="logout-form" class="form-wraper" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <div class="dropdown">
                            <button class=" dropdown-toggle" type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="mb-0 me-sm-2 me-1 profile-img">
                                    <img loading="lazy" src="{{ getS3File(auth()->user()->profile_image) }}" alt="image not found" class="img-fluid" />
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('app.dashboard') }}</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">{{ __('app.logout') }}</a>
                                </li>
                            </ul>
                        </div>

                    </form>
                    @else
                    <button class="green-hover-bg theme-btn text-capital" id="login-sidebar-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">{{ __('app.log-in') }}</button>
                    @endif
                    <div class="login-sidebar offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="green-hover-bg btn-close close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        {{-- For Login  --}}
                        <div class="offcanvas-body">
                            <div class="login-details">
                                <div class="form-login-here">
                                    <h4 class="text-white text-captilize text-center">{{ __('app.login') }}</h4>
                                    <form class="form-wraper" action="{{ route('login') }}" method="POST" id="login_form">
                                        @csrf
                                        <label id="floatingInput-error" class="error" style="color: black !important" for="floatingInput"></label>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control  @error('email') is-invalid @enderror" id="floatingInput" name="username" placeholder="Email ID" required autocomplete="off" onblur="checkCabinet($(this).val())" />
                                            <label for="floatingInput">{{ __('app.Phone\Email\UserNames') }}</label>
                                        </div>
                                        <label id="floatingPassword-error" class="error " style="color: black !important" for="floatingPassword"></label>
                                        <div class="form-floating mb-3 div-custom d-flex align-items-center">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" name="password" placeholder="Password" required autocomplete="off" />
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                            <label for="floatingPassword">{{ __('app.password') }}</label>

                                            {{-- @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror --}}
                                        </div>
                                        <div>
                                            <label id="label_role" class="error" style="color: black !important" for="role_id"></label>
                                            <div class="login_password">

                                            </div>
                                        </div>
                                        <div class="captcha-holder">
                                            {{-- <div class="g-recaptcha mt-3 d-flex- w-100" data-callback="enableBtn2" data-sitekey="6LeUtuchAAAAAOkHp2JOP1sWoLUwascX2fCAjnzx"></div><br /> --}}
                                        </div>
                                        <div class="forgot-password d-flex justify-content-end align-items-end">
                                            <a href="{{ url('password/reset') }}">{{ __('app.forgot-password') }}</a>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex justify-content-center align-items-center mt-xxl-5 pt-3">
                                    <button class="green-hover-bg  theme-btn white-bg login" type="button" id="login_now" onclick="login($(this),'login_form')" >{{ __('app.login') }}</button>
                                </div>
                                <div class="no-acount forgot-password d-flex justify-content-center align-items-center">
                                    <a href="javascript:void(0)" onclick="changeLoginRegisterForm($(this))">{{ __("app.don't-have-an-account") }}
                                        <span>{{ __('app.register-now') }}</span></a>
                                </div>
                            </div>
                            {{-- For registration  --}}
                            <div class="login-details d-none side-register-form">
                                <div class="form-login-here">
                                    <h4 class="text-white text-captilize text-center">{{ __('app.register') }}</h4>
                                    <form class="form-wraper" action="{{ route('register') }}" method="POST" id="register_form">
                                        @csrf

                                        {{-- full name  --}}

                                        <label id="full_name-error" class="error " style="color: black !important" for="full_name_register"></label>
                                        <div class="form-floating mb-xxl-3 mb-2">
                                            <input type="text" class="form-control  @error('email') is-invalid @enderror" id="full_name_register" name="full_name" placeholder="Email ID" required autocomplete="off" />
                                            <label for="floatingInput2">{{ __('app.full-name') }}</label>
                                            @error('full_name')
                                            <span class="invalid-feedback" role="alert" style="color: black">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- email adress  --}}
                                        <label id="email_register-error" class="error " style="color: black !important" for="email_register"></label>
                                        <div class="form-floating mb-xxl-3 mb-2">
                                            <input type="email" class="form-control  @error('email') is-invalid @enderror here is copy" id="email_register" name="email" placeholder="Email ID" required autocomplete="off" data-type="email" />
                                            <input type="hidden" class="checkvalidate" value="0">
                                            <label for="floatingInput1">{{ __('app.email-address') }}</label>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert" style="color: black">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- user name  --}}
                                        <label id="user_name_register-error" class="error " style="color: black !important" for="user_name_register"></label>
                                        <div class="form-floating mb-xxl-3 mb-2">
                                            <input type="text" class="form-control  @error('user_name') is-invalid @enderror" id="user_name_register" name="user_name" placeholder="User Name" required autocomplete="off" data-type="user_name" />
                                            <input type="hidden" class="checkvalidate" value="0">

                                            <label for="floatingInput2">{{ __('app.user-name') }}</label>
                                            @error('user_name')
                                            <span class="invalid-feedback" role="alert" style="color: black">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- phone number  --}}

                                        <div class="d-flex">
                                            <label id="phone_number_register-error" class="error " style="color: black !important" for="phone_number_register"></label>
                                            <label id="country_code_id-error" class="error " style="color: black !important" for="country_code_id"></label>
                                        </div>
                                        <div class="form-floating mb-xxl-3 mb-2 form-select-input">
                                            {{-- <select class="form-control-select" name="country_code_id" id="country_code_id" data-type="phone_number"  >
                                                @foreach ($country_codes as $code)
                                                <option value="{{ $code->id }}" {{ $code->phonecode=='+92' ? 'selected' : '' }}></option>
                                            @endforeach
                                            </select> --}}
                                            <select id="select22" class="id_select2_header login-form-select" name="country_code_id" style="width: 120px;">
                                                @foreach ($country_codes as $code)
                                                <option value="{{ $code->id }}" {{ $code->phonecode=='+92' ? 'selected' : '' }} data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                                @endforeach
                                            </select>
                                            <input type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control  @error('phone_number') is-invalid @enderror" id="phone_number_register" name="phone_number" placeholder="Phone Number" required autocomplete="off" data-type="phone_number" />
                                            <input type="hidden" class="checkvalidate" value="0">
                                            <label for="phone_number">{{ __('app.phone-number') }}</label>
                                            @error('phone_number')
                                            <span class="invalid-feedback" role="alert" style="color: black">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- passowrd  --}}
                                        <label id="password_register-error" class="error " style="color: black !important" for="password_register"></label>
                                        <div class="form-floating mb-xxl-3 mb-2 div-custom d-flex align-items-center">
                                            <input type="password" passwordCheck="passwordCheck" id="password_register" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="off" />
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                            <label for="floatingPassword">{{ __('app.password') }}</label>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- confirm passeord  --}}
                                        <label id="password_confirmation-error mb-2" class="error " style="color: black !important" for="password_confirmation"></label>
                                        <div class="form-floating div-custom d-flex align-items-center">
                                            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="password_confirmation" required autocomplete="off" />
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                            <label for="floatingPassword">{{ __('app.confirm-password') }}</label>
                                            @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        {{-- term and condittin  --}}
                                        <div class="home-form form-content form-check d-flex align-items-center mt-xxl-4 mt-2">
                                            <div class="d-flex">
                                                <input type="checkbox" class="form-check-input" id="agree_check" name="agree_check" required>
                                            </div>
                                            <div class="d-flex forgot-password">
                                                <label class="form-check-label" for="agree_check">
                                                    <p class="text-white small-text">{{ __('app.i-agree-with') }} <a class="size-medium small-text" href="#">
                                                            {{ __('app.terms-&-condition') }}</a></p>
                                                </label>
                                            </div>
                                        </div>
                                        {{-- recaptha  --}}
                                        {{-- <label id="agree_check-error" class="error " style="color: black !important" for="agree_check"></label> --}}
                                        {{-- <div class="g-recaptcha mt-3" data-callback="enableregisterBtn2" data-sitekey="6LeUtuchAAAAAOkHp2JOP1sWoLUwascX2fCAjnzx"></div><br /> --}}
                                    </form>
                                    <div class="d-flex justify-content-center align-items-center mt-xxl-5 pt-3">
                                        <button class="theme-btn green-hover-bg  white-bg login" type="button" id="register_now" onclick="register($(this))">{{ __('app.register') }}</button>
                                    </div>
                                </div>
                                <div class="no-acount forgot-password d-flex justify-content-center align-items-center">
                                    <a href="javascript:void(0)" onclick="changeLoginRegisterForm($(this))">{{ __('app.already-have-an-account') }}
                                        {{ __('app.login-now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    @if(request()->url() != route('headlines'))
    <!-- <div class="ticker-wrapper-h banner-news-bar">
        <div class="news-content d-flex">
            <div class="heading text-red">{{__('app.breaking-news')}}</div>
            <ul class="news-ticker-h">
                @foreach($headlines as $headline)
                <li><a href="{{ route('headlines', $headline->id) }}">{{ $headline->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div> -->
    <div class="ticker-wrapper-h banner-news-bar ticker-wrap">
    <div class="heading text-red">{{__('app.breaking-news')}}</div>
        <div class="ticker">
            <ul class="ticker_item">
                <li>
                    @foreach($headlines as $headline)
                    <a href="{{ route('headlines', $headline->id) }}">{{ $headline->title }}</a>
                    @endforeach
                </li>
            </ul>

        </div>
    </div>
    @endif

    <div class="font-wrapper">
        <div id="magnifier" class="item selected">
            <div class="size">
                <span><i class="fa fa-search-plus"></i></span>
            </div>
        </div>
    </div>
</header>
