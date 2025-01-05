<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('assets/home/images/favicon.png')}}" />
    <!-- Meta Description -->
    <meta name="description" content="" />
    <!-- Meta Keyword -->
    <meta name="keywords" content="" />
    <!-- meta character set -->
    <meta charset="UTF-8" />
    <!-- Site Title -->
    <title>Mustafai</title>
    <!-- Bootstrap-5 CSS -->
    <link rel="stylesheet" href="{{ url('user/css/bootstrap-5.min.css') }}" />
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ url('user/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/owl.theme.default.min.css') }}" />
    <!--font awsome-->
    <link href="{{ url('user/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!--aos animation-->
    <link href="{{ url('user/css/aos.css') }}" rel="stylesheet" />
    <!-- style -->
    <link rel="stylesheet" href="{{ url('user/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/slick-theme.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/style.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/mustafai.css') }}" />

    @if(App::getLocale() == 'english')
    <link rel="stylesheet" href="{{ url('user/css/style.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/mustafai.css') }}" />
    @endif
    @if(App::getLocale() == 'urdu' || App::getLocale() == 'arabic')
    <link rel="stylesheet" href="{{ url('user/css/ur-vr-dashboard.css') }}" />
    <link rel="stylesheet" href="{{ url('user/css/ur-vr-mustafai.css') }}" />
    @endif

    <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.min.css')}}">
    {{-- <link rel="stylesheet" href="{{url('assets/home/ttf/arial.ttf')}}"> --}}
    <!-- toaster -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!--select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- for sweet alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
    <!-- Flipbook StyleSheet -->
    <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

    <!-- Icons Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />

    <style>


        .error{
            color: red;
        }
        .preloader {
            display: flex;
            background-color: #f4f6f9;
            height: 100vh;
            width: 100%;
            transition: height 200ms linear;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 9999;
            opacity: 0.9;
        }
    </style>
    @stack('styles')

</head>

<body class="{{(App::getLocale() != 'english') ? 'ur-ar-version':'en-version'}}">
    <!-------------------------------////////////////////------------------------------------->
    <section id="loading">
        <div id="loading-content"></div>
      </section>
    @php
    $url = \Illuminate\Support\Facades\Route::currentRouteName();
    $user_subscription_enedate=\App\Models\Admin\UserSubscription::where('user_id',auth()->user()->id)->pluck('subscription_end_date')->first();
    @endphp


    <div class="wrapper">
                <!-- Preloader -->
                <div class="preloader flex-column justify-content-center align-items-center">
                    <img class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}" alt="Mustafai Dashboard">
                </div>
        <!--Header -->
        @include('user.layouts.header')
        <!--page content -->
        <div class="admin-side-wraper">

            <div class="news-feed {{ ($url == 'user.chats') ? 'chat-page' : '' }} {{ ($url == 'user.profile') ? 'max-area' : '' }}">
                <!-- sidebar -->
                @if($url === 'user.chats')
                @include('user.layouts.chat-sidebar')
                @else
                @include('user.layouts.sidebar')
                @endif
            <!-- content -->
                <div class="content {{ ($url == 'user.chats') ? 'chat-content' : '' }} ">
                    @if(!empty(auth()->user()->subscription_fallback_role_id) && ($user_subscription_enedate > time()))
                        <div class="subscription-expired">
                            <i class="fa fa-exclamation-triangle pl-2" aria-hidden="true"></i>&nbsp;
                            <p>{{ __('app.subscription-msg2') }}</p>
                            <a href="{{ url('user/my-subscriptions') }}">{{ __('app.subscribe-btn') }}</a>
                        </div>
                    @endif
                    @if(!empty(auth()->user()->subscription_fallback_role_id) && ($user_subscription_enedate < time()))
                        <div class="subscription-expired">
                            <i class="fa fa-exclamation-triangle pl-2" aria-hidden="true"></i>&nbsp;
                            <p>{{ __('app.subscription-msg') }}</p>
                            <a href="{{ url('user/my-subscriptions') }}">{{ __('app.subscribe-btn') }}</a>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
            <!--contacts and groups-->
            @if($url != 'user.profile')
            <div class="contacts-group">
                @include('user.layouts.contacts-groups')
            </div>
            @endif
        </div>
    </div>
    <!-------------------------------////////////////////------------------------------------->

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ url('user/js/jquery.js') }}"></script>
    <script src="{{ url('user/js/bootstrap-5.min.js') }}"></script>
    <!--aos script-->
    <script src="{{ url('user/js/aos.js') }}"></script>
    <!--owl script-->
    <script src="{{ url('user/js/popper.min.js') }}"></script>
    <script src="{{ url('user/js/script.js') }}"></script>
    <script src="{{ url('user/js/slick.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <!-- toaster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!--custom javascript-->
    <script src="{{ url('user/custom-js/main.js') }}"></script>
    <script src="{{asset('assets/admin/select2/js/select2.js')}}"></script>
    <script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>

    @include('common-script.validate-localization')
    <!-- for sweet alert  -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.min.js" integrity="sha512-BDZ+kFMtxV2ljEa7OWUu0wuay/PAsJ2yeRsBegaSgdUhqIno33xmD9v3m+a2M3Bdn5xbtJtsJ9sSULmNBjCgYw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <script>
        $(document).ready(function() {
            var headlineItems = $('.news-ticker-h li a').length;
            if(headlineItems = 1)
            {
                $('.news-ticker-h').css('-webkit-animation-duration','30s');
                $('.news-ticker-h').css('animation-duration','30s');
            }
            if(headlineItems <= 3)
            {
                $('.news-ticker-h').css('-webkit-animation-duration','35s');
                $('.news-ticker-h').css('animation-duration','35s');
            }
            else if(headlineItems <= 6)
            {
                $('.news-ticker-h').css('-webkit-animation-duration','50s');
                $('.news-ticker-h').css('animation-duration','50s');
            }
            else if(headlineItems <= 10)
            {
                $('.news-ticker-h').css('-webkit-animation-duration','60s');
                $('.news-ticker-h').css('animation-duration','60s');
            }
            // window.onload = function() {
                $('.preloader').fadeOut(500, function() {
                    $('.preloader').css('display','none');
                });
            // }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        var URLS = {
            donationPaymentMethod: "{{ url('/get-payment-method') }}",
            donateNow: "{{ url('donate') }}",

        }
        const interval = null;
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.zone_id').select2({
            tags: true
            });
            $('.zone_id_permanent').select2({
            tags: true
            });
        });
    </script>
    <!-- toaster script-->
    <script>
        @if(session('success'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
        toastr.error("{{ session('error') }}");
        @endif
        var url2 = "{{ route('user.change-lang') }}";
        $(".changeLang").on('click', function() {
            window.location.href = url2 + "?lang=" + $(this).find('.lang-name').attr('data-val');
        });
    </script>
    <script>
        $(".toggle-password").click(function() {

            $(this).toggleClass("fa-eye fa-eye-slash");
            if($(this).hasClass('fa-eye-slash')){
                $(this).parent('.div-custom').find('input').attr('type','text')
            }else{
                $(this).parent('.div-custom').find('input').attr('type','password')
            }
    });
    </script>

    @stack('scripts')
    @include('user.scripts.app-script')
    <!-- Flipbook main Js file -->
    <script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
</body>

</html>
