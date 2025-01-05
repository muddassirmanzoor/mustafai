<script>
    function deleteComment(_this) {
        let commentId = $(_this).attr('data-comment-id');

        $.ajax({
            type: "POST",
            url: "{{ route('user.post.comment.delete') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'comment_id': commentId,
            },
            success: function(result) {
                if (result.status === 200) {
                    $('[data-comment-element="' + commentId + '"]').remove();
                    Swal.fire(AlertMessage.done, AlertMessage.comment_delete, 'success');
                }
            }
        });
    }
</script>
