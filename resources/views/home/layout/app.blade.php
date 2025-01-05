<html lang="en" translate="no">

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('assets/home/images/favicon.png') }}" />
    <!-- Meta Description -->
    <meta name="description" content="" />
    <!-- Meta Keyword -->
    <meta name="keywords" content="" />
    <!-- meta character set -->
    <meta charset="UTF-8" />
    {{-- facebook meta tags --}}
    <meta property="og:url" content="https://mustafai.pk/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Mustafai Tahreek" />
    <meta property="og:description" content="Mustafai Tahreek Description" />
    <meta property="og:image" content="https://mustafai.pk/post-images/post165537554163.png" />
    <!-- Site Title -->
    <title>Mustafai</title>
    <!-- Bootstrap-5 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/home/css/bootstrap-5.min.css') }}" />
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('assets/home/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/home/css/owl.theme.default.min.css') }}" />
    <!--font awsome-->
    <link href="{{ asset('assets/home/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!--aos animation-->
    <link href="{{ asset('assets/home/css/aos.css') }}" rel="stylesheet" />

    {{-- Select2 --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">

    <!-- toaster -->
    <link rel="stylesheet" href="{{ asset('assets/home/css/toastr.min.css') }}" />

    <!-- style -->
    @if (App::getLocale() == 'english')
        <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}" />
    @endif
    @if (App::getLocale() == 'urdu' || App::getLocale() == 'arabic')
        <link rel="stylesheet" href="{{ asset('assets/home/css/ur-ar-style.css') }}" />
    @endif

    <!-- for sweet alert  -->
    <link rel="stylesheet" href="{{ url('user/css/mustafai.css') }}" />
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/home/css/app-style.css') }}" />
    @stack('header-scripts')

</head>

<body class="{{ App::getLocale() != 'english' ? 'ur-ar-version' : 'en-version' }}">
    <div class="wrapper">
        @if (request()->route()->getPrefix() !== 'api')
            <div class="whatsp-btn-wraper">
                <a target="_blank"
                    href="https://api.whatsapp.com/send?phone={{ str_replace(' ', '', getSettingDataHelper('whatsapp')) }}&text=Welcome to Mustafai business account how can we help you"
                    class="whatsapp-button"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
            </div>
        @endif
        <!-- Preloader -->
        @if (request()->route()->getPrefix() !== 'api')
            <div class="preloader flex-column justify-content-center align-items-center">
                <img loading="lazy" class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}"
                    alt="Mustafai Dashboard">
            </div>
        @endif
        @if (request()->route()->getPrefix() !== 'api')
            @include('home.sections.header')
        @endif
        <main>
            @yield('content')
        </main>
        {{-- @if (request()->route()->getPrefix() !== 'api') --}}
        @if (request()->route()->getPrefix() !== 'api')
            @include('home.sections.footer')
        @endif
    </div>

    <script>
        var URLS = {
            featuredonprod: "{{ url('get-feature-donation-product') }}",
            donateNow: "{{ url('donate') }}",
            loginUrl: "{{ route('login') }}",
            contactUsUrl: "{{ url('/contact-form') }}",
            prayerTime: "{{ url('get-prayer-times') }}",
            userDashboard: "{{ url('user/dashboard') }}",
            RegisgerURL: "{{ route('register') }}",
            afterRegister: "{{ url('/after-register-message') }}",
            donationPaymentMethod: "{{ url('/get-payment-method') }}",
            cabinetUserroles: "{{ url('/cabinet-user-roles') }}",
            userProfile: "{{ url('user/profile') }}",
            validateRegister: "{{ url('validate-register') }}",
            baseurl: "{{ url('/') }}",
        }
    </script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('assets/home/js/jquery.js') }}"></script>
    <script defer src="{{ asset('assets/home/js/bootstrap-5.min.js') }}"></script>
    <!--aos script-->
    <script defer src="{{ asset('assets/home/js/aos.js') }}"></script>
    <!--owl script-->
    <script defer src="{{ asset('assets/home/js/script.js') }}"></script>
    <script defer src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ asset('assets/home/js/owl.carousel.min.js') }}"></script>
    <script defer src="{{ asset('assets/home/js/custom.js') }}"></script>



    <!-- For scrolling -->

    @isset($sections)

        @if ($sections->count() >= 3)
            <script defer src="{{ asset('/js/gsap.min.js') }}"></script>
            <script defer src="{{ asset('js/ScrollMagic.min.js') }}"></script>
            <script defer src="{{ asset('js/debug.addIndicators.min.js') }}"></script>
            <script defer src="{{ asset('js/animation.gsap.min.js') }}"></script>
            <script defer src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
            <script defer src="{{ asset('js/TimelineMax.min.js') }}"></script>
        @endif

    @endisset

    <!-- javascript -->
    <script src="{{ asset('assets/admin/jquery-validation/jquery.validate.min.js') }}"></script>

    <script defer src="{{ asset('assets/home/sweetalert.js') }}"></script>

    <script defer src="{{ asset('assets/home/js/common.js') }}"></script>

    <script defer src="{{ asset('assets/home/js/html-magnifier.js') }}"></script>

    <script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>

    <!-- toaster -->

    <script defer src="{{ asset('assets/home/js/toastr.min.js') }}"></script>

    <script type="text/javascript">
        @if (session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif
    </script>

    @include('common-script.validate-localization')
    @include('home.scripts.app-script')
    @include('home.scripts.phone-code-script')
    @stack('footer-scripts')
</body>

</html>
