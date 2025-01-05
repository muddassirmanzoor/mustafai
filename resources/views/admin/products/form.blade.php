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

</style>

@push('header-scripts')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
<!-- SummerNote -->
<link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/dropify/dist/css/dropify.min.css')}}">
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL('admin/products') }}">Products</a></li>
                        <li class="breadcrumb-item active">Create Product</li>
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
                            <h3 class="card-title">Product Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="announcement-form" class="form-horizontal label-left" action="{{ URL('admin/products') }}" enctype="multipart/form-data" method="POST">
                                {!! csrf_field() !!}

                                <input type="hidden" name="action" value="{{$action}}">
                                <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                <div class="accordion" id="accordionExample">
                                    <div class="card">

                                        <!-- For Title -->
                                        <div class="card-header" id="category-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colcategory" aria-expanded="true" aria-controls="colcategory">
                                                    Category
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colcategory" class="collapse" aria-labelledby="category-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Category <span class="text-red">*</span></label>
                                                    <div class="col-sm-6">
                                                        <select name="category_id" class="custom-select rounded-0" required="">
                                                            <option value="">--Select Category--</option>
                                                            @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{($row->category_id==$category->id)?'selected':''}}>{{ $category->name_english }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- For Name -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#name" aria-expanded="true" aria-controls="name">
                                                    Name
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="name" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Name ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control" placeholder="Enter Name {{ $lang->name_english }}" name="name_{{ $lang->key }}" value="{{$row->{'name_'.$lang->key} }}" required="">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For Vendor Name -->
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colvendor" aria-expanded="true" aria-controls="colvendor">
                                                    Select Product Vendor
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colvendor" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group ">
                                                            <label class="form-label">Vendor name <span class="text-red">*</span></label>
                                                            <select class="form-control" name="vendor_id" required>
                                                                <option value="">--select vendor---</option>
                                                                @forelse($vendors as $value)
                                                                <option value="{{ $value->id }}" @if($value->id == $row->vendor_id) selected @endif>{{ $value->name_english }}</option>
                                                                @empty
                                                                <option value="">no type found!</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- For  Description -->
                                        <div class="card-header" id="description-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colDescription" aria-expanded="true" aria-controls="colDescription">
                                                    Description
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="colDescription" class="collapse" aria-labelledby="description-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class=" form-label">Description ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                <textarea class="form-control" name="description_{{ $lang->key }}" rows="5" cols="70" required>{{$row->{'description_'.$lang->key} }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- For Image -->
                                        <div class="card-header" id="image-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
                                                    Product Image
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="image" class="collapse" aria-labelledby="image-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="col-sm-7">
                                                    @if(!empty($product_images))
                                                        @foreach($product_images as $key=>$val)
                                                            <div class="mt-2 mb-2 img_div" id="img_div_{{$loop->iteration}}">
                                                                <img class="imageThumb" src="{{getS3File($val->file_name)}}" title="${f.name}" />
                                                                <input type="hidden" name="old_image_id[]" value="{{$val->id}}"  />
                                                                <button id="" type="button" onclick="delete_imag('img_div_{{$loop->iteration}}')" style="height:40px" class="btn btn-danger ml-2" style="width: 2%">Remove</button>
                                                                <i class="fas fa-arrows-h"></i>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <div id="newRow" class="input_image_scroller"></div>
                                                    <button id="addRow" type="button" class="btn btn-primary">Add Product Image</button>
                                                    <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 214 x 214 </span> pixels</small>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- For Files of product -->
                                        <div class="card-header" id="image-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colsFile" aria-expanded="true" aria-controls="colsFile">
                                                    Product Pricing
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colsFile" class="collapse" aria-labelledby="file-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Select File Type <span class="text-red">*</span> </label>
                                                        <select name="file_type" id="file_type" class="custom-select rounded-0" required="">
                                                            <option value="">--Select File Type--</option>
                                                            <option value="1" @if($row->file_type == 1) selected @endif>Free Of Cost</option>
                                                            <option value="2" @if($row->file_type == 2) selected @endif>Paid</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 product_price" id="product_price">
                                                        <label class="form-label">Price <span class="text-red">*</span></label>
                                                        <input type="number" class="form-control" placeholder="Enter Price" name="price" id="price" value="{{$row->file_type == 2 ? $row->price : 0 }}" {{ $row->file_type == 2 ? 'required' : ''}}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For details of shipment -->

                                        <div class="card-header" id="image-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colsshipment" aria-expanded="true" aria-controls="colsFile">
                                                    Shipment Details
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colsshipment" class="collapse" aria-labelledby="file-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Select shipment Type <span class="text-red">*</span></label>
                                                        <select name="is_shipment_charges_apply" class="custom-select rounded-0" required="">
                                                            <option value="">--Select shipment Type--</option>
                                                            <option value="0" @if($row->is_shipment_charges_apply == 0) selected @endif>Free of Cost</option>
                                                            <option value="1" @if($row->is_shipment_charges_apply == 1) selected @endif>Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For details of book type -->

                                        <div class="card-header" id="image-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colsproduct_type" aria-expanded="true" aria-controls="colsFile">
                                                    Product Type
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colsproduct_type" class="collapse" aria-labelledby="file-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div id="file-div" class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-sm-10">
                                                                <label class="form-label">Upload File </label>
                                                                <input type="file" id="file_input" class="form-control" placeholder="--Select File--" name="file_name" id="file_name" value="" accept="application/doc,application/docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                                                            </div>
                                                            <div class="col-sm-1 mt-5" id="download_div" @if(empty($row->file_name)) style="display: none" @endif>
                                                                <a @if(!empty($row->file_name)) href="{{asset($row->file_name)}}" @endif download>
                                                                    <img alt="" id="sample_file" @if(!empty($row->file_name)) src="{{asset($row->file_name)}}" @endif>
                                                                    <i class="fa fa-download" style="font-size: 30px;"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Select Product Type <span class="text-red">*</span></label>
                                                        <select name="product_type" id="product-type" class="custom-select rounded-0" required="">
                                                            <option value="">--Select Product Type--</option>
                                                            <option value="0" @if($row->product_type == 0) selected @endif>Physical</option>
                                                            <option value="1" @if($row->product_type == 1) selected @endif>Virtual</option>
                                                        </select>
                                                    </div>
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
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">New Arrival</label>
                                                    <div class="col-sm-6">
                                                        <div class="icheck-primary d-inline">
                                                            Yes
                                                            <input type="radio" name="new" id="yes-radio-new" value="1" {{ ($row->new==1) ? 'checked' : '' }}>
                                                            <label for="yes-radio-new">
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            No
                                                            <input type="radio" name="new" id="no-radio-new" value="0" {{ ($row->new==0) ? 'checked' : '' }}>
                                                            <label for="no-radio-new">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-6">
                                                        <div class="icheck-primary d-inline">
                                                            Active
                                                            <input type="radio" name="status" id="active-radio-status" value="1" {{ ($row->status==1) ? 'checked' : '' }}>
                                                            <label for="active-radio-status">
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            In-Active
                                                            <input type="radio" name="status" id="in-active-radio-status" value="0" {{ ($row->status==0) ? 'checked' : '' }}>
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
                                            <a href="{{ URL('admin/products') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
                                            <button type="submit" class="btn btn-primary float-right"> {{ ($action == 'add') ? 'Save' : 'Update' }} </button>
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
<!-- SummerNote -->
<script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('assets/admin/dropify/dist/js/dropify.min.js')}}"></script>
<!-- Page specific script -->
<script>
    $(function() {
        $('.dropify').dropify();

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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    // imageinpt.onchange = evt => {
    //   const [file] = imageinpt.files
    //     console.log(file);
    //   if (file) {
    //     sample_image.src = URL.createObjectURL(file)
    //     $('#sample_image').parent().attr('href',URL.createObjectURL(file));
    //   }
    // }
    $('#clear_image').click(function() {
        event.preventDefault();
        $('#imageinpt').val('');
        $('#sample_image').parent().attr('href', 'https://www.freeiconspng.com/uploads/no-image-icon-6.png');
        $('#sample_image').attr('src', 'https://www.freeiconspng.com/uploads/no-image-icon-6.png');
    });


    var rowIncrese = 0
      $("#addRow").click(function () {
          rowIncrese+=1
          var html = '';
          html += '<div id="inputFormRow">';
          html += '<div class="input-group" style="margin-bottom: 2%">';
          html += '<input id="files" type="file" name="image[]" data-file-num="'+rowIncrese+'"  data-preview-id="sample_image_'+rowIncrese+'" class="form-control m-input imageinpt dynamic_class_'+rowIncrese+'" accept="image/*">';
          html += '<div class="input-group-append">';
          html += '<button id="removeRow" type="button" style="height:40px" class="btn btn-danger dynamic_files_'+rowIncrese+'">Remove</button>';
          html += '</div>';
          html += '</div>';

          $('#newRow').append(html);
      });

    // $("#addRow").click(function() {
    //     var html = '';
    //     html += '<div id="inputFormRow">';
    //     html += '<div class="input-group" style="margin-bottom: 2%">';
    //     html += '<input id="files" type="file" name="image[]" class="form-control m-input multiImage">';
    //     html += '<div class="input-group-append">';
    //     html += '<button id="removeRow" type="button" style="height:40px" class="btn btn-danger dynamic_files">Remove</button>';
    //     html += '</div>';
    //     html += '</div>';

    //     $('#newRow').append(html);
    // });

    $(document).on('click', '#removeRow', function() {
        $(this).closest('#inputFormRow').remove();
    });


    // $("body").on("change", '.multiImage', function(e) {

    //     var files = e.target.files
    //         , filesLength = files.length;
    //     for (var i = 0; i < filesLength; i++) {
    //         var f = files[i]
    //         var fileReader = new FileReader();
    //         fileReader.onload = (function(e) {
    //             var file = e.target;
    //             $(`<div class="bar" style="margin-left:50px">
    //                     <div><img class="imageThumb" src="${e.target.result}" title="${f.name}"/></div>
    //                     <br/>
    //                     `).insertAfter($('.dynamic_files:last'));

    //         });
    //         fileReader.readAsDataURL(f);
    //     }
    // });

    $("body").on("change",'input:file', function (e) {
         let inputFileNumber = $(this).attr('data-file-num')

          var files = e.target.files,
              filesLength = files.length;
          for (var i = 0; i < filesLength; i++) {
              var f = files[i]
              var fileReader = new FileReader();
              fileReader.onload = (function (e) {
                  var file = e.target;
                  var previewerId = `previewer_${inputFileNumber}`;
                    if ($("#" + previewerId).length) {
                    $("#" + previewerId).remove();
                    }

                  $(`<div class="bar" style="margin-left:50px" id="${previewerId}">
                        <div><img class="imageThumb" id="sample_image_${inputFileNumber}" src="${e.target.result}" title="${f.name}"/></div>
                        `).insertAfter($(`.dynamic_files_${inputFileNumber}`));

              });
              fileReader.readAsDataURL(f);
          }
      });

    function delete_imag(id) {
        $('#' + id).remove();
    }

    file_input.onchange = evt => {
        const [file] = file_input.files
        console.log(file);
        if (file) {
            sample_file.src = URL.createObjectURL(file)
            $('#sample_file').parent().attr('href', URL.createObjectURL(file));
            $('#download_div').show();
        }
    }

</script>
<script>
    $('#product-type').on('change', function() {
        if (this.value == 0) {
            $('#file-div').css('display', 'none')
        } else {
            $('#file-div').css('display', 'block')
        }
    });
    var conceptName = $('#product-type').find(":selected").val();
    if (conceptName == 0) {
        $('#file-div').css('display', 'none')
    } else {
        $('#file-div').css('display', 'block')
    }

    $('#file_type').on('change', function() {
        if (this.value == 1) {
            $('#product_price').css('display', 'none');
            $('#price').val(0);
            $('#price').attr('required', false);
        }
        else {
            $('#product_price').css('display', 'block');
            $('#price').attr('required', true);
        }
    });
    var file_type = $('#file_type').find(":selected").val();
    if (file_type == 1) {
        $('#product_price').css('display', 'none')
    } else {
        $('#product_price').css('display', 'block')
    }

</script>
@endpush
