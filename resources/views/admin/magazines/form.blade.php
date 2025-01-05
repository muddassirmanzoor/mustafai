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
                    <h1 class="m-0">Create Magazine </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL('admin/magazines') }}"> Magazine</a></li>
                        <li class="breadcrumb-item active">Create Magazine</li>
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
                            <h3 class="card-title">Magazine Form</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/magazines') }}" enctype="multipart/form-data" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <!-- For message title general  -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#title" aria-expanded="true" aria-controls="title">
                                                    Title
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="title" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Title ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control" placeholder="Enter Title {{ $lang->name_english }}" name="title_{{ $lang->key }}" value="{{ $row->{'title_'.$lang->key} }}" required="">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For Magazine Category -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#donation_category" aria-expanded="true" aria-controls="Price">
                                                    Magazine Category
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="donation_category" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group ">
                                                            <label class="form-label">Magazine Category <span class="text-red">*</span></label>
                                                            <select class="form-control" name="magazine_category_id" required>
                                                                <option value="">--select magazine category---
                                                                </option>
                                                                @forelse($magazine_categories as $category)
                                                                <option value="{{ $category->id }}" @if ($category->id == $row->magazine_category_id) selected @endif>
                                                                    {{ $category->name_english }}</option>
                                                                @empty
                                                                <option value="">no type found!</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For Image -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
                                                    Magazine File
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">File <span class="text-red">*</span></label>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control" placeholder="Select Image" id="file" name="file" accept="application/pdf" value="" @if (!$row->file) required @endif>
                                                        <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 214 x 214 </span> pixels</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For thumbnail Image -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#thumbnail" aria-expanded="true" aria-controls="image">
                                                    Thumbnail Image
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="thumbnail" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Image <span class="text-red">*</span></label>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control imageinpt dynamic_class_1" data-file-num="1"  data-preview-id="sample_thumbnail" placeholder="Select Image" id="imageinpt" name="thumbnail_image" value="" @if(!$row->thumbnail_image) required @endif accept="image/*">
														<small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 214 x 214 </span> pixels</small>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        @if($row->thumbnail_image)
                                                        <a href="{{ getS3File($row->thumbnail) }}" target="_blank">
                                                            <img src="{{ getS3File($row->thumbnail_image) }}" alt="" id="sample_thumbnail" width="100" height="100">
                                                        </a>
                                                        <button class="btn btn-sm btn-danger d-block mt-2" id="clear_image"> clear Image</button>
                                                        @else
                                                        <a href="javascrit:void(0)" target="_blank">
                                                            <img id="sample_thumbnail" src="{{ getS3File('images/dummy-images/dummy.PNG') }}" alt="your image" width="60" height="60" />
                                                        </a>
                                                        <button class="btn btn-sm btn-danger d-block mt-2" id="clear_image"> clear Image</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For Content -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#message" aria-expanded="true" aria-controls="message">
                                                    Content
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="message" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Content ({{ $lang->name_english }}) <span class="text-red">*</span></label><br>
                                                                <textarea id="description_english" class="summernote" name="description_{{ $lang->key }}" rows="5" cols="30" required>{{ $row->{'description_'.$lang->key} }}</textarea>
                                                            </div>
                                                    </div>
                                                    @endforeach

                                                </div>

                                            </div>
                                        </div>
                                        <!-- for Gernal -->
                                        <div class="card-header" id="short-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#general" aria-expanded="true" aria-controls="general">
                                                    General
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="general" class="collapse" aria-labelledby="short-heading" data-parent="#accordionExample">
                                            <div class="card-body">
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
                                        <!-- end gernal -->
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group text-right">
                                        <div class="col-sm-12">
                                            <a href="{{ URL('admin/magazines') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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

        // $(document).ready(function() {
        //             $(document).on('change', '.imageinpt', function() {
        //             // $('#imageinpt').on('change', function() {
        //                 var width=$(this).attr('data-wdith');
        //                 var height=$(this).attr('data-height');

        //                 var file = this.files[0];
        //                 var img = new Image();
        //                 var reader = new FileReader();

        //                 reader.onload = function(e) {
        //                 img.src = reader.result;

        //                     $(img).on('load', function() {
        //                         if (img.width >= width || img.height >= height) {
        //                         // alert("Image dimension should be 214 x 214");
        //                         swal("Oops!", `Max Image dimension should be ${width} x ${height}.`, "error")

        //                         var drEvent = $('.dropify').dropify();
        //                         drEvent = drEvent.data('dropify');
        //                         drEvent.resetPreview();
        //                         $("#imageinpt").val('');

        //                         }
        //                     });
        //                 };

        //                 reader.readAsDataURL(file);
        //             });
        // });

        $('#page-form').validate({
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

</script>
<script>
    imageinpt.onchange = evt => {
        const [file] = imageinpt.files
        if (file) {
            sample_thumbnail.src = URL.createObjectURL(file)
            $('#sample_thumbnail').parent().attr('href', URL.createObjectURL(file));
        }
    }
    $('#clear_image').click(function() {
        event.preventDefault();
        $('#imageinpt').val('');
        $('#sample_thumbnail').parent().attr('href', 'https://www.freeiconspng.com/uploads/no-image-icon-6.png');
        $('#sample_thumbnail').attr('src', 'https://www.freeiconspng.com/uploads/no-image-icon-6.png');
        $('#imageinpt').attr('required', true);
    });

</script>
@endpush
