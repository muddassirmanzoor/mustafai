<script>
    let profileImage = '';
    let bannerImage = '';

    function validateCustomFormat(input) {
    // Define the regular expression pattern
    var pattern = /^\d{5}-\d{7}-\d{1}$/;

    // Test the input against the pattern
    return pattern.test(input);
    }

    function updateBanner() {
        if($('#banner_input')[0].files.length === 0) return Swal.fire(`{{__('app.select-image')}}`, '', 'error');
        // return alert('please select image');
        let formdata = new FormData();
        formdata.append('banner', bannerImage);
        formdata.append('_token', "{{csrf_token()}}");
        $.ajax({
            type: "POST",
            url: "{{route('user.banner')}}",
            processData: false,
            contentType: false,
            data: formdata,
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                $('#updateBannerModal .close').click();
                $('#updateBannerForm').trigger("reset");
                $('.user_banner').html('');
                $('.user_banner').html(result);
                $('.preloader').hide();
            }
        });
    }

    $('#profile_image').on('change', function () {
        profileImage = this.files[0];
    });

    $('#banner_input').on('change', function () {
        bannerImage = this.files[0];
    });

    function profileVisibility(_this)
    {
        $('.profile_visibility').css('display', 'none')
        $('.fetched_visibility_status').text($(_this).attr('data-text'))
        value = $(_this).attr('data-id')
        $.ajax({
            type: "POST",
            url: "{{route('user.visibility')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'visibility': value
            },
            success: function(result) {
                if(result.status === 200)
                {
                    $('#is_public').prop('disabled', false);

                    Swal.fire(AlertMessage.visibilityStatus , '', 'success');
                }
            }
        });
    }
    function userOccupation(userId){
            $.ajax({
                type: "POST",
                url: "{{route('user.show-occupation')}}",
                data: {'_token': "{{csrf_token()}}", user_id: userId},
                success: function(result) {
                    if (result.status === 200) {
                        $("#updateOccupationModal").modal('show');
                        $('.user_occupation').html(result.html);
                    }
                }
            });
    }
    function profileOccupation()
    {
        var formData = new FormData($('#userOcupationForm')[0]);
        $.ajax({
            type: "POST",
            url: "{{route('user.occupation')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                if(result.status === 200)
                {
                    Swal.fire(AlertMessage.success, '', 'success');
                }
                $('.preloader').hide();
                $("#updateOccupationModal").modal('hide');
            }
        });
        // $.ajax({
        //     type: "POST",
        //     url: "{{route('user.occupation')}}",
        //     data: {
        //         '_token': "{{csrf_token()}}",
        //         'occupation': value
        //     },
        //     success: function(result) {
        //         if(result.status === 200)
        //         {
        //             Swal.fire(AlertMessage.success, '', 'success');
        //         }
        //     }
        // });
    }

    function updateProfileImage() {
        if($('#profile_image')[0].files.length === 0) return Swal.fire(`{{__('app.select-image')}}`, '', 'error');
        let formdata = new FormData();
        formdata.append('profile_image', profileImage);
        formdata.append('_token', "{{csrf_token()}}");
        $.ajax({
            type: "POST",
            url: "{{route('user.image')}}",
            processData: false,
            contentType: false,
            data: formdata,
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                // console.log(result)
                    $('#updateProfileModal .close').click();
                    $('#updateProfileImageForm').trigger("reset");
                    $('.user_profile_image').html('');
                    $('.user_profile_image').html(result);
                    $('.preloader').hide();


            }
        });
    }

    function updateTagline()
    {
        if($('#tagline_english').val() === '' && $('#tagline_urdu').val() === '' && $('#tagline_arabic').val() === '') return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');
        $.ajax({
            type: "POST",
            url: "{{route('user.tagline')}}",
            data: $("#updateTaglineForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                if (result.status === 200) {
                    $('#updateTaglineModal .close').click();
                    $('#updateTaglineForm').trigger("reset");
                    $('.user_tagline').text(result.data);
                }
                $('.preloader').hide();
            }
        });

    }

    function updateUserName()
    {
        if($('#user_name_english').val() === '' && $('#user_name_urdu').val() === '' && $('#user_name_arabic').val() === '') return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');
        $.ajax({
            type: "POST",
            url: "{{route('user.username')}}",
            data: $("#updateUserNameForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                if (result.status === 200) {
                    // console.log(result.data);
                    $('#updateUserNameModal .close').click();
                    $('#updateUserNameForm').trigger("reset");
                    $('.user_name_data').text(result.data);
                    // $('#user_name_english').val(result.data)
                    Swal.fire(AlertMessage.success, '', 'success');
                }
                $('.preloader').hide();

            }
        });

    }
    function updateCNIC()
    {
        if($('#form_cnic').val() === '') return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');
        if(!validateCustomFormat($('#form_cnic').val())) return Swal.fire(`{{__('app.cnic-formate')}}`, '', 'error');
        $.ajax({
            type: "POST",
            url: "{{route('user.cnic')}}",
            data: $("#updateUserCnicForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                if (result.status === 200) {
                    // console.log(result.data);
                    $('#updateCnicModal .close').click();
                    $('#updateUserCnicForm').trigger("reset");
                    $('.user_cnic').text(result.data);
                    $('#form_cnic').val(result.data);
                    Swal.fire(AlertMessage.success, '', 'success');
                }
                $('.preloader').hide();

            }
        });

    }

    function updateAddress()
    {

        if(($('#address_english').val() === '' && $('#address_urdu').val() === '' && $('#address_arabic').val() === '')) return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');

        if ($('#cnic').val() === '') {
            return Swal.fire(`{{ __('app.cnic-required') }}`, '', 'error');
        }
        $('#updateAddressForm').validate();
        if ($('#updateAddressForm').valid()) {

            let data = $('#updateAddressForm').serialize();
            // if($('#address_input').val() === '') return alert('please input address');
            // $('#updateAddressForm').submit();
            $.ajax({
                type: "POST",
                url: "{{route('user.address')}}",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function(result) {
                    if (result.status === 200) {
                        $('#updateAddressModal .close').click();
                        $('#updateAddressForm').trigger("reset");
                        $('.user_address').text(result.data);
                        $('#address_input').val(result.data);

                        $('#address_english').val(result.userAddressData.address_english);
                        $('#address_urdu').val(result.userAddressData.address_urdu);
                        $('#address_arabic').val(result.userAddressData.address_arabic);
                        $('#postcode').val(result.userAddressData.postcode);

                        $('#permanent_address_english').val(result.userAddressData.permanent_address.permanent_address_english);
                        $('#permanent_address_urdu').val(result.userAddressData.permanent_address.permanent_address_urdu);
                        $('#permanent_address_arabic').val(result.userAddressData.permanent_address.permanent_address_arabic);
                        $('#postcode_permanent').val(result.userAddressData.permanent_address.postcode_permanent);
                        $('#cnic').val(result.userAddressData.cnic);
                        location.reload(true);
                        Swal.fire(AlertMessage.success, '', 'success');
                    }
                    $('.preloader').hide();
                }
            });
        }

    }

    function addExperience() {
        if(!$("#addExperienceForm input[name=title_english]").val() && !$("#addExperienceForm input[name=title_urdu]").val() && !$("#addExperienceForm input[name=title_arabic]").val()) { return Swal.fire(`{{__('app.one-title')}}`, '', 'error'); }
        if(!$("#addExperienceForm input[name=experience_company_english]").val() && !$("#addExperienceForm input[name=experience_company_urdu]").val() && !$("#addExperienceForm input[name=experience_company_arabic]").val()) { return Swal.fire(`{{__('app.one-company')}}`, '', 'error'); }
        if(!$("#addExperienceForm input[name=experience_location_english]").val() && !$("#addExperienceForm input[name=experience_location_urdu]").val() && !$("#addExperienceForm input[name=experience_location_arabic]").val()){ return Swal.fire(`{{__('app.one-location')}}`, '', 'error'); }
        if(!$("#addExperienceForm input[name=experience_start_date]").val() || !$('.is_currently_working').is(':checked') && !$("#addExperienceForm input[name=experience_end_date]").val()) { return Swal.fire(`{{__('app.fill-dates')}}`, '', 'error'); }
        $('.add_experience_button').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.experience')}}",
            data: $("#addExperienceForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                $('.add_experience_button').prop('disabled', false);
                $('#experienceModal .close').click();
                $('#addExperienceForm').trigger("reset");
                $('.experience_list').html('')
                $('.experience_list').html(result)
                $('.preloader').hide();

            }
        });
    }

    function editExperienceRequest()
    {
        if(!$("#editExperienceForm input[name=experience_company_english]").val() && !$("#editExperienceForm input[name=experience_company_urdu]").val() && !$("#editExperienceForm input[name=experience_company_arabic]").val()) { return Swal.fire(`{{__('app.one-company')}}`, '', 'error'); }
        if(!$("#editExperienceForm input[name=experience_location_english]").val() && !$("#editExperienceForm input[name=experience_location_urdu]").val() && !$("#editExperienceForm input[name=experience_location_arabic]").val()){ return Swal.fire(`{{__('app.one-location')}}`, '', 'error'); }
        if(!$("#editExperienceForm input[name=experience_start_date]").val() || !$('.is_currently_working').is(':checked') && !$("#editExperienceForm input[name=experience_end_date]").val()) { return Swal.fire(`{{__('app.fill-dates')}}`, '', 'error'); }
        $.ajax({
            type: "POST",
            url: "{{route('user.experience-edit')}}",
            data: $("#editExperienceForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                $('#editExperienceModal').modal('hide');
                $('.experience_list').html('')
                $('.experience_list').html(result)
                $('.preloader').hide();

            }
        });
    }

    function editEducationRequest()
    {
        if(!$("#editEducationModal input[name=institute_english]").val() && !$("#editEducationModal input[name=institute_urdu]").val() && !$("#editEducationModal input[name=institute_arabic]").val()) { return Swal.fire(`{{__('app.one-institute')}}`, '', 'error'); }
        if(!$("#editEducationModal input[name=degree_name_english]").val() && !$("#editEducationModal input[name=degree_name_urdu]").val() && !$("#editEducationModal input[name=degree_name_arabic]").val()) { return Swal.fire(`{{__('app.one-degree')}}`, '', 'error'); }
        if(!$("#editEducationModal input[name=start_date]").val() || !$("#editEducationModal input[name=end_date]").val()) { return Swal.fire(`{{__('app.degree-dates')}}`, '', 'error'); }
        $.ajax({
            type: "POST",
            url: "{{route('user.education-edit')}}",
            data: $("#editEducationForm").serialize(),
            success: function(result) {
                $('#editEducationModal').modal('hide');
                $('.education_list').html('')
                $('.education_list').html(result)
            }
        });
    }

    function editExperience(_this)
    {
        $('#editExperienceModal').modal('show');
        let id = $(_this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "{{route('user.experience-get')}}",
            data: {
                '_token': "{{csrf_token()}}",
                id: id,
            },
            success: function(result) {
               $('.edit_experience_section').html('');
               $('.edit_experience_section').html(result);
            }
        });
    }

    function addEducation() {
        if(!$("#addEducationForm input[name=institute_english]").val() && !$("#addEducationForm input[name=institute_urdu]").val() ) { return Swal.fire(`{{__('app.one-institute')}}`, '', 'error'); }
        if(!$("#addEducationForm input[name=degree_name_english]").val() && !$("#addEducationForm input[name=degree_name_urdu]").val() ) { return Swal.fire(`{{__('app.one-degree')}}`, '', 'error'); }
        if(!$("#addEducationForm input[name=start_date]").val() || !$("#addEducationForm input[name=end_date]").val()) { return Swal.fire(`{{__('app.degree-dates')}}`, '', 'error'); }
        $('.add_education_button').prop('disabled', true)
        $.ajax({
            type: "POST",
            url: "{{route('user.education')}}",
            data: $("#addEducationForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                $('.add_education_button').prop('disabled', false)
                $('#addEducationModal .close').click();
                $('#addEducationForm').trigger("reset");
                $('.education_list').html('')
                $('.education_list').html(result)
                $('.preloader').hide();

            }
        });
    }

    function editEducation(_this)
    {
        $('#editEducationModal').modal('show');
        let id = $(_this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "{{route('user.education-get')}}",
            data: {
                '_token': "{{csrf_token()}}",
                id: id,
            },
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                $('.edit_education_section').html('');
                $('.edit_education_section').html(result);
                $('.preloader').hide();

            }
        });
    }

    function deleteEducation(_this)
    {
        let id = $(_this).attr('data-id');

        $.ajax({
            type: "POST",
            url: "{{route('user.education-destroy')}}",
            data: {
                '_token': "{{csrf_token()}}",
                id: id
            },
            success: function(result) {
                $('.education_list').html('')
                $('.education_list').html(result)
            }
        });
    }

    function deleteExperience(_this)
    {
        let id = $(_this).attr('data-id');

        $.ajax({
            type: "POST",
            url: "{{route('user.experience-destroy')}}",
            data: {
                '_token': "{{csrf_token()}}",
                id: id
            },
            success: function(result) {
                $('.experience_list').html('')
                $('.experience_list').html(result)
            }
        });
    }

    $('#aboutMoadl').on('shown.bs.modal', function () {
        $.ajax({
            type: "GET",
            url: "{{route('user.about')}}",
            success: function(result) {
                $('#summernote').val(result.data)
            }
        });
    })

    function updateAbout()
    {
        if($('#summernote2').summernote('isEmpty') && $('#summernote').summernote('isEmpty') && $('#summernote1').val()==='')
        {
            return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error')
        }
        $('.about_close_button').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.about')}}",
            data: $("#aboutForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                // console.log(result.data.about);
                    $('.about_close_button').prop('disabled', false);
                    $('#aboutMoadl .close').click();
                    $('.about_section').html('')
                    $('.about_section').html(result.data)
                    $('.preloader').hide();

            }
        });
    }

    // posts carousal scripts

        AOS.init();
        $(document).ready(function () {
        //Open Drop Down
        $(".custom-select").click(function (e) {
            e.preventDefault();

            if ($(".custom-select-wrapper").hasClass("open-dropdown")) {
                $(".custom-select-wrapper").removeClass("open-dropdown");
                $(this).parent().parent().toggleClass("open-dropdown");
            } else {
                $(this).parent().parent().toggleClass("open-dropdown");
            }
        });
        $("html").click(function (event) {
        if ($(event.target).closest(".custom-select").length === 0) {
        $(".custom-select-wrapper").removeClass("open-dropdown");
    }
    });
    });
        $('.responsive').slick({
        dots: false,
        loop:true,
        infinite: false,
        arrows:true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
    {
        breakpoint: 1024,
        settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        arrows:true,
        dots: false
    }
    },
    {
        breakpoint: 600,
        settings: {
        slidesToShow: 2,
        slidesToScroll: 2
    }
    },
    {
        breakpoint: 480,
        settings: {
        slidesToShow: 1,
        slidesToScroll: 1
    }
    }
        ]
    });

    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    function mybtn() {
        var x = document.getElementById("myDIV2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }


    $(document).ready(function() {
        $('#summernote').summernote({
        height: 200,
        });
        $('#summernote1').summernote({
        height: 200,
        });
        $('#summernote2').summernote({
        height: 200,
        });

        $(".custom-select").click(function (e) {
            e.preventDefault();

            if ($(".custom-select-wrapper").hasClass("open-dropdown")) {
                $(".custom-select-wrapper").removeClass("open-dropdown");
                $(this).parent().parent().toggleClass("open-dropdown");
            } else {
                $(this).parent().parent().toggleClass("open-dropdown");
            }
        });
        $("html").click(function (event) {
            if ($(event.target).closest(".custom-select").length === 0) {
                $(".custom-select-wrapper").removeClass("open-dropdown");
            }
        });
    });
   function update_donor_details(){
    //   $('.about_close_button').prop('disabled', true);
        $.ajax({
            type: "GET",
            url: "{{route('user.donor')}}",
            success: function(result) {
                    // $('.about_close_button').prop('disabled', false);
                    // $('#donorModal .close').click();
                    $('.donor-modal-body').html('')
                    $('.donor-modal-body').html(result)
            }
        });
   }
   function create_resume_modal(){

        $.ajax({
                type: "GET",
                url: "{{route('user.create.resume')}}",
                beforeSend: function(){
                    showLoading()
                },
                success: function(result) {
                    $('.create-resume-body').html('')
                    $('.create-resume-body').html(result)
                    hideLoading()
                }
            });

   }
   $('#donor_form').validate()
   $('#skillModal').validate()
   $('#bloodForm').validate()
   function showLoading() {
    document.querySelector('#loading').classList.add('loading');
    document.querySelector('#loading-content').classList.add('loading-content');
    }

    function hideLoading() {
        document.querySelector('#loading').classList.remove('loading');
        document.querySelector('#loading-content').classList.remove('loading-content');
    }

    let tagInputEle = $('.tags-input');
    tagInputEle.tagsinput();
    $('#skillForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    function updateSkills()
    {
        if($('#skills_english').val() === '' && $('#skills_urdu').val() === '' && $('#skills_arabic').val() === '') return Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');
        $('.skill_close_button').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.skills')}}",
            data: $("#skillForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                // console.log(result);

                    $('.skill_close_button').prop('disabled', false);
                    $('#skillModal .close').click();
                    $('#skillsDiv').html('')
                    $('#skillsDiv').html(result);
                    $('.preloader').hide();
            }
        });
    }
    function updateBlood()
    {
        if($('#blood_group_english').val() === '' && $('#blood_group_urdu').val() === '' && $('#blood_group_arabic').val() === '') {
            Swal.fire(`{{__('app.please-fill-input-field')}}`, '', 'error');
            return false;
        }
        $('.blood_close_button').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('user.blood')}}",
            data: $("#bloodForm").serialize(),
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function(result) {
                    $('.blood_close_button').prop('disabled', false);
                    $('#bloodModal .close').click();
                    $('#blood_section').html('')
                    $('#blood_section').html(result.data);
                    $('.preloader').hide();

            }
        });
    }

    $(document).on('change', '.is_currently_working', function () {
        if(this.checked) {
            $('.experience_end_date_div').css('display', 'none')
            return;
        }
        $('.experience_end_date_div').css('display', 'block')
    });
   function parentFunction(_this){
    id=_this.attr('id');
    if ($('#'+id).is(":checked"))
    {
        $('.'+id).css('display','block');
    }
    else{
        $('.'+id).css('display','none');
        $('.'+id).find('input:checkbox').prop('checked', false);
    }
   }
   function otherProfessionFunction(_this){
    id=_this.attr('id');
    if ($('#'+id).is(":checked"))
    {
        $('#other-profession').css('display','block');
    }
    else{
        $('#other-profession').css('display','none');
    }
   }

    $(document).on("click",".btn-close",function() {
        $("#editEducationModal").modal('hide');
        $("#editExperienceModal").modal('hide');
        $("#updateOccupationModal").modal('hide');
    });
    // alert("ok");
    $('.image_only').change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var validImageTypes = ["image/jpeg", "image/png", "image/gif"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            Swal.fire(`{{__('app.select-image')}}`, '', 'error');
            $(this).val('');
        }
    });
    function endDatevalidation() {
        var minDate=$('#start_date_partial').val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $('#end_date_partial').attr('min', minDate);
        $('#end_date_partial').val('');
    }
    function endDatevalidationExperience() {
        var minDate=$('#experience_start_date_partial').val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $('#experience_end_date_partial').attr('min', minDate);
        $('#experience_end_date_partial').val('');
    }
</script>
