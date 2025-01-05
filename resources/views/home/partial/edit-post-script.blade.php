<script>
    $(document).on('click', '.edit_post', function () {
        let postId = $(this).attr('data-post-id');

        $.ajax({
            type: "POST",
            url: "{{route('user.post.show')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'post_id': postId
            },
            success: function(result) {
                if(result.status === 200)
                {
                    $('.edit_modal_body').html(result.data)
                }
            }
        });
    });

    $(document).on('click', '.delete_post', function () {
        let postId = $(this).attr('data-post-id')

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "{{route('user.post.delete')}}",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'post_id': postId
                    },
                    success: function(result) {
                        if(result.status === 1)
                        {
                            $('[data-main-post="' + postId + '"]').remove()

                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your post has been deleted.',
                                'success'
                            )
                        }
                    }
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your post is safe :)',
                    'error'
                )
            }
        })
    })
</script>
