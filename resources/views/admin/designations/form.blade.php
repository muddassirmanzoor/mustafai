@extends('admin.layout.app')

@push('header-scripts')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if($type==1)
                        <h1 class="breadcrumb-item active">Create Admin Designation</h1>
                    @else
                        <h1 class="breadcrumb-item active">Create User Designation</h1>
                    @endif
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{URL('admin/designations')}}">Designations</a></li>
                        @if($type==1)
                            <li class="breadcrumb-item active">Create Admin Designation</li>
                        @else
                            <li class="breadcrumb-item active">Create User Designation</li>
                        @endif
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- General form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Designation Form</h3>
                        </div><!-- /.card-header -->
                        
                        <div class="card-body">
                            <form id="designation-form" class="form-horizontal label-left" action="{{URL('admin/designations')}}" enctype="multipart/form-data" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                <div class="card-body">
                                    @if($type == 1)
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Designation Name <span class="text-red">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="Enter Designation Name" name="name_english" value="{{ $row->name_english }}">
                                        </div>
                                    </div>
                                    @elseif($type == 2)
                                    @foreach(activeLangs() as $lang)
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Designation Name ({{$lang->name_english}}) <span class="text-red">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="Enter Designation Name" name="name_{{$lang->key}}" value="{{ $row->{'name_'.$lang->key} }}" required>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- Status -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-6">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" name="status" id="active-radio" value="1" {{ $row->status == 1 ? 'checked' : '' }}>
                                                <label for="active-radio">Active</label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" name="status" id="in-active-radio" value="0" {{ $row->status == 0 ? 'checked' : '' }}>
                                                <label for="in-active-radio">In-Active</label>
                                            </div>
                                        </div>
                                    </div>

                                    @if($row->id != 1)
                                    <hr>
                                    <h4 class="heading">To give permissions on specific module.</h4><br>
                                    <div class="d-flex justify-content-end">
                                        <label class="fancy-checkbox custom-bgcolor-darkblue">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="check_all" onClick="check_all_boxes($(this),'all','all')">
                                                    <label class="custom-control-label" for="check_all">Check/Uncheck All</label>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    @foreach(rights($type) as $module => $rights)
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ $module }}</h3>
                                            <span class="d-flex justify-content-end">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input check_all_modules" id="check_all_{{ str_replace(' ', '_', $module) }}" onClick="check_all_boxes($(this),'{{ str_replace(' ', '_', $module) }}','module_wise')">
                                                    <label class="custom-control-label" for="check_all_{{ str_replace(' ', '_', $module) }}"></label>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="card-body row">
                                            @foreach($rights as $key => $right)
                                            <?php $checked = ''; if(!empty($row->right_ids) && in_array($right->right_name, explode(',', $row->right_ids))) { $checked = 'checked'; } ?>
                                            <div class="col-sm-3">
                                                <label class="fancy-checkbox custom-bgcolor-darkblue">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" name="right_ids[]" class="custom-control-input all-check-permissions all-check-permissions_{{ str_replace(' ', '_', $module) }}" id="{{ str_replace(' ', '_', $module) }}-right-id-{{ $key }}" {{ $checked }} value="{{ $right->right_name }}" data-attr="{{ str_replace(' ', '_', $module) }}">
                                                            <label class="custom-control-label" for="{{ str_replace(' ', '_', $module) }}-right-id-{{ $key }}">{{ ucwords(str_replace('-', ' ', $right->right_name)) }}</label>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- Submit/Cancel Buttons -->
                                    <div class="form-group text-right">
                                        <div class="col-sm-12">
                                            <a href="{{URL('admin/designations')}}" class="btn btn-info" style="margin-right: 5px;">Cancel</a>
                                            <button type="submit" class="btn btn-primary float-right">{{ $action == 'add' ? 'Save' : 'Update' }}</button>
                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </form>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
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
<script>
    function check_all_boxes(_this='', data='', module_wise='') {
        if (data == 'all') {
            if (!_this.is(':checked')) {
                $('.all-check-permissions').prop('checked', false)
                $(".check_all_modules").prop('checked', false)
            } else {
                $('.all-check-permissions').prop('checked', true)
                $(".check_all_modules").prop('checked', true)
            }
        } else if (module_wise == 'module_wise') {
            if (!_this.is(':checked')) {
                $('.all-check-permissions_' + data + '').prop('checked', false)
            } else {
                $('.all-check-permissions_' + data + '').prop('checked', true)
            }
        } else if (module_wise == "onload") {
            var counter = false;
            var someNumbers = [];
            $(".all-check-permissions").each(function() {
                if (!$(this).is(':checked')) {
                    var modules = $(this).attr('data-attr');
                    if (!$.inArray("check_all_" + modules, someNumbers) >= 0) {
                        someNumbers.push("check_all_" + modules);
                    }
                    counter = true;
                }
            });
            if (counter) {
                $('.check_all_modules').prop('checked', true);
                $.each(someNumbers, function(key, value) {
                    $("#" + value).prop('checked', false);
                });
                $("#check_all").prop('checked', false);
            } else {
                $("#check_all").prop('checked', true)
                $(".check_all_modules").prop('checked', true)
            }
        } else {}
    }

    check_all_boxes('_this', 'data', module_wise = 'onload');

    $(function() {
        $('[data-mask]').inputmask();
        bsCustomFileInput.init();

        $('#designation-form').validate({
            rules: {
                name_english: {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
