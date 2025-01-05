@extends('admin.layout.app')

<style>
    input[type="file"] {
        display: block;
    }

    .imageThumb {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
    }

    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
    }

    .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    .remove:hover {
        background: white;
        color: black;
    }
    .is_hidden_true {
        display: none;
    }
    .is_hidden_false {
        display: block;
    }
</style>

<!-- tags input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />

@push('header-scripts')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
    <!-- SummerNote -->
    <link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Post</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/posts') }}">Posts</a></li>
                            <li class="breadcrumb-item active">Create Post</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Post Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="post-form" action="{{ URL('admin/posts') }}" enctype="multipart/form-data" method="POST">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="action" value="{{$action}}">
                                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
                                    <div class="accordion" id="accordionExample">

                                        <div class="card">
                                            <!-- For  title   -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#title" aria-expanded="true"
                                                            aria-controls="title">
                                                        Title
                                                    </button>
                                                </h2>
                                            </div>

{{--                                            <div id="title" class="collapse" aria-labelledby="title-heading"--}}
{{--                                                 data-parent="#accordionExample">--}}
                                                <div class="card-body">

                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group ">
                                                                    <label>Title {{ $lang->name_english }} <span class="text-red">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Title {{ $lang->name_english }}" name="title_{{ $lang->key }}"
                                                                        value="{{ $row->{'title_'.$lang->key} }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
{{--                                            </div>--}}

                                            <div class="col-sm-4">
                                                <!-- text input -->
                                                <div class="form-group mt-5">
                                                    <label>Post Type <span class="text-danger">*</span></label>
                                                    @php
                                                        $currentRoute = Route::currentRouteName();
                                                    @endphp
                                                    @if($currentRoute == 'posts.edit')
                                                        <input type="hidden" name="post_type" value="{{ $row->post_type }}">
                                                    @endif
                                                    <select class="form-control" name="post_type"
                                                            id="post_type" {{ Route::currentRouteName() == 'posts.edit' ? 'disabled' : '' }}>
                                                        <option value="1" @if($row->post_type == '1') selected @endif>Simple Post</option>
                                                        <option value="2" @if($row->post_type == '2') selected @endif>Job Post</option>
                                                        <option value="3" @if($row->post_type == '3') selected @endif>Announcement</option>
                                                        <option value="5" @if($row->post_type == '5') selected @endif>Blood Post</option>
                                                    </select>
                                                    <small>{{ Route::currentRouteName() == 'posts.edit' ? 'disabled for edit action' : '' }}</small>
                                                </div>
                                            </div>


                                            {{-- if selected job type is 2--}}
                                            <div class="job_div {{ $row->job_type == 2 || $row->job_type == 1 ? 'is_hidden_false': 'is_hidden_true' }}" style="border: 2px dotted black">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Profession</label>
                                                            <input value="{{ $row->occupation }}" class="form-control" type="text" name="occupation">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Experience</label>
                                                            <input value="{{ $row->experience }}" class="form-control" type="text" name="experience">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Skills</label>
                                                            <input value="{{ $row->skills }}" class="form-control" type="text" name="skills">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Select Job Type</label>
                                                            <select name="job_type" class="form-control job_type">
                                                                <option value="">Select job type</option>
                                                                <option value="1" {{ $row->job_type == 1 ? 'selected' : '' }}>Hiring</option>
                                                                <option value="2" {{ $row->job_type == 2 ? 'selected' : '' }}>Seeking</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2 resume_div {{ $row->job_type == 2 ? 'is_hidden_false' : 'is_hidden_true' }}">
                                                            <label>Upload your resume</label>
                                                            <input type="file" name="resume" class="form-control mt-2 resume">
                                                            <small>jpg,png,pdf</small>
                                                            <small>
                                                                @php
                                                                    $currentRoute = Route::currentRouteName();
                                                                @endphp
                                                                @if($currentRoute == 'posts.edit')
                                                                    <a href="{{ getS3File($row->resume ?? '') }}">{{ __('app.download-resume') }}</a>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Summary</label>
                                                            <textarea name="description_english" cols="30" rows="5" class="form-control">{{ $row->description_english }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--blood post div--}}
                                            <div class="blood_div {{ $row->post_type == 5 ? 'is_hidden_false': 'is_hidden_true' }}" style="border: 2px dotted black">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="city">City</label>
                                                            {{-- <input value="{{ $row->city }}" type="text" name="city" id="city" class="form-control" placeholder="City"> --}}
                                                            {{--@if($row->post_type == 5 && $action == 'edit')--}}
                                                                <select name="city" id="" class="form-control">
                                                                    <option value="">{{ __('app.select-city') }}</option>
                                                                    @forelse($cities as $city)
                                                                        <option value="{{ $city->id }}" {{ $action == 'edit' && $row->post_type == 5 &&  $row->city == $city->id ? 'selected' : ''}}>{{ $city->name_english }}</option>
                                                                    @empty
                                                                        <option value="">{{ __('app.no-data') }}</option>
                                                                    @endforelse
                                                                </select>
                                                            {{--@endif--}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="hospital">Hospital</label>
                                                            <input value="{{ $row->hospital }}" type="text" name="hospital" id="hospital" class="form-control" placeholder="Hospital">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="address">Address</label>
                                                            <input value="{{ $row->address }}" type="text" name="address" id="address" class="form-control" placeholder="Complete address">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- For Post File-->
                                            <div class="card-header" id="post-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#post" aria-expanded="true"
                                                            aria-controls="post">
                                                        Post File
                                                    </button>
                                                </h2>
                                            </div>

{{--                                            <div id="post" class="collapse" aria-labelledby="post-file"--}}
{{--                                                 data-parent="#accordionExample">--}}
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-sm-7">
                                                            @if($action == 'edit')
                                                                @foreach($files as $image)
                                                                    <div id="inputFormRow">
                                                                        <input type="hidden" value="{{ $image->id }}" name="old_files[]">
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-append" style="margin-bottom: 2%">
                                                                                <button id="removeRow" type="button" style="height:40px" class="btn btn-danger">Remove</button>
                                                                                <div style="margin-left:50px">
                                                                                    <div>
                                                                                        <img class="imageThumb" src="{{ getS3File($image->file) }}"/>
                                                                                        <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 400 x 400 </span> pixels</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div id="newRow" class="input_image_scroller"></div>
                                                            <button id="addRow" type="button" class="btn btn-primary add_post_image_button" {{ $row->job_type == 1 || $row->job_type ==2 ? 'disabled' : '' }}>Add Post Image</button>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                            </div>--}}

                                            <!-- for Gernal -->



                                            <div class="card-header" id="short-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#general" aria-expanded="true"
                                                            aria-controls="general">
                                                        General
                                                    </button>
                                                </h2>
                                            </div>
{{--                                            <div id="general" class="collapse" aria-labelledby="short-heading"--}}
{{--                                                 data-parent="#accordionExample">--}}
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Status</label>
                                                        <div class="col-sm-6">
                                                            <div class="icheck-primary d-inline">
                                                                Active
                                                                <input type="radio" name="status"
                                                                       id="active-radio-status"
                                                                       value="1" {{ ($row->status==1) ? 'checked' : '' }}>
                                                                <label for="active-radio-status">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                In-Active
                                                                <input type="radio" name="status"
                                                                       id="in-active-radio-status"
                                                                       value="0" {{ ($row->status==0) ? 'checked' : '' }}>
                                                                <label for="in-active-radio-status">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                            </div>--}}


                                        </div>
                                        <!-- end accordian -->
                                        <div class="card-body">
                                            <div class="form-group text-right">
                                                <div class="col-sm-12">
                                                    <a href="{{ URL('admin/posts') }}" class="btn btn-info"
                                                       style="margin-right:05px;"> Cancel </a>
                                                    <button type="submit"
                                                            class="btn btn-primary float-right"> {{ ($action == 'add') ? 'Save' : 'Update' }} </button>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- Main content -->
    </div>
@endsection

@push('footer-scripts')
    <!-- jQuery -->
    <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- jquery-validation -->
    <script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('assets/admin/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/admin/inputmask/jquery.inputmask.min.js')}}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
    <!-- Page specific script -->
    <!-- SummerNote -->
    <script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
    <!-- tags input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>

        let tagInputEle = $('#tags-input');
        tagInputEle.tagsinput();

        /*$(function () {
            $('[data-mask]').inputmask();
            bsCustomFileInput.init();
            $('#post-form').validate({
                ignore: false,
                rules:
                    {
                        title_english: {
                            required: true,
                        },
                        title_urdu: {
                            required: true,
                        },
                        title_arabic: {
                            required: true,
                        },
                        post_type: {
                            required: true,
                        },
                        status: {
                            required: true,
                        }
                    },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

        });*/

        $("#addRow").click(function () {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="input-group" style="margin-bottom: 2%">';
            html += '<input id="files" type="file" name="files[]" class="form-control m-input">';
            html += '<div class="input-group-append">';
            html += '<button id="removeRow" type="button" style="height:40px" class="btn btn-danger dynamic_files">Remove</button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });


            $("body").on("change",'input:file', function (e) {
                var files = e.target.files,
                    filesLength = files.length;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileReader = new FileReader();
                    fileReader.onload = (function (e) {
                        var file = e.target;
                        $(`<div class="bar" style="margin-left:50px">
                        <div><img class="imageThumb" src="${e.target.result}" title="${f.name}"/></div>
                        <br/>
                        `).insertAfter($('.dynamic_files:last'));

                    });
                    fileReader.readAsDataURL(f);
                }
            });

            $('#post_type').on('change', function () {
                let jobType = $(this).find(":selected").val();
                if(jobType == 2) { // 2= job post
                    $('.job_div').css('display', 'block')
                    $('.add_post_image_button').prop('disabled', true)
                    // $('#newRow').remove()
                } else {
                    $('.job_div').css('display', 'none')
                    $('.add_post_image_button').prop('disabled', false)
                }
                if(jobType == 5) { // 5 = blood post
                    $('.blood_div').css('display', 'block')
                } else {
                    $('.blood_div').css('display', 'none')
                }
            });

        $('.job_type').on('change', function() {
            let jobType = $(this).val()
            if (jobType == 1) $('.resume_div').css('display', 'none');
            if (jobType == 2) $('.resume_div').css('display', 'block');
        });

    </script>
@endpush
