<script>
    /* like post*/

    $(document).on('click', '.like_post', function(event) {
        let likeIcon = $(this);
        let postId = $(this).attr('data-likes-counter');
        let userId = $('#user-id').val();

        likeIcon.css({
            'pointer-events': 'none',
            'opacity': '0.5'
        })

        $.ajax({
            type: "POST",
            url: "{{ route('user.post.like') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'user_id': userId,
                'post_id': postId
            },
            success: function(result) {
                var like = result.data > 1 ? `{{ __('app.likes') }}` : `{{ __('app.like') }}`;
                if (result.status === 200) {
                    // likeIcon.removeClass('text-red');
                    likeIcon.addClass('text-green');
                    likeIcon.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    })

                    $('[data-likes-counter="' + postId + '"]').html(`<span>${result.data} ${like}</span>`)
                }
                if (result.status === 204) {
                    likeIcon.removeClass('text-green');
                    // likeIcon.addClass('text-red');
                    likeIcon.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    })
                    // $('[data-likes-counter="' + postId + '"]').text(`${result.data} ${like}`)
                    $('[data-likes-counter="' + postId + '"]').html(`<span>${result.data} ${like}</span>`)
                }
                if(result.status === 0) {
                    swal.fire('{{ __('app.forbidden') }}', result.message, 'error')
                }
            }
        });
    });
</script>
