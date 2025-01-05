<script src="{{ asset('js/jquery.countdown.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>


<script>

    $('[data-countdown]').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(`
                <ul>
                                    <li>
                                        <span id="days">
                                            <h2>${event.strftime('%D')}</h2>
                                        </span>days
                                    </li>
                                    <li>
                                        <span id="hours">
                                            <h2>${event.strftime('%H')}</h2>
                                        </span>Hours
                                    </li>
                                    <li>
                                        <span id="minutes">
                                            <h2>${event.strftime('%M')}</h2>
                                        </span>Minutes
                                    </li>
                                    <li>
                                        <span id="seconds">
                                            <h2>${event.strftime('%S')}</h2>
                                        </span>Seconds
                                    </li>
                                </ul>
            `);
        });
    });


    $(document).on('click', '.comment_button', function () {
        let postId = $(this).attr('data-post-id');
        $('[data-comment-div="' + postId + '"]').toggle("slide");
    });

    $(document).on('click', '.read_comments', function (event) {
        let postId = $(this).attr('data-post-id');
        $('[data-comments-list="' + postId + '"]').toggle("slide");
    });

    $(document).on('click', '.like-icon', function (event) {
        let likeIcon = $(this);
        let postId = $(this).attr('data-post-id');
        let userId = $('#user-id').val();

        likeIcon.css({
            'pointer-events': 'none',
            'opacity': '0.5'
        })

        $.ajax({
            type: "POST",
            url: "{{route('user.post.like')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'user_id': userId,
                'post_id': postId
            },
            success: function (result) {
                var like = result.data > 1 ? `{{ __('app.likes') }}` : `{{ __('app.like') }}`;
                if (result.status === 200) {
                    likeIcon.removeClass('text-secondary');
                    likeIcon.addClass('text-green');
                    likeIcon.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    })
                    $('[data-likes-counter="' + postId + '"]').text(`${result.data} ${like}`)
                }
                if (result.status === 204) {
                    likeIcon.removeClass('text-green');
                    likeIcon.addClass('text-secondary');
                    likeIcon.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    })
                    $('[data-likes-counter="' + postId + '"]').text(`${result.data} ${like}`)
                }
            }
        });
    });

    $(document).on('keypress', '.comment_input', function (event) {
        let commentInput = $(this);
        let postId = commentInput.attr('data-comment-id');
        if (event.which === 13) {
            commentInput.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "{{route('user.post.comment')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'post_id': postId,
                    'comment': commentInput.val()
                },
                success: function (result) {
                    var comment = result.data > 1 ? `{{ __('app.comments') }}` : `{{ __('app.comment') }}`;
                    if (result.status === 200) {
                        let dataCommentList = $('[data-comments-list="' + postId + '"]');
                        dataCommentList.css('display', 'block').append(`<li>${commentInput.val()}</li>`)
                        commentInput.val('');
                        $('[data-comments-counter="' + postId + '"]').text(`${result.data} ${comment}`)
                    }
                    commentInput.prop('disabled', false);
                }
            });
        }
    });

    $(document).on('click', '.send_comment', function (event) {
        let commentInput = $(this).prev();
        let postId = commentInput.attr('data-comment-id');
        let userId = $('#user-id').val();
        commentInput.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.post.comment')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'post_id': postId,
                'comment': commentInput.val()
            },
            success: function (result) {
                var comment = result.data > 1 ? `{{ __('app.comments') }}` : `{{ __('app.comment') }}`;
                if (result.status === 200) {
                    let dataCommentList = $('[data-comments-list="' + postId + '"]');
                    dataCommentList.css('display', 'block').append(`<li>${commentInput.val()}</li>`)
                    commentInput.val('');
                    $('[data-comments-counter="' + postId + '"]').text(`${result.data} ${comment}`)
                }
                commentInput.prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.share_button', function () {
        let postId = $(this).attr('data-post-id');
        $('[data-share-div="' + postId + '"]').toggleClass("drop-down--active");
    });
    $(document).on('click', function (event) {
        if (!$(event.target).is('.share_button')) {
            $('.drop-down.drop-down--active').removeClass('drop-down--active');
        }
    });

    let userEmail = $('.emailEvent').val();

    $('.attende_email').keyup(function () {
        userEmail = $(this).val()
    });

    $('.submit_event').click(function () {
        debugger;
        let eventId = $('.attende_id').val();
        var mailPattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;

        if (!mailPattern.test(userEmail)) {
            swal.fire(`{{__('app.invalid-email')}}`,``, "error")
            // alert('not a valid e-mail address');
            return 1;
        }

        $('.submit_event').prop('disabled', true);

        $.ajax({
            type: "POST",
            data: {
                '_token': "{{csrf_token()}}",
                eventId: eventId,
                email: userEmail
            },
            url: "{{route('event.create')}}",
            success: function (result) {
            if (result.status === 200 ) {
                swal.fire(AlertMessage.event,'', "success");
                // alert(result.message)
                $('#eventModal').modal('hide')
                $('.close_event_modal').click()
            }
            if (result.status === 201 ) {
                        swal.fire(AlertMessage.emailexist,'', "error");
                        // alert(result.message)
                        $('#eventModal').modal('hide')
                    }else{
                        swal.fire(AlertMessage.success,'', "sucess");
                        // alert("event successfully submitted")
                    }
                $('.submit_event').prop('disabled', false);
                $('.close_event_modal').click()
            }
        });
    });


    $('.join_event_button').click(function () {
        let evendId = $(this).attr('data-event-id');
        $('.attende_id').val(evendId)
        // $('.attende_email').val('')
    });

    function fetchRealTimeLikes() {
        $.ajax({
            type: "GET",
            url: "{{route('likes.counter')}}",
            success: function (result) {
                if (result.status === 200) {
                    for (const post of result.data) {
                        var like = post.likes_count > 1 ? `{{ __('app.likes') }}` : `{{ __('app.like') }}`;
                        $('[data-likes-counter="' + post.id + '"]').text(`${post.likes_count} ${like}`);
                    }
                }
            }
        });
    }

    function fetchRealTimeComments() {
        $.ajax({
            type: "GET",
            url: "{{route('comments.info')}}",
            success: function (result) {
                console.log(result, 'foo barq');
                if (result.status === 200) {
                    for (const post of result.data) {
                        var comment = post.comments_count > 1 ? `{{ __('app.comments') }}` : `{{ __('app.comment') }}`;
                        $('[data-comments-counter="' + post.id + '"]').text(`${post.comments_count} ${comment}`);
                        if (post.comments_count > 0) {
                            $('[data-comments-list="' + post.id + '"]').html('');
                            for (const comment of post.comments) {
                                $('[data-comments-list="' + post.id + '"]').append(`<li><b>${comment.user.user_name ?? 'guest'}</b> ${comment.body}</li>`);
                            }
                        }
                    }
                }
            }
        });
    }

    // script for checking specific div is in viewport or not


    function inViewport(elem, callback, options = {}) {
        return new IntersectionObserver(entries => {
            entries.forEach(entry => callback(entry));
        }, options).observe(document.querySelector(elem));
    }

    inViewport('.posts_section', element => {
        // element is in viewport
        // console.log('in viewport')

        const myInterval = setInterval(() => {
            // fetchRealTimeLikes();
            // fetchRealTimeComments();
        }, 3000)

    }, {
        root: document.querySelector('.scroll')
    });

    //end of script for checking specific div is in viewport or not

    /*load more posts scripts*/

    var page = 1;

    $('.timeline_posts').scroll(function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 1) {
            page++;
            loadMoreData(page);
        }
    });

    function loadMoreData(page) {
        $.ajax({
            url: '/more-posts/?page=' + page,
            type: "get",
            beforeSend: function () {
                // $('.ajax-load').show();
            }
        })
            .done(function (data) {
                if (data.html == "") {
                    // $('.ajax-load').html("No more records found");
                    return;
                }

                $('.ajax-load').hide();
                $("#timeline_posts").append(data.html);

                $(".dynamic_owl").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    items: 1,
                    nav: true,
                    dots: false,

                });
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                //Swal.fire(`{{__('app.server-issue')}}`, '', 'error');
                // alert('server not responding...');
            });
    }

</script>
