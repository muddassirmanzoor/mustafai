@extends('admin.layout.app')

@push('header-scripts')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/admin/fontawesome-free/css/all.min.css') }}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
<!-- SummerNote -->
<link rel="stylesheet" href="{{ asset('assets/admin/summernote/summernote-bs4.min.css') }}">
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Professions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL('admin/occupations') }}">Professions</a></li>
                        <li class="breadcrumb-item active">Create Professions</li>
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
                            <h3 class="card-title">Profession Form</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="announcement-form" class="form-horizontal label-left" action="{{ URL('admin/occupations') }}" enctype="multipart/form-data" method="POST">
                                {!! csrf_field() !!}

                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <!-- For Title -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colTitle" aria-expanded="true" aria-controls="colTitle">
                                                    Title
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colTitle" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Title ({{ $lang->name_english }}) <span class="text-red">*</span></label>

                                                                <input type="text" class="form-control" placeholder="Enter Title In {{ $lang->name_english }}" name="title_{{ $lang->key }}" value="{{ $row->{'title_'.$lang->key} }}" required="">
                                                                <small>
        															<span class="d-block text-danger  mt-1">Please avoid to enter special character <br>
        														</small>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>

                                        <!-- For Parent -->
                                        @if (!$occupations->isEmpty())
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#parent" aria-expanded="true" aria-controls="parent">
                                                    Parent Profession
                                                </button>
                                            </h2>
                                        </div>
                                        @endif
                                        <div id="parent" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group ">
                                                            <ul id="occupations-tree">
                                                                @foreach ($occupations as $occupation)
                                                                <li>
                                                                    {{ $occupation->title_english }}
                                                                    <input type="radio" name="parent_id" {{ ($occupation->id==$row->parent_id)? "checked" : "" }} value="{{ $occupation->id }}">
                                                                    @if ($occupation->subProfession()->count())
                                                                    @foreach ( $occupation->subProfession as $child )
                                                                    <ul>
                                                                        <li>
                                                                            {{ $child->title_english }}
                                                                        </li>
                                                                    </ul>
                                                                    @endforeach
                                                                    @endif
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- For Content -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colContent" aria-expanded="true" aria-controls="colContent">
                                                    Content
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colContent" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Content ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                <textarea class="summernote" name="content_{{ $lang->key }}">{{$row->{'content_'.$lang->key} }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>

                                        <!-- General -->
                                        <div class="card-header" id="general-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colGeneral" aria-expanded="true" aria-controls="colGeneral">
                                                    General
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colGeneral" class="collapse" aria-labelledby="general-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                {{-- <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Show In Header</label>
                                                    <div class="col-sm-6">
                                                        <div class="icheck-primary d-inline">
                                                            Yes
                                                            <input type="radio" name="in_header" id="show-header-yes" value="1" {{ $row->in_header == 1 ? 'checked' : '' }}>
                                                            <label for="show-header-yes">
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            No
                                                            <input type="radio" name="in_header" id="show-header-no" value="0" {{ $row->in_header == 0 ? 'checked' : '' }}>
                                                            <label for="show-header-no">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-6">
                                                        <div class="icheck-primary d-inline">
                                                            Active
                                                            <input type="radio" name="status" id="active-radio-status" value="1" {{ $row->status == 1 ? 'checked' : '' }}>
                                                            <label for="active-radio-status">
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            In-Active
                                                            <input type="radio" name="status" id="in-active-radio-status" value="0" {{ $row->status == 0 ? 'checked' : '' }}>
                                                            <label for="in-active-radio-status">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group text-right">
                                        <div class="col-sm-12">
                                            <a href="{{ URL('admin/occupations') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
                                            <button type="submit" class="btn btn-primary float-right">
                                                {{ $action == 'add' ? 'Save' : 'Update' }} </button>
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
<script src="{{ asset('assets/admin/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('assets/admin/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/admin/jquery-validation/additional-methods.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('assets/admin/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>
<!-- SummerNote -->
<script src="{{ asset('assets/admin/summernote/summernote-bs4.min.js') }}"></script>
<!-- Page specific script -->
<script>
    $(function() {

        $('.summernote').summernote({
            height: ($(window).height() - 300),
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0],$(this));
                },
                onMediaDelete : function(target) {
                    removeImage(target[0].src)
                }
            }
        })

        $('#announcement-form').validate({
            ignore: false
            , rules: {}
            , errorElement: 'span'
            , errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            }
            , highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            }
            , unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
            , invalidHandler: function(e, validator) {
                // loop through the errors:
                for (var i = 0; i < validator.errorList.length; i++) {
                    // "uncollapse" section containing invalid input/s:
                    $(validator.errorList[i].element).closest('.collapse').collapse('show');
                }
            }
        });
    });

    function uploadImage(image,_this)
		{
			var data = new FormData();
		    data.append("image", image);
		    data.append("path", 'occupation-images');
		    $.ajax({
		        url: "{{ route('admin.uploadeditoimage') }}",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: data,
		        type: "post",
		        success: function(url) {

		            var image = $('<img>').attr('src', url);
		            _this.summernote("insertNode", image[0]);
		        },
		        error: function(data) {
		        }
		    });
		}

		function removeImage(image)
		{
			var data = new FormData();
		    data.append("image", image);
			data.append("path", 'occupation-images');
		    $.ajax({
		        url: "{{ route('admin.deleteeditorimage') }}",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: data,
		        type: "post",
		        success: function() {
		        },
		        error: function(data) {
		        }
		    });
		 }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

</script>
@endpush
