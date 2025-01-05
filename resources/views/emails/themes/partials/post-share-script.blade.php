<script>
    /* like post*/

    $(document).on('click', '.mustafai_timeline_share', function(event) {
        let postId = $(this).attr('data-post-id');

        let msg = `{{ __('app.post-shared-on-mustafai-timeline') }}`

        $.ajax({
            type: "POST",
            url: "{{ route('user.post.share') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'post_id': postId
            },
            success: function(result) {
                if(result.status === 200) {
                    Swal.fire(
                        '',
                        msg,
                        'success'
                    )
                }
                if(result.status === 0) {
                    swal.fire('Oops!', result.message, 'error')
                }
            }
        });
    });
</script>
