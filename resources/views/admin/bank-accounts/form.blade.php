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
                        <h1 class="m-0">Create Bank Account</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/banks') }}">Bank Accounts</a></li>
                            <li class="breadcrumb-item active">Create Bank Account</li>
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
                                <h3 class="card-title">Bank Account Form</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="categories-form" class="form-horizontal label-left"
                                    action="{{ URL('admin/bank-accounts') }}" enctype="multipart/form-data" method="POST">
                                    {!! csrf_field() !!}

                                    <input type="hidden" name="action" value="{{ $action }}">
                                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <!-- For Banks-->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#banks" aria-expanded="true" aria-controls="Price">
                                                        Bank
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="banks" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Banks <span
                                                                        class="text-red">*</span></label>
                                                                <select class="form-control" name="bank_id"
                                                                    required>
                                                                    <option value="">--select Bank---
                                                                    </option>
                                                                    @forelse($banks as $bank)
                                                                        <option value="{{ $bank->id }}"
                                                                            @if ($bank->id == $row->bank_id) selected @endif>
                                                                            {{ $bank->name_english }}</option>
                                                                    @empty
                                                                        <option value="">no type found!</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- For Banks-->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#module" aria-expanded="true" aria-controls="Price">
                                                        Module
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="module" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Modules <span
                                                                        class="text-red">*</span></label>
                                                                <select class="form-control" name="module_id"
                                                                    required>
                                                                    <option value="">--select Module---</option>
                                                                    <option value="1" {{ $row->module_id==1 ? 'selected' : '' }}>Donation</option>
                                                                    <option value="2" {{ $row->module_id==2 ? 'selected' : '' }}>Business Plan</option>
                                                                    <option value="3" {{ $row->module_id==3 ? 'selected' : '' }}>Mustafai Store</option>
                                                                    <option value="4" {{ $row->module_id==4 ? 'selected' : '' }}>Monthly Subscription	</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Account Title -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#title" aria-expanded="true" aria-controls="name">
                                                        Account Title
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="title" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group ">
                                                                    <label class="form-label">Account Title ({{ $lang->name_english }}) <span
                                                                            class="text-red">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter Account Title {{ $lang->name_english }}" name="account_title_{{ $lang->key }}"
                                                                        value="{{ $row->{'account_title_'.$lang->key} }}" required="">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Account Number -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#number" aria-expanded="true" aria-controls="name">
                                                        Account Number
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="number" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" placeholder="Enter Account Number" id="account_number" name="account_number" value="{{ $row->account_number }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Branch Number -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#branch_number" aria-expanded="true" aria-controls="name">
                                                        Branch ID / Number / Code
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="branch_number" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Branch Number <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control" placeholder="Enter Branch Number" name="branch_number" value="{{ $row->branch_number }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For IBAN Number -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#IBAN" aria-expanded="true" aria-controls="name">
                                                        IBAN Number
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="IBAN" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">IBAN Number <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" placeholder="Enter IBAN Number" id="iban_number" name="iban_number" value="{{ $row->iban_number }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- General -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#colGeneral" aria-expanded="true"
                                                        aria-controls="colGeneral">
                                                        General
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="colGeneral" class="collapse" aria-labelledby="general-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Status</label>
                                                        <div class="col-sm-6">
                                                            <div class="icheck-primary d-inline">
                                                                Active
                                                                <input type="radio" name="status"
                                                                    id="active-radio-status" value="1"
                                                                    {{ $row->status == 1 ? 'checked' : '' }}>
                                                                <label for="active-radio-status">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                In-Active
                                                                <input type="radio" name="status"
                                                                    id="in-active-radio-status" value="0"
                                                                    {{ $row->status == 0 ? 'checked' : '' }}>
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
                                                <a href="{{ URL('admin/bank-accounts') }}" class="btn btn-info"
                                                    style="margin-right:05px;"> Cancel </a>
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
            $('#categories-form').validate({
                ignore: false,
                rules: {
                    //____________for Account Number Validation__________//
					account_number: {
						required: function (element) {
							return (($("#iban_number").val() == '') ? true : false);
						}
					},
					//_______________For Iban Validation
					iban_number: {
						required: function (element) {
							return (($("#account_number").val() == '') ? true : false);
						}
					}
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
                },
                invalidHandler: function(e, validator) {
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
    </script>
@endpush
