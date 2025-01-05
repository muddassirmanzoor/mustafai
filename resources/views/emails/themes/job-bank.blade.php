@extends('user.layouts.layout')
@section('content')
@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
<!-- tags input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<style>
    .bootstrap-tagsinput .tag {
        background: red;
        padding: 4px;
        font-size: 14px;
    }
    div#seekers-datatable_length {
        padding-top: 15px;
    }
    div#seekers-datatable_filter {
        margin-top: -33px;
    }
    div#hiring-datatable_length {
        padding-top: 15px;
    }
    div#hiring-datatable_filter {
        margin-top: -33px;
    }
    div#resume-datatable_length {
        padding-top: 15px;
    }
    div#resume-datatable_filter {
        margin-top: -33px;
    }
    .bootstrap-tagsinput .tag {
    background: red;
    padding: 6px;
    font-size: 14px;
    line-height: 38px;
}
</style>
@endpush
<div class="userlists-tab">
    <div class="list-tab">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @if(have_permission('View-Seeking-Job-Bank'))
                <button class="nav-link active show-tab" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-tab1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{__('app.job-seeking')}}</button>
                @endif
                @if(have_permission('View-Hiring-Job-Bank'))
                <button onclick="hiringDataTable()" class="nav-link show-tab
                @if(!have_permission('View-Seeking-Job-Bank')) active @endif"
                 id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-tab2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{__('app.job-hiring')}}</button>
                @endif
                @if(have_permission('View-CV-Resume-Applicants'))
                <button onclick="resumeDataTable()" class="nav-link
                @if(!have_permission('View-Seeking-Job-Bank') && !have_permission('View-Hiring-Job-Bank')) active @endif
                " id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-tab3" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">{{__('app.cv-or-resume')}}</button>
                @endif
            </div>
        </nav>
        <div class="table-form">
            <h5 class="form-title">{{__('app.list-of-candidates')}}</h5>
            <form class="form-list-sidebar">
                <div class="form-btn mt-lg-0 mt-3">
                    <button type="button" data-toggle="modal" data-target="#createJobPostModal" class="theme-btn-borderd-btn theme-btn text-inherit">{{__('app.make-a-post')}}</button>
                </div>
            </form>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show
            @if(have_permission('View-Seeking-Job-Bank')) active @endif" id="nav-tab1" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="users-table" id="style-2">
                    <table id="seekers-datatable" class="table border-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.id-num') }}</th>
                                <th scope="col">{{ __('app.name') }}</th>
                                <th scope="col">{{ __('app.occupation') }}</th>
                                <th scope="col">{{ __('app.skills') }}</th>
                                <th scope="col">{{ __('app.experience') }}</th>
                                <th scope="col">{{ __('app.title') }}</th>
                                <th scope="col">{{ __('app.resume') }}</th>
                                <th scope="col">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center justify-content-center my-xl-4 my-2">

                </div>
            </div>
            <div class="tab-pane
             @if(have_permission('View-Hiring-Job-Bank') && !have_permission('View-Seeking-Job-Bank')) active @endif"
              id="nav-tab2" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="users-table" id="style-2">
                    <table id="hiring-datatable" class="table border-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.id-num') }}</th>
                                <th scope="col">{{ __('app.name') }}</th>
                                <th scope="col">{{ __('app.company-name') }}</th>
                                <th scope="col">{{ __('app.occupation') }}</th>
                                <th scope="col">{{ __('app.skills') }}</th>
                                <th scope="col">{{ __('app.experience') }}</th>
                                <th>{{ __('app.title') }}</th>
                                <th scope="col">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center justify-content-center my-xl-4 my-2">

                </div>
            </div>
            <div class="tab-pane
            @if(have_permission('View-CV-Resume-Applicants') && !have_permission('View-Hiring-Job-Bank') && !have_permission('View-Seeking-Job-Bank')) active @endif"
             id="nav-tab3" role="tabpanel" aria-labelledby="nav-contact-tab">
                <div class="users-table" id="style-2">
                    <table id="resume-datatable" class="table border-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.id-num') }}</th>
                                <th scope="col">{{ __('app.summary') }}</th>
                                <th scope="col">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center justify-content-center my-xl-4 my-2">

                </div>
            </div>
            <div class="tab-pane fade" id="nav-tab4" role="tabpanel" aria-labelledby="nav-contact-tab">
                <div class="users-table" id="style-2">
                    <table class="table border-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.name') }}</th>
                                <th scope="col">{{ __('app.occupation') }}</th>
                                <th scope="col">{{ __('app.skills') }}</th>
                                <th scope="col">{{ __('app.experience') }}</th>
                                <th scope="col">Summary</th>
                                <th scope="col">Resume</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row" class="text-capitalize">
                                    <div class="d-flex align-items-center">
                                        <figure class="mb-0 me-2 user-img">
                                            <img src="./images/user.png" alt="" class="img-fluid">
                                        </figure>
                                        Abdul wali
                                    </div>
                                </td>
                                <td class="text-capitalize">teacher</td>
                                <td class="text-capitalize">drawing</td>
                                <td class="text-capitalize">2 years</td>
                                <td>Nunc Scelerisque Tincidunt Elit. Vestibulum Non Mi</td>
                                <td><i class="fa fa-download" aria-hidden="true"></i></td>
                            </tr>
                            <tr>
                                <td scope="row" class="text-capitalize">
                                    <div class="d-flex align-items-center">
                                        <figure class="mb-0 me-2 user-img">
                                            <img src="./images/user.png" alt="" class="img-fluid">
                                        </figure>
                                        Azeem ahmad
                                    </div>
                                <td class="text-capitalize">teacher</td>
                                <td class="text-capitalize">drawing</td>
                                <td class="text-capitalize">2 years</td>
                                <td>Nunc Scelerisque Tincidunt Elit. Vestibulum Non Mi</td>
                                <td><i class="fa fa-download" aria-hidden="true"></i></td>
                            </tr>
                            <tr>
                                <td scope="row" class="text-capitalize">
                                    <div class="d-flex align-items-center">
                                        <figure class="mb-0 me-2 user-img">
                                            <img src="./images/user.png" alt="" class="img-fluid">
                                        </figure>
                                        M Tariq
                                    </div>
                                </td>
                                <td class="text-capitalize">teacher</td>
                                <td class="text-capitalize">drawing</td>
                                <td class="text-capitalize">2 years</td>
                                <td>Nunc Scelerisque Tincidunt Elit. Vestibulum Non Mi</td>
                                <td><i class="fa fa-download" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create Job Post Modal -->
<div class="modal fade  library-detail common-model-style" id="createJobPostModal" tabindex="-1" role="dialog" aria-labelledby="createJobPostModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="createJobPostModal">{{__('app.create-post')}}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            @csrf
            <div class="modal-body">
                <form id="userPostForm" method="post" action="{{ route('user.create.job-post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="dash-comon-form">
                        <div class="form-group mb-2">
                            <label>{{__('app.title')}}<span class="text-red">*<span></label>
                            <input type="text" name="title_english" class="form-control post_title" placeholder="{{__('app.title')}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.occupation')}}<span class="text-red">*<span></label>
                            <input type="text" name="occupation" class="form-control post_title" placeholder="{{__('app.occupation')}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.experience')}}<span class="text-red">*<span></label>
                            <input type="text" name="experience" class="form-control  post_title" placeholder="{{__('app.experience')}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label> {{__('app.comma-seprated-skills')}}<span class="text-red">*<span></label>
                            <input style="width: 100%" id="tags-input" type="text" class="form-control" name="skills" placeholder="{{__('app.comma-seprated-skills')}}" required />
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.select-job-type')}}<span class="text-red">*<span></label>
                            <select name="job_type" class="form-control job_type" required>
                                <option value="" disabled selected hidden class="job-type-option-1">{{__('app.select-job-type')}}</option>
                                <option value="1">{{__('app.job-hiring')}}</option>
                                <option value="2">{{__('app.job-seeking')}}</option>
                            </select>
                        </div>
                        {{--<div class="apply_for_whom d-flex justify-content-between">
                            <div>
                                <label for="">Apply as yourself</label>
                                <input type="radio" name="apply_for_whom" value="you" checked>
                            </div>
                            <div>
                                <label for="">Apply for someone else</label>
                                <input type="radio" value="other" name="apply_for_whom">
                            </div>
                        </div>--}}
                        <div class="form-group mb-2">
                            <label>{{__('app.job-type')}} <span class="text-red"><span class="text-red">*<span><span></label>
                            <input type="text" name="job_seeker_or_hire_job_type"  class="form-control mt-2">
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.your-email')}}<span class="text-red">*<span></label>
                            <input type="email" name="job_seeker_or_hire_email"  class="form-control mt-2" value="{{ auth()->user()->email }}">
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.Phone-no')}}<span class="text-red">*<span></label>
                            <input type="number" name="job_seeker_or_hire_phone"  class="form-control mt-2" value="{{ auth()->user()->phone_number }}">
                        </div>
                        <div class="resume_div" style="display: none">
                            <div class="apply_from_profile">
                                <label>{{__('app.apply_prof')}}</label>
                                @php
                                    $isResumeExists = auth()->check() ? (auth()->user()->resume == null ? false : true) : false;
                                @endphp
                                <input class="is_resume_exists" type="checkbox" name="is_resume" {{ $isResumeExists ? '' : 'disabled' }}>
                                <br>
                                <small>{{ $isResumeExists ? '' : __('app.apply_prof_err')  }}</small>
                            </div>
                            <div class="to_append_resume_div">
                                <div class="form-group mb-2 appended_resume_div">
                                    <label>{{__('app.cv-or-resume')}}</label>
                                    <input type="file" name="resume" onchange="loadFile($(this),event)"  class="form-control mt-2 resume" accept="image/jpeg, image/png, application/pdf,application/msword">
                                    <small>jpg,png,pdf,docs</small>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label>{{__('app.your-name')}}</label>
                                <input type="text" name="job_seeker_name"  class="form-control mt-2" value="{{ auth()->user()->user_name }}">
                            </div>
                            <div class="form-group mb-2">
                                <label>{{__('app.currently-working')}}</label>
                                <input type="text" name="job_seeker_currently_working"  class="form-control mt-2">
                            </div>
                        </div>
                        <div class="hiring_div" style="display: none">
                            <div class="form-group mb-2">
                                <label>{{__('app.company-name')}}</label>
                                <input type="text" name="hiring_company_name"  class="form-control mt-2">
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label>{{__('app.summary')}}</label>
                            <textarea name="description_english" id="summary_limit" cols="30" rows="5" maxlength="200" class="form-control" style="resize: none;"></textarea>
                            <span class="pull-right label label-default" id="count_message" style="margin-right:5px;"></span>
                        </div>
                    </div>
                    <div class="modal-footer w-100">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="button" class="green-hover-bg theme-btn create_post">{{__('app.post')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--apply job modal-->
<div class="modal fade library-detail common-model-style" id="applyJobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.apply-for-job') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="dash-comon-form" action="{{ route('user.apply-job') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="job_post_input" name="job_post_id" value="">

                    <div class="apply_for_whom d-flex justify-content-between">
                        <div>
                            <label for="">{{__('app.apply-as-your-self')}}</label>
                            <input type="radio" name="apply_for_whom" value="you" checked>
                        </div>
                        <div>
                            <label for="">{{__('app.apply-for-someone-else')}}</label>
                            <input type="radio" value="other" name="apply_for_whom">
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-name') }}" name="name" required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-experience') }}" name="experience" required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-age') }}" name="age" required>
                    </div>
                    <div class="form-group mt-3 to_append_resume_div apply_from_profile_of_applying_job">
                        <label>{{__('app.apply-from-profile')}}</label>
                        @php
                         $isResumeExists = auth()->user()->resume == null ? false : true;
                        @endphp
                        <input class="is_resume_exists_of_applying_job"  type="checkbox" name="is_resume" {{ $isResumeExists ? '' : 'disabled' }}>

                        <br>

                        <small>{{ $isResumeExists ? '' : 'Can not apply from profile because you did not create resume from profile yet!' }}</small>
                    </div>
                    <div class="form-group mt-3 appended_resume_div_of_applying_job">
                        <input type="file" class="form-control" name="resume" onchange="loadFile($(this),event)" accept="image/jpeg, image/png, application/pdf,application/msword">
                        <small>jpg,png,pdf,docs</small>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="submit" class="green-hover-bg theme-btn">{{ __('app.apply-now') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- view applicants modal   -->

<div class="modal library-detail common-model-style fade" id="viewApplicantsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.applicants') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body to_append_applicants">
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade library-detail common-model-style" id="editPostModal" tabindex="-1" role="dialog"
aria-labelledby="editPostModal" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
   <div class="modal-content create-post-modal">
       <div class="modal-header">
           <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.edit-post') }}</h4>
           <button type="button" class="btn-close" data-dismiss="modal"></button>
       </div>
       <div class="edit_modal_body">
           {{-- dynamic content here --}}
       </div>
   </div>
</div>
</div>

@endsection

@push('scripts')
<!-- tags input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('assets/admin/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/admin/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/admin/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
@include('home.partial.edit-post-script')
<script>
    var allow=''
    var have_permision = "<?php echo have_permission('Export-Job-Bank'); ?>";
    let tagInputEle = $('#tags-input');
    tagInputEle.tagsinput();

    // arry[0].title = "{{__('app.list-of-candidates')}}";
    $('.job_type').on('change', function() {
        let jobType = $(this).val()
        if (jobType == 1) { // 1 = hiring
            $('.resume_div').css('display', 'none');
            $('.hiring_div').css('display', 'block');
        }
        if (jobType == 2) { // 2 = seeking
            // let applyForWhom = $('input[name=apply_for_whom]:checked', '#userPostForm').val();
            // if (applyForWhom === 'you') {
                $('.resume_div').css('display', 'block')
            // }
            $('.hiring_div').css('display', 'none')
        }
    });

    $('input[type=radio][name=apply_for_whom]').change(function() {
        if (this.value == 'you') {
            $('.apply_from_profile_of_applying_job').css('display', 'block')
        }
        else if (this.value == 'other') {
            $('.apply_from_profile_of_applying_job').css('display', 'none')
            $('.appended_resume_div_of_applying_job').css('display', 'block')
        }
    });

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

    // append or remove div on the basis of resume checkbox of applying job
    $('.is_resume_exists_of_applying_job').change(function () {
        if ($(this).is(':checked')) {
            $('.appended_resume_div_of_applying_job').css('display', 'none')
        }
        if (!$(this).is(':checked')) {
            $('.appended_resume_div_of_applying_job').css('display', 'block')
            /*$('.appended_resume_div').remove()
            $('.to_append_resume_div').append(`
             <div class="form-group mt-3 appended_resume_div">
                        <input type="file" class="form-control" name="resume" required>
                    </div>
            `)*/
        }
    })

    $(document).on('click', '.create_post', function (e) {
        let jobType = $('.job_type').val();
        $('#userPostForm').validate();
        if ($('#userPostForm').valid()) {
        if (jobType == 1) { // hiring
            if ( $('input[name="job_seeker_or_hire_email"]').val() == '' || $('input[name="occupation"]').val() == '' || $('input[name="experience"]').val() == '' || $('input[name="skills"]').val() == '' || $('input[name="hiring_company_name"]').val() == '' || $('input[name="job_seeker_or_hire_phone"]').val() == '' || $('input[name="job_seeker_or_hire_job_type"]').val() == '' || $.trim($('#summary_limit').val()).length < 1) {
                return Swal.fire(`Please fill all fields`, '', 'error');
            } else {
                return $('#userPostForm').submit()
            }
        }
        if (jobType == 2) { // seeking
            if ( $('input[name="job_seeker_or_hire_email"]').val() == '' || $('input[name="occupation"]').val() == '' || $('input[name="experience"]').val() == '' || $('input[name="skills"]').val() == '' || ( !$('.is_resume_exists').is(':checked') ? $('input[name="resume"]')[0].files.length === 0 : ''  ) || $('input[name="job_seeker_name"]').val() == ''  || $('input[name="job_seeker_or_hire_phone"]').val() == '' || $('input[name="job_seeker_currently_working"]').val() == '' || $('input[name="job_seeker_or_hire_job_type"]').val() == '' || $.trim($('#summary_limit').val()).length < 1) {
                return Swal.fire(`Please fill all fields`, '', 'error');
            } else {
                return $('#userPostForm').submit()
            }
        }

        return Swal.fire(`Please fill all fields`, '', 'error');
    }
    })

    function applyJob(_this) {
        $('#applyJobModal').modal('toggle');
        let jobPostId = $(_this).attr('data-post-id');
        $('.job_post_input').val(jobPostId)
    }

    function viewApplicants(_this) {
        let postId = $(_this).attr('data-post-id');

        $.ajax({
            type: "POST",
            url: "{{route('user.job-bank-applicants')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'post_id': postId
            },
            success: function(result) {
                if (result.status === 200) {
                    console.log(result, 'result')
                    $('.to_append_applicants').html('');
                    $('.to_append_applicants').html(result.data);
                }
            }
        });

        $('#viewApplicantsModal').modal('toggle')
    }

    <?php
    if(have_permission('View-Seeking-Job-Bank'))
    {
    ?>
        seekersDatatable();
    <?php
    }

    if(have_permission('View-Hiring-Job-Bank') && (!have_permission('View-Seeking-Job-Bank')))
    {
        ?>
        hiringDataTable();
        <?php
    }

    if(have_permission('View-CV-Resume-Applicants') && (!have_permission('View-Hiring-Job-Bank')) && (!have_permission('View-Seeking-Job-Bank')))
    {
    ?>
    resumeDataTable();
    <?php
    }
    ?>


    function seekersDatatable(){
    $(function() {
        if(have_permision){
            var allow_seeker=dataCustomizetbale("{{__('app.list-of-candidates-import')}}",  arrywidth= [ '8%', '16%', '16%', '24%', '12%', '20%'],  arraycolumn = [0,1,2,3,4,5],"{{__('app.list-of-candidates')}}");
            var dom='Blfrtip';
        }
        else{
            var dom='lfrtip'
        }
        // alert(arry[0].customize.doc.function(doc).content[1].table.widths)



        $('#seekers-datatable').dataTable({

            "language": {
                    "oPaginate": {
                        "sFirst":    `{{__('app.first')}}`,
                        "sLast":    `{{__('app.last')}}`,
                        "sNext":    `{{__('app.next')}}`,
                        "sPrevious": `{{__('app.previous')}}`,
                    },
                    "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                    "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                    "sSearch":  `{{__('app.search')}}`,
                    "sZeroRecords":   `{{__('app.no-data-available')}}`,
                    "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

            },
            sort: false,
            pageLength: 50,
            scrollX: true,
            processing: true,
            drawCallback: function() {
                hideOverlayLoader();
            },
            dom:dom ,
            buttons:allow_seeker,
            lengthMenu: [
                [5, 10, 25, 50, 100, 200, -1],
                [5, 10, 25, 50, 100, 200, "All"]
            ],
            serverSide: true,
            ajax: "{{ url('user/job-bank') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'occupation',
                    name: 'occupation',
                },
                {
                    data: 'skills',
                    name: 'skills'
                },
                {
                    data: 'experience',
                    name: 'experience'
                },
                {
                    data: 'title_english',
                    name: 'title_english'
                },
                {
                    data: 'resume',
                    name: 'resume'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt', function() {});
    });
}

    function showOverlayLoader() {}

    function hideOverlayLoader() {}

    function hiringDataTable() {
        $('#hiring-datatable').dataTable({

            "language": {
                    "oPaginate": {
                        "sFirst":    `{{__('app.first')}}`,
                        "sLast":    `{{__('app.last')}}`,
                        "sNext":    `{{__('app.next')}}`,
                        "sPrevious": `{{__('app.previous')}}`,
                    },
                    "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                    "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                    "sSearch":  `{{__('app.search')}}`,
                    "sZeroRecords":   `{{__('app.no-data-available')}}`,
                    "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

            },
            sort: false,
            pageLength: 50,
            scrollX: true,
            processing: false,
            destroy: true,
            drawCallback: function() {
                hideOverlayLoader();
            },
            responsive: false,
            dom: 'Blfrtip',
            buttons: arry,
            lengthMenu: [
                [5, 10, 25, 50, 100, 200, -1],
                [5, 10, 25, 50, 100, 200, "All"]
            ],
            serverSide: true,
            ajax: "{{ route('user.job-bank-hiring') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'hiring_company_name',
                    name: 'hiring_company_name'
                },
                {
                    data: 'occupation',
                    name: 'occupation'
                },
                {
                    data: 'skills',
                    name: 'skills'
                },
                {
                    data: 'experience',
                    name: 'experience'
                },
                {
                    data: 'title_english',
                    name: 'title'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt', function() {});
    }

        function resumeDataTable() {
            if(have_permision){
            var allow_resume=dataCustomizetbale("{{__('app.list-of-candidates-import')}}",  arrywidth= [ '30%', '70%'],  arraycolumn = [0,1],"{{__('app.list-of-candidates')}}");
            var dom='Blfrtip';
            }
            else{
                var dom='lfrtip'
            }
            $('#resume-datatable').dataTable({
                "language": {
                        "oPaginate": {
                            "sFirst":    `{{__('app.first')}}`,
                            "sLast":    `{{__('app.last')}}`,
                            "sNext":    `{{__('app.next')}}`,
                            "sPrevious": `{{__('app.previous')}}`,
                        },
                        "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                        "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                        "sSearch":  `{{__('app.search')}}`,
                        "sZeroRecords":   `{{__('app.no-data-available')}}`,
                        "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

                },
                sort: false,
                pageLength: 50,
                scrollX: true,
                processing: false,
                destroy: true,
                drawCallback: function() {
                    hideOverlayLoader();
                },
                responsive: false,
                dom: dom,
                buttons:allow_resume,
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,
                ajax: "{{ route('user.job-bank-resume') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title_english',
                        name: 'Job Summary'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt', function() {});
        }
        var text_max = 200;
        $('#count_message').html('0 / ' + text_max );

        $('#summary_limit').keyup(function() {
        var text_length = $('#summary_limit').val().length;
        var text_remaining = text_max - text_length;

        $('#count_message').html(text_length + ' / ' + text_max);
        });

        $("#nav-contact-tab").click(function(){
         $('.form-title').css('display','none');
         $('.text-inherit').css('display','none');
        });
        $(".show-tab").click(function(){
         $('.form-title').css('display','block');
         $('.text-inherit').css('display','block');
        });

        var loadFile = function(_this, event) {
            var file = $(_this).prop('files')[0];
            var allowedTypes = ["image/jpeg", "image/png", "application/pdf", "application/msword"];

            if (allowedTypes.includes(file.type)) {
                //ok
            } else {
                _this.val('');
                swal.fire(AlertMessage.invalid_file_type, AlertMessage.invalid_file_message, "error");
            }
        };

</script>
@include('user.scripts.font-script')

@endpush
