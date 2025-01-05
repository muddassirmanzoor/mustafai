<!DOCTYPE html>
<html>
    <head>

        <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.min.css')}}">
    
        <!-- toaster -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <!-- style -->
        @if($lang == 'english')
        <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}" />
        @endif
        @if($lang == 'urdu' || $lang == 'arabic')
        <link rel="stylesheet" href="{{ asset('assets/home/css/ur-ar-style.css') }}" />
        @endif
    
        <!-- for sweet alert  -->
    
    
        <link rel="stylesheet" href="{{ url('user/css/mustafai.css') }}" />
        <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    
        <style>
            .preloader {
                display: -ms-flexbox;
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
    
            .wa-chat-box-brand {
                object-fit: contain;
                padding: 5px;
            }
    
            /* for mobile screen hide magnifier */
            @media only screen and (max-width: 820px) {
                #magnifier {
                    display: none;
                }
            }
        </style>
        @stack('header-scripts')
    </head>
    <body class="{{($lang != 'english') ? 'ur-ar-version':'en-version'}}">
        <main>
            <input type="hidden" id="bg-img-blue" value="{{asset('images/blue-bg.png')}}">
            @if(!empty($page))
                <div class="">
                    <div class="container-fluid container-width">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="cms-page-title">
                                <h3 class="about-h-1 text-center">{{$page->title}}</h3>
                            </div>
                            <div class="cms-page-content">
                                {!! str_replace('src="http://127.0.0.1:8000','src="'.url('').'',$page->description)  !!}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="">
                    <div class="container-fluid container-width">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <h3 class="about-h-1 text-center">{{ __('app.sorry') }}</h3>
                            <p class="about-t-1 text-center">
                                {{ __('app.no-page-found') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </body>
</html>
