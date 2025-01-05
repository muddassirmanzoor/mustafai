@extends('home.layout.app')

@push('header-scripts')
<!--select 2-->
<style>
.select2-selection.select2-selection--single{
    display: flex;
    align-items: center;
    border: 1px solid #ccd4da !important;
    border-radius: 3px !important;
    margin-right: 8px;
}
.select2-selection.select2-selection--single .select2-selection__arrow{
    right: 16px !important;
}
.country-code-wrap{
    display: flex;
    align-items: center;
}
</style>
@endpush
@php
    $country_codes= App\Models\Admin\CountryCode::all();
@endphp
@section('content')
    <section class="csm-pages-wraper register-pg commpon-log-sign">
        <div class="container">
            {{-- <div class="d-flex justify-content-center align-items-center">
            <a class="navbar-brand" href="{{URL('/')}}">
                <div class="logo">
                    <img src="{{ asset('assets/home/images/site-logo.png') }}" alt="image not found" class="img-fluid" />
                </div>
            </a>
        </div> --}}
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="log-header">
                            <h4 class="text-center">{{ __('app.register') }}</h4>
                        </div>
                        <div class="body">
                            <form  id="register_form" method="POST" >
                                @csrf
                                {{-- full name  --}}
                                <div class="row mb-3">

                                    <div class="col-12">
                                        <label for="full_name">{{ __('app.full-name') }}</label>
                                            <input id="full_name_register" type="text"
                                            class="form-control "
                                            placeholder="{{ __('app.full-name') }}" name="full_name"
                                            value="" required autocomplete="full_name">
                                        <label class="text-danger" id="full_name_error" for="full_name_register"></label>

                                    </div>
                                </div>
                                 {{-- email  --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="email">{{ __('app.your-email') }}</label>
                                        <input id="email_register" type="email"
                                            class="form-control here is copy"
                                            placeholder="{{ __('app.your-email') }}" name="email"
                                            value="" required autocomplete="email">
                                        <label class="text-danger" id="email_register-error" for="email_register"></label>

                                    </div>
                                </div>

                                 {{-- user name  --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="user_name">{{ __('app.your-name') }}</label>
                                        <input id="user_name_register" type="text"
                                            placeholder="{{ __('app.enter-name') }}"class="form-control"
                                            name="user_name" value="" required
                                            autocomplete="user_name" autofocus>
                                            <label id="user_name_register-error" class="error " for="user_name_register"></label>

                                    </div>
                                </div>

                                {{-- phone number  --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="phone_number">{{ __('app.phone-number') }}</label>
                                        <div class="form-select-input second">
                                            {{-- <select name="country_code_id" class="form-control-select" name="" id="country_code_id">
                                                @foreach ($country_codes as $code)
                                                <option value="{{ $code->id }}" {{ $code->phonecode=='+92' ? 'selected' : '' }}>({{ $code->phonecode }})</option>
                                                @endforeach
                                            </select> --}}
                                            <select id="id_select2_example" class="id_select2_example" name="country_code_id" style="width: 200px;">
                                                @foreach ($country_codes as $code)
                                                <option value="{{ $code->id }}" {{ $code->phonecode=='+92' ? 'selected' : '' }} data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                                @endforeach
                                            </select>
                                            <input id="phone_number_register" type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control "
                                                placeholder="{{ __('app.phone-number') }}" name="phone_number"
                                                value="" required autocomplete="phone_number">
                                            </div>
                                            <label id="country_code_id-error" class="error d-none" for="country_code_id"></label>
                                            <label id="phone_number_register-error" class="error " for="phone_number_register"></label>
                                    </div>
                                </div>

                                {{-- password  --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="password_register">{{ __('app.password') }}</label>
                                        <div class="d-flex align-items-center show-hide-pass-eye div-custom">
                                            <input id="password_register" type="password" placeholder="{{ __('app.password') }}"
                                                class="form-control " name="password"
                                                required autocomplete="new-password">
                                            <span toggle="#password"
                                                class="fa fa-fw fa-eye field-icon toggle-password eye-custom"
                                                id="eye-custom"></span>


                                            </div>
                                            <label id="password_register-error" class="error " for="password_register"></label>
                                    </div>
                                </div>
                                 {{-- confirm password  --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="password-confirm">{{ __('app.confirm-password') }}</label>
                                        <div class="d-flex align-items-center show-hide-pass-eye  div-custom">
                                            <input id="password_confirmation" type="password" class="form-control"
                                                placeholder="{{ __('app.confirm-password') }}" name="password_confirmation"
                                                required autocomplete="new-password">
                                            <span toggle="#password"
                                                class="fa fa-fw fa-eye field-icon toggle-password eye-custom"
                                                id="eye-custom"></span>

                                            </div>
                                            <label id="password_confirmation-error" class="error " for="password_confirmation"></label>
                                    </div>
                                </div>
                                {{-- <div class="captcha-holder">
                                    <div class="g-recaptcha" data-sitekey="6LeUtuchAAAAAOkHp2JOP1sWoLUwascX2fCAjnzx"
                                        data-callback="enableregisterBtn"></div><br />
                                    </div> --}}
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" id="register_page" class="green-hover-bg theme-btn" onclick="register($(this))">
                                        {{ __('app.register') }}
                                    </button>

                                </div>
                                <div class="d-flex justify-content-center align-items-center mt-2">
                                    <a class="text-green"
                                        href="{{ url('login') }}">{{ __('app.already-have-an-account') }}
                                        {{ __('app.login-now') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
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
 {{-- @include('home.scripts.phone-code-script') --}}
@endpush
