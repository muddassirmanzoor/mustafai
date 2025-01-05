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
    });
    //header menu
    $(document).ready(function() {
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

    $('.change-ourself').attr('style', 'background-image : url({{ asset('images/blue-bg.png') }}) !important;');
    $('section.serving-humanity').attr('style',
        'background: #f4f6fa !important;background-image: url({{ asset('images/library_images.serving-humanity-bg.png') }}) !important;'
    );

    $(".search-icon").click(function() {
        $(".search-wrapper").toggleClass("active");
    });

    $(".item.selected").click(function() {
        $(".font-wrapper").addClass("active");
    });
    $(".close-icon").click(function() {
        $(".font-wrapper").removeClass("active");
    });
    $('#foundations-carousel').owlCarousel({
        loop: true,
        margin: 20,
        dots: false,
        nav: false,
        items: 1,
        // autoplay:true,
        responsive: {
            575: {
                items: 2,
                nav: false,
            },
            768: {
                items: 3,
                nav: false,

            },
            1300: {
                items: 4,
                nav: false,
            },
        },
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
    $("#banner-slider").owlCarousel({
        items: 1,
        lazyLoad: true,
        slideSpeed: 2000,
        autoplay: true,
        center: true,
        dots: false,
        nav: false,
        loop: true,
        margin: 0,
        autoplayTimeout: 7000,
        smartSpeed: 800,
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
    $("#owl-eventslider").owlCarousel({
        items: 1,
        center: true,
        dots: false,
        nav: true,
        loop: true,
        margin: 10,
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
        if (typeof $('.client-single.active').attr('data-id') === "undefined") {
            return false;
        }
        let index = $('.client-single.active').attr('data-id').split("_")[1];
        $("#content_testimonial_" + index).removeClass('d-none');

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
                setTimeout(function() {
                    if ($('.client-single').hasClass('active')) {
                        $('.content-testimonial').addClass('d-none');
                        let index = $('.client-single.active').attr('data-id').split("_")[1];
                        $("#content_testimonial_" + index).removeClass('d-none');
                    }
                }, 1000);


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
            if (/^[a-z0-9][a-z0-9-_\.]+@([a-z]|[a-z0-9]?[a-z0-9-]+[a-z0-9])\.[a-z0-9]{2,10}(?:\.[a-z]{2,10})?$/
                .test(email)) {

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
                                swal.fire(AlertMessage.done, AlertMessage.subscription,
                                    "success");
                            } else {
                                swal.fire(AlertMessage.oops, AlertMessage.emailexist,
                                    "error");
                            }
                            $('#email_subscription').val('')
                            $('.preloader').hide();

                        }
                    });
                } else {
                    swal.fire(`{{ __('app.empty-email') }}`, `{{ __('app.please-enter-email') }}`,
                        "error")
                }
            } else {
                swal.fire(`{{ __('app.invalid-email') }}`, ``, "error")
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
        var data_url = `{{ request()->url() }}`;
        var split_url_data = data_url.split(`{{ url('/') }}`)[1].replaceAll("/", "");
        // alert(split_url_data);
        if (split_url_data == '') {
            $("#data_").addClass('active');
        } else if ($('#data_' + split_url_data).attr('data-info') == 'simple_link') {
            $('#data_' + split_url_data).addClass('active');
        } else if ($('#data_' + split_url_data).attr('data-info') == 'drop_dowm') {
            $('#data_' + split_url_data).parent().parent().parent().siblings('.drop-down__button').find(
                '.for_active_drop').addClass('active');
        }
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        if ($(this).hasClass('fa-eye-slash')) {
            $(this).parent('.div-custom').find('input').attr('type', 'text')
        } else {
            $(this).parent('.div-custom').find('input').attr('type', 'password')
        }
    });

    $("#login-sidebar-btn").click(function() {
        // alert("hello");
        if ($('.login-sidebar:visible').length) {
            if (!$('head > script[src="https://www.google.com/recaptcha/api.js"]').length) {
                $('head').append($('<script />').attr('src', 'https://www.google.com/recaptcha/api.js'));
            }
        }
    });

    function diff_minutes(dt2, dt1) {

        var diff = (dt2.getTime() - dt1.getTime()) / 1000;
        diff /= 60;
        return Math.abs(Math.round(diff));

    }
    // 2 minutes remaining in namaz api
    var currentdate = new Date();
    dt1 = new Date(currentdate); //curent time
    dt2 = new Date("December 1, 2022 11:13:00"); //namaz time

    if ($('.no-data-lib').length) {
        $('.mustfai-library').addClass('mustafai-library-section');
    }
</script>
