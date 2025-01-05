@extends('home.layout.app')

@section('content')
    <section class="csm-pages-wraper commpon-log-sign">
        <div class="container">
            {{-- <div class="d-flex justify-content-center align-items-center">
                <a class="navbar-brand" href="{{ URL('/') }}">
                    <div class="logo">
                        <img src="{{ asset('assets/home/images/site-logo.png') }}" alt="image not found" class="img-fluid" />
                    </div>
                </a>
            </div> --}}
            <div class="card">
                <div class="log-header">
                    <h4 class="text-center">{{__('app.login')}}</h4>
                </div>
                <div class="body">
                    <form class="contact-us-form" id="login_page_form" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="phone_number">{{__('app.Phone\Email\UserNames')}}</label>
                                <input id="phone_number" type="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror" name="username"
                                    value="{{ old('phone_number') }}" required autocomplete="phone_number" placeholder="{{__('app.Phone\Email\UserName')}}" autofocus onblur="checkCabinet($(this).val())">

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3" >
                            <div class="col-md-12">
                                <label for="password">{{__('app.password')}}</label>

                              <div class="d-flex align-items-center show-hide-pass-eye div-custom">
                                <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="{{__('app.password')}}">
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                              </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3" >
                            <div class="col-md-12 login_password">
                                {{-- <label for="password">{{__('app.select-role')}}</label> --}}

                            </div>
                        </div>

                        <div class="home-form form-content d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('app.remember-me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-green" href="{{ route('password.request') }}">
                                    {{__('app.forgot-password')}}
                                </a>
                            @endif
                        </div>
                        <div class="captcha-holder">
                            {{-- <div class="g-recaptcha" data-sitekey="6LeUtuchAAAAAOkHp2JOP1sWoLUwascX2fCAjnzx" data-callback="enableBtn"></div><br/> --}}
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" id="login_page_btn" class="green-hover-bg theme-btn login"onclick="login($(this),'login_page_form')">
                                {{__('app.login')}}
                            </button>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <a class="text-green" href="{{ route('register') }}">
                                {{__("app.don't-have-an-account")}} <span>{{__('app.register-now')}}</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @include('home.home-sections.get-in-touch')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection
@push('footer-scripts')
 <script>

    $(document).ready(function() {
            $(".login-sidebar").html("")
            $("#login-sidebar-btn").css('visibility','hidden');
        });
 </script>
@endpush
