<script>

    let resume = '';
    let jobId = '';

    function applyNow(_this)
    {
        jobId = $(_this).attr('data-post-id');
    }

    $(document).on('click', '.apply_job_btn', function () {
        if($('#person-name').val() == '' || $('#person-experience').val() == '' || $('#person-age').val() == '' || (resume == '' && !$('.is_resume_exists').is(':checked')))
        {
            swal.fire('please fill all fields','', "error");
            // alert('please fill all fields!')
            return;
        }

        $('.apply_job_btn').prop('disabled', true);
        $('.apply_job_btn').css('opacity', '0.3');

        let formdata = new FormData();
        formdata.append('_token', "<?php echo e(csrf_token()); ?>");
        formdata.append('job_post_id', jobId);
        formdata.append('name', $('#person-name').val());
        formdata.append('experience', $('#person-experience').val());
        formdata.append('age', $('#person-age').val());
        formdata.append('is_resume', $('.is_resume_exists').val());
        formdata.append('resume', resume);
        $.ajax({
            type: "POST",
            url: "{{route('guest.apply-job')}}",
            processData: false,
            contentType: false,
            data: formdata,
            success: function(result) {
                let msg = `{{ __('app.applied-on-job-successfully') }}`
                if(result.status == 200)
                {
                    $('#applyJobModal .close').click();
                    $('#person-name').val('');
                    $('#person-experience').val('');
                    $('#person-age').val('');
                    resume = '';
                    Swal.fire(
                        '',
                        msg,
                        'success'
                    )
                }
                if(result.status == 0) {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Oops...',
                        text: `${result.message}`,
                        footer: ''
                    })
                }
                $('.apply_job_btn').prop('disabled', false);
                $('.apply_job_btn').css('opacity', '1');
            }
        });
    })

    // fill receipt input dynamically
    $(document).on('change', '#person-resume', function() {
        resume = this.files[0];
    })

    // append or remove div on the basis of resume checkbox
    $('.is_resume_exists').change(function () {
        if ($(this).is(':checked')) {
            $('.appended_resume_div').remove()
        }
        if (!$(this).is(':checked')) {
            $('.appended_resume_div').remove()
            $('.to_append_resume_div').append(`
             <div class="form-group mt-3 appended_resume_div">
                        <input type="file" class="form-control" name="resume" required>
                    </div>
            `)
        }
    })

</script>
