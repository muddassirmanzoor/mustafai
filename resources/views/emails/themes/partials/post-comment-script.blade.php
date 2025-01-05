<script>
    $(document).on('click', '.comment_button', function() {
        let postId = $(this).attr('data-post-id');
        $('[data-comment-div="' + postId + '"]').toggle("slide");
    });

    $(document).on('click', '.send_comment', function(e) {
        elementSend=$(this);
        let userName = "{{ \Illuminate\Support\Facades\Auth::user()->user_name }}";
        let userPic = "{{ \Illuminate\Support\Facades\Auth::user()->profile_image }}";
        let postId = $(this).attr('data-post-id');
        let commentInput = $('[data-comment-id-input="' + postId + '"]');
        let commentVal = commentInput.val()
        commentInput.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.post.comment')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'post_id': postId,
                'comment': commentVal
            },
            beforeSend: function(msg){
                elementSend.attr("style","pointer-events: none")
                // ementSend { pointer-events: none; }
            },
            success: function(result) {
                var comment = result.data > 1 ? `{{ __('app.comments') }}` : `{{ __('app.comment') }}`;
                if (result.status === 200) {
                    let dataCommentList = $('[data-comments-list="' + postId + '"]');
                    dataCommentList.css('display', 'block').append(`<li class="d-flex justify-content-between align-items-center" data-comment-element="${result.commentId}"><div><img class="start-a-post-profile" src="{{ 'https://mustafaipks3bucket.s3.ap-southeast-1.amazonaws.com/' }}`+ userPic +`"  alt="" class="img-fluid"> <b>${userName}</b> ${commentInput.val()}</div><i data-comment-id="${result.commentId}" onclick="deleteComment(this)" style="cursor: pointer" class="fa fa-trash dell-btn"></i></li>`)
                    commentInput.val('');
                    $('[data-comments-counter="' + postId + '"]').text(`${result.data} ${comment}`)
                }
                if (result.status === 0) {
                    swal.fire('Oops!', result.message, 'error')
                }
                commentInput.prop('disabled', false);
                setTimeout(function(){
                    elementSend.attr("style","pointer-events: auto")
                }, 1000);

            }
        });
    });

    $(document).on('click', '.read_comments', function() {
        let postId = $(this).attr('data-post-id');
        $('[data-comments-list="' + postId + '"]').toggle("slide");
    });

    $(document).on('keypress', '.comment_input', function(event) {
        if (event.which === 13) {
            let commentInput = $(this);
            let sendCommentButton = commentInput.next();
            sendCommentButton.click()
        }
    });

</script>
