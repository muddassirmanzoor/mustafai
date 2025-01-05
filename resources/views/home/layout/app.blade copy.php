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
    <meta property="og:url" content="https://mustafai.pk" />
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
    @if(App::getLocale() == 'english')
    <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}" />
    @endif
    @if(App::getLocale() == 'urdu' || App::getLocale() == 'arabic')
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

<body class="{{(App::getLocale() != 'english') ? 'ur-ar-version':'en-version'}}">
    <div class="wrapper">
        <div class="whatsp-btn-wraper">
            <a target="_blank" href="https://api.whatsapp.com/send?phone={{str_replace(" ",'',getSettingDataHelper('whatsapp'))}}&text=Welcome to Mustafai business account how can we help you" class="whatsapp-button"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
        </div>
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}" alt="Mustafai Dashboard">
        </div>
        @include('home.sections.header')
        <main>
            @yield('content')
        </main>
        @include('home.sections.footer')
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
            validateRegister: "{{url('validate-register')}}",
            userDashboard: "{{url('user/dashboard')}}",
            baseurl: "{{url('/')}}",
        }

  </script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('assets/home/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/home/js/bootstrap-5.min.js') }}"></script>
    <!--aos script-->
    <script src="{{ asset('assets/home/js/aos.js') }}"></script>
    <!--owl script-->
    <script src="{{ asset('assets/home/js/script.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ asset('assets/home/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/home/js/custom.js') }}"></script>


    <!-- For scrolling -->

    <script src="{{ asset('js/gsap.min.js') }}" ></script>
    <script src="{{ asset('js/ScrollMagic.min.js') }}"></script>
    <script src="{{ asset('js/debug.addIndicators.min.js') }}"></script>
    <script src="{{ asset('js/animation.gsap.min.js') }}"></script>
    <script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('js/TimelineMax.min.js') }}"></script>

    <!-- javascript -->
    <script src="{{ asset('assets/admin/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('assets/home/sweetalert.js') }}"></script>

    <script src="{{ asset('assets/home/js/common.js') }}"></script>

    <script src="{{ asset('assets/home/js/html-magnifier.js') }}"></script>

    <script src="{{asset('assets/admin/select2/js/select2.js')}}"></script>

    <!-- toaster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script type="text/javascript">
        //whatsapp button
        // var url = 'https://wati-integration-service.clare.ai/ShopifyWidget/shopifyWidget.js?11874';
        // var s = document.createElement('script');
        // s.type = 'text/javascript';
        // s.async = true;
        // s.src = url;
        // var options = {
        //     "enabled": true,
        //     "chatButtonSetting": {
        //         "backgroundColor": "#4dc247",
        //         "ctaText": "",
        //         "borderRadius": "25",
        //         "marginLeft": "0",
        //         "marginBottom": "50",
        //         "marginRight": "50",
        //         "position": "right",
        //         "right": '30',
        //         "top": '480',
        //     },
        //     "brandSetting": {
        //         "brandName": "Al Mustafai",
        //         "brandSubTitle": "Typically replies within a day",
        //         "brandImg": "https://mustafaidev.server18.arhamsoft.info/assets/admin/dist/img/pre-loader.png",
        //         "welcomeText": "Hi there!\nHow can I help you?",
        //         "messageText": "Hello, I have a question about",
        //         "backgroundColor": "#0a5f54",
        //         "ctaText": "Start Chat",
        //         "borderRadius": "25",
        //         "autoShow": false,
        //         "phoneNumber": "{{getSettingDataHelper('whatsapp')}}"
        //     }
        // };
        // s.onload = function() {
        //     CreateWhatsappChatWidget(options);
        // };
        // var x = document.getElementsByTagName('script')[0];
        // x.parentNode.insertBefore(s, x);
        // end of whatsapp button


        // @if(session('success'))
        // toastr.options = {
        //     "closeButton": true,
        //     "progressBar": true
        // }
        // toastr.success("{{ session('success') }}");
        // @endif

        // @if(session('error'))
        // toastr.options = {
        //     "closeButton": true,
        //     "progressBar": true
        // }
        // toastr.error("{{ session('error') }}");
        // @endif
    </script>

    <script>
        /* global HTMLMagnifier */
        $("#magnifier").click(function() {
            const magnifier = new HTMLMagnifier({
                width: 500
            });
            magnifier.on('syncScrollBars', function(magnifierContent) {
                $('div.scrollable-area', magnifierContent).scrollTop($('div.scrollable-area').scrollTop());
            });
            magnifier.show();
        });

        $(document).ready(function() {
            $('.preloader').fadeOut(500, function() {
                $('.preloader').hide();
            });
        })
    </script>

    @include('common-script.validate-localization')

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
            validateRegister: "{{url('validate-register')}}",
            userDashboard: "{{url('user/dashboard')}}",
            baseurl: "{{url('/')}}",
        }
    </script>

    <script>
        $(document).ready(function() {
            // window.onload = function() {
            //     $('.preloader').fadeOut(500, function() {
            //         $('.preloader').hide();
            //     });
            // }
            var headlineItems = $('.ticker_item li a').length;
            if (headlineItems = 1) {
                $('.ticker-wrap .ticker').css('-webkit-animation-duration', '30s');
                $('.ticker-wrap .ticker').css('animation-duration', '30s');
            }
            if (headlineItems <= 3) {
                $('.ticker-wrap .ticker').css('-webkit-animation-duration', '35s');
                $('.ticker-wrap .ticker').css('animation-duration', '35s');
            } else if (headlineItems <= 6) {
                $('.ticker-wrap .ticker').css('-webkit-animation-duration', '50s');
                $('.ticker-wrap .ticker').css('animation-duration', '50s');
            } else if (headlineItems <= 10) {
                $('.ticker-wrap .ticker').css('-webkit-animation-duration', '60s');
                $('.ticker-wrap .ticker').css('animation-duration', '60s');
            }
            window.onscroll = function() {
                if (document.body.scrollTop > 300) {
                    $('.top-header').css('display', 'none');
                    $('.ticker-wrapper-h').css('display', 'none');
                } else {
                    $('.top-header').css('display', 'block');
                    $('.ticker-wrapper-h').css('display', 'block');
                }
            };


            $('#login_form').validate();
            var url = "{{ URL('change-lang') }}";

            $(".changeLang").on('click', function() {
                window.location.href = url + "?lang=" + $(this).find('.lang-name').attr('data-val');
            });
        });

        $('.change-ourself').attr('style', 'background-image : url({{ asset("images/blue-bg.png") }}) !important;');
        $('section.serving-humanity').attr('style',
            'background: #f4f6fa !important;background-image: url({{ asset("images/library_images.serving-humanity-bg.png") }}) !important;'
        );
    </script>

    <!--aos initialize-->
    <script>
        $(".search-icon").click(function() {
            $(".search-wrapper").toggleClass("active");
        });

        $(".item.selected").click(function() {
            $(".font-wrapper").addClass("active");
        });
        $(".close-icon").click(function() {
            $(".font-wrapper").removeClass("active");
        });
        $("#owl-one, #owl-two, #owl-three").owlCarousel({
            center: true,
            items: 1,
            dots: false,
            nav: false,
            loop: true,
            margin: 30,
            responsive: {
                575: {
                    items: 2,
                },
                991: {
                    items: 4,
                },
                1300: {
                    items: 5,
                },
            },
        });
        $("#owl-four").owlCarousel({
            items: 1,
            center: true,
            dots: false,
            nav: true,
            loop: true,
            margin: 10,
        });
        $("#owl-five").owlCarousel({
            center: true,
            items: 2,
            dots: false,
            nav: false,
            loop: true,
            margin: 10,
            responsive: {
                600: {
                    items: 1,
                },
            },
        });
        $(".timeline_carousal").owlCarousel({
            items: 1,
            center: true,
            dots: false,
            nav: true,
            loop: true,

        });
        $(document).ready(function() {
            var sync1 = $("#sync1");
            var sync2 = $("#sync2");
            var slidesPerPage = 5; //globaly define number of elements per page
            var syncedSecondary = true;

            sync1
                .owlCarousel({
                    lazyLoad: true,
                    items: 1,
                    slideSpeed: 2000,
                    dots: false,
                    nav: false,
                    autoplay: false,
                    dots: true,
                    loop: true,
                    responsiveRefreshRate: 200,
                    autoplayTimeout: 7000,
                    smartSpeed: 800,
                })
                .on("changed.owl.carousel", syncPosition);

            sync2
                .on("initialized.owl.carousel", function() {
                    sync2.find(".owl-item").eq(0).addClass("current");
                })
                .owlCarousel({
                    items: 4,
                    items: slidesPerPage,
                    dots: false,
                    nav: false,
                    autoplayTimeout: 7000,
                    smartSpeed: 800,
                    slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                    responsiveRefreshRate: 100,
                })
                .on("changed.owl.carousel", syncPosition2);

            function syncPosition(el) {
                var count = el.item.count - 1;
                var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }

                //end block

                sync2.find(".owl-item").removeClass("current").eq(current).addClass("current");
                var onscreen = sync2.find(".owl-item.active").length - 1;
                var start = sync2.find(".owl-item.active").first().index();
                var end = sync2.find(".owl-item.active").last().index();

                if (current > end) {
                    sync2.data("owl.carousel").to(current, 100, true);
                }
                if (current < start) {
                    sync2.data("owl.carousel").to(current - onscreen, 100, true);
                }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    sync1.data("owl.carousel").to(number, 100, true);
                }
            }

            sync2.on("click", ".owl-item", function(e) {
                e.preventDefault();
                var number = $(this).index();
                sync1.data("owl.carousel").to(number, 300, true);
            });
        });
        $(document).ready(function() {

            $('.client-single').on('click', function(event) {
                event.preventDefault();

                var active = $(this).hasClass('active');

                var parent = $(this).parents('.testi-wrap');

                if (!active) {
                    var activeBlock = parent.find('.client-single.active');

                    var currentPos = $(this).attr('data-position');

                    var newPos = activeBlock.attr('data-position');

                    activeBlock.removeClass('active').removeClass(newPos).addClass('inactive').addClass(
                        currentPos);
                    activeBlock.attr('data-position', currentPos);

                    $(this).addClass('active').removeClass('inactive').removeClass(currentPos).addClass(
                        newPos);
                    $(this).attr('data-position', newPos);

                }
            });

        }(jQuery));

        $(document).ready(function() {
            $("#adropDown").click(function() {
                $(".about-drop-down").toggleClass("drop-down--active");
            });
        });

        $(document).ready(function() {
            $("#moredropDown").click(function() {
                $(".more-drop-down").toggleClass("drop-down--active");
            });
        });

        $(document).ready(function() {
            $("#adropDown").click(function() {
                $(".about-drop-down").toggleClass("drop-down--active");
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $(document).on("click", "#subBtn", function() {
                // alert("adsfds");
                let email = $('#email_subscription').val();
                if (/^[a-z0-9][a-z0-9-_\.]+@([a-z]|[a-z0-9]?[a-z0-9-]+[a-z0-9])\.[a-z0-9]{2,10}(?:\.[a-z]{2,10})?$/.test(email)) {

                    if (email !== '') {
                        // alert("not empty")
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('subscription') }}",
                            data: {
                                email: email
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                // $(".button").button("disable");
                                $('.preloader').show();
                            },
                            success: function(data) {
                                if (data == 1) {
                                    swal.fire(AlertMessage.done, AlertMessage.subscription, "success");
                                } else {
                                    swal.fire(AlertMessage.oops, AlertMessage.emailexist, "error");
                                }
                                $('#email_subscription').val('')
                                $('.preloader').hide();

                            }
                        });
                    } else {
                        swal.fire(`{{__('app.empty-email')}}`, `{{__('app.please-enter-email')}}`, "error")
                    }
                } else {
                    swal.fire(`{{__('app.invalid-email')}}`, ``, "error")
                }
            });
            var next, prev;
            next = function() {
                return $('.event-item:first-child').addClass('swing').fadeOut(700, 'swing', function() {
                    $('.event-item:first-child').hide().appendTo('.event-slides').removeClass('swing');
                }).fadeIn(700, 'swing');
            };
            prev = function(classname) {
                return $('.event-item:first-child').addClass('swing-rev').fadeOut(700, 'swing', function() {
                    $('.event-item:first-child').removeClass('swing-rev');
                    $('.event-item:last-child').prependTo('.event-slides').fadeIn(700, 'swing');
                }).fadeIn(700, 'swing');
            };
            $('.next').click(function() {
                return next();
            });
            $('.prev').click(function() {
                var classname = $(".event-item:first-child").data('id');
                return prev(classname);
            });
        });
        $(document).ready(function() {
            //for set nav bar active and deactive
            var data_url = `{{request()->url()}}`;
            var split_url_data = data_url.split(`{{url('/')}}`)[1].replaceAll("/", "");
            // alert(split_url_data);
            if (split_url_data == '') {
                $("#data_").addClass('active');
            } else if ($('#data_' + split_url_data).attr('data-info') == 'simple_link') {
                $('#data_' + split_url_data).addClass('active');
            } else if ($('#data_' + split_url_data).attr('data-info') == 'drop_dowm') {
                $('#data_' + split_url_data).parent().parent().parent().siblings('.drop-down__button').find('.for_active_drop').addClass('active');
            }
        })
    </script>

    <script>
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            if ($(this).hasClass('fa-eye-slash')) {
                $(this).parent('.div-custom').find('input').attr('type', 'text')
            } else {
                $(this).parent('.div-custom').find('input').attr('type', 'password')
            }
        });
    </script>

    <script>
        $("#login-sidebar-btn").click(function() {
            // alert("hello");
            if ($('.login-sidebar:visible').length) {
                if (!$('head > script[src="https://www.google.com/recaptcha/api.js"]').length) {
                    $('head').append($('<script />').attr('src', 'https://www.google.com/recaptcha/api.js'));
                }
            }
        });

        //________________ this code for namaz R&D _____________//
        // const convertTime12to24 = (time12h) => {
        //     const [time, modifier] = time12h.split(' ');

        //     let [hours, minutes] = time.split(':');

        //     if (hours === '12') {
        //         hours = '00';
        //     }

        //     if (modifier === 'PM') {
        //         hours = parseInt(hours, 10) + 12;
        //     }

        //     return `${hours}:${minutes}`;
        // }

        // function currentDate(){

        //     var today = new Date();
        //     var dd = String(today.getDate()).padStart(2, '0');
        //     var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        //     var yyyy = today.getFullYear();

        // return  today = mm + '/' + dd + '/' + yyyy;
        // }

        // function diff_minutes(dt2, dt1)
        // {

        //     var diff =(dt2.getTime() - dt1.getTime()) / 1000;
        //     diff /= 60;
        //     return Math.abs(Math.round(diff));

        // }


        // function namazhighlighter(){
        //     alert("ok")
        //     // return 0;
        //     var time = new Date();
        //     var curentTime=time.toLocaleTimeString();
        //     // dt1 = new Date(`${currentDate()} ${curentTime}`);
        //     // alert(dt1); return 0;
        //     // alert(convertTime12to24(curentTime));
        //     $('.namazTimePara').each(function(){
        //         // alert($(this).parent());
        //         var nmaztime=$(this).html();
        //         var todaytime = new Date(`${currentDate()} ${curentTime}`);
        //         var nmazTimestring = new Date(`${currentDate()}  ${nmaztime}`);
        //         alert(diff_minutes(nmazTimestring, todaytime));
        //         // alert(dt2);
        //         // return 0;
        //         // console.log(diff_minutes(dt1, dt2));


        //     })
        //     // var end = '23:30';

        //     // s = start.split(':');
        //     // e = end.split(':');

        //     // min = e[1]-s[1];
        //     // hour_carry = 0;
        //     // if(min < 0){
        //     //     min += 60;
        //     //     hour_carry += 1;
        //     // }
        //     // hour = e[0]-s[0]-hour_carry;
        //     // diff = hour + ":" + min;
        // }
        // namazhighlighter();

        // })
        // $(document).on("click","#login-sidebar-bt",function() {

        // setTimeout("namazhighlighter()", 7000)

        function diff_minutes(dt2, dt1) {

            var diff = (dt2.getTime() - dt1.getTime()) / 1000;
            diff /= 60;
            return Math.abs(Math.round(diff));

        }
        // 2 minutes remaining in namaz api
        var currentdate = new Date();
        dt1 = new Date(currentdate); //curent time
        dt2 = new Date("December 1, 2022 11:13:00"); //namaz time
        // alert(diff_minutes(dt1, dt2));

        // function toHoursAndMinutes(totalMinutes) {
        // const hours = Math.floor(totalMinutes / 60);
        // const minutes = totalMinutes % 60;
        // return { hours, minutes };
        // }
    </script>
    @include('home.scripts.phone-code-script')
    @stack('footer-scripts')
</body>

</html>
