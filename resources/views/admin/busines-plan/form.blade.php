@extends('admin.layout.app')

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
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.css')}}">
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Business Plan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/busines_plans') }}">Business Plans</a>
                            </li>
                            <li class="breadcrumb-item active">Create Business Plan</li>
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
                                <h3 class="card-title">Business Plan Form</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="categories-form" class="form-horizontal label-left"
                                      action="{{ URL('admin/busines_plans') }}" enctype="multipart/form-data"
                                      method="POST">
                                    {!! csrf_field() !!}

                                    <input type="hidden" name="action" value="{{$action}}">
                                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <!-- For Name -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#name" aria-expanded="true"
                                                            aria-controls="name">
                                                        Name
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="name" class="collapse" aria-labelledby="message-title-heading"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group ">
                                                                    <label class="form-label">Name ({{ $lang->name_english }})<span
                                                                            class="text-red">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter Name {{ $lang->name_english }}"
                                                                        name="name_{{ $lang->key }}"
                                                                        value="{{$row->{'name_'.$lang->key} }}" required="">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="type" id="daily-radio-btn" value="3" required>

                                            {{-- <!-- Type -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#type" aria-expanded="true" aria-controls="type">
                                                    Type
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="type" class="collapse" aria-labelledby="general-heading" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Type</label>
                                                        <div class="col-sm-6">
                                                            <!-- <div class="icheck-primary d-inline">
                                                                Monthly
                                                                <input type="radio" name="type" id="monthly-radio-btn" value="1" {{ ($row->type==1) ? 'checked' : '' }} required>
                                                                <label for="monthly-radio-btn">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                Weekly
                                                                <input type="radio" name="type" id="Weekly-radio-btn" value="2" {{ ($row->type==2) ? 'checked' : '' }} required>
                                                                <label for="Weekly-radio-btn">
                                                                </label>
                                                            </div> -->
                                                            <div class="icheck-primary d-inline">
                                                                Daily
                                                                <input type="radio" name="type" id="daily-radio-btn" value="3" {{ ($row->type==3) ? 'checked' : '' }} required>
                                                                <label for="daily-radio-btn">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <!-- Invoices -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#invoice-users" aria-expanded="true"
                                                            aria-controls="invoice-users">
                                                        Daily Amount
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="invoice-users" class="collapse" aria-labelledby="general-heading"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Total Invoices <span
                                                                        class="text-red">*</span></label>
                                                                <input type="number" class="form-control"
                                                                       placeholder="Enter Total Invoices"
                                                                       name="total_invoices"
                                                                       value="{{$row->total_invoices}}" required="">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Amount/day <span
                                                                        class="text-red">*</span></label>
                                                                <input type="number" class="form-control"
                                                                       placeholder="Enter Daily Amount"
                                                                       name="invoice_amount"
                                                                       value="{{$row->invoice_amount}}" required="">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class=" form-label">Total Users <span
                                                                        class="text-red">*</span></label>
                                                                <input type="number" class="form-control"
                                                                       placeholder="Enter Total Users"
                                                                       name="total_users" value="{{$row->total_users}}"
                                                                       required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#idescription" aria-expanded="true"
                                                            aria-controls="idescription">
                                                        Description
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="idescription" class="collapse" aria-labelledby="general-heading"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group ">
                                                                    <label class="form-label">Description ({{ $lang->name_english }})<span
                                                                            class="text-red">*</span></label><br>
                                                                    <textarea name="description_{{ $lang->key }}" id="" cols="35"
                                                                            rows="5" required>{{$row->{'description_'.$lang->key} }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Terms And Conditions -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#term_condition" aria-expanded="true"
                                                            aria-controls="term_condition">
                                                        Terms and Conditions 
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="term_condition" class="collapse" aria-labelledby="general-heading"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group ">
                                                                    <label class="form-label">Terms and Conditions ({{ $lang->name_english }})  <span class="text-danger">*</span> </label><br>
                                                                    <textarea name="term_conditions_{{ $lang->key }}" id="" class="terms_conditions" required>{{$row->{'term_conditions_'.$lang->key} }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Start And End Date -->
                                            <div class="card-header" id="general-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#start-end-date" aria-expanded="true"
                                                            aria-controls="start-end-date">
                                                        Start and End Date
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="start-end-date" class="collapse" aria-labelledby="general-heading"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group ">
                                                                <label class="form-label">Start Date <span
                                                                        class="text-red">*</span></label>
                                                                <input type="date" class="form-control"
                                                                       placeholder="Start date" name="start_date"
                                                                       min="{{ $action == 'add' ? date("Y-m-d") : date('Y-m-d',$row->start_date)}}"
                                                                       value="{{($action =='edit') ? date('Y-m-d',$row->start_date) : ''}}"
                                                                       required="">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="form-group ">
                                                                <label class="form-label">End Date <span
                                                                        class="text-red">*</span></label>
                                                                <input type="date" class="form-control"
                                                                       placeholder="End date" name="end_date"
                                                                       min="{{ $action == 'add' ? date("Y-m-d") : date('Y-m-d',$row->start_date)}}"
                                                                       value="{{($action == 'edit') ? date('Y-m-d',$row->end_date) : ''}}"
                                                                       required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($action == 'add')
                                                <!-- For Payment Detail -->
                                                <div class="card-header" id="title-heading">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse" data-target="#payment_method"
                                                                aria-expanded="true" aria-controls="name">
                                                            Payment Details
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="payment_method" class="collapse"
                                                     aria-labelledby="message-title-heading"
                                                     data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        @foreach($payment_methods as $key => $pmethod)
                                                            <div class="form-group">
                                                                <input type="checkbox"
                                                                       class="pmethods-input-sending bp-input do-not-ignore"
                                                                       name="sending_payment_method_ids[]"
                                                                       value="{{$pmethod->id}}"
                                                                       onchange="paymentMethods('pmethods-input-sending')">
                                                                {{$pmethod->method_name_english}}
                                                            </div>
                                                            @foreach($pmethod->paymentDetails as $detail)
                                                                <div
                                                                    class="form-group pmethods-input-sending-{{$pmethod->id}}"
                                                                    style="display: none;">
                                                                    <label
                                                                        for="">{{$detail->method_fields_english}}</label>
                                                                    <input type="text" class="form-control"
                                                                           placeholder="Enter {{$detail->method_fields_english}}"
                                                                           name="sending_details[{{$pmethod->id}}][{{$detail->id}}]">
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>

                                            @endif

                                            @if ($action == 'edit')
                                                <!-- For Payment Detail -->
                                                <div class="card-header" id="title-heading">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse" data-target="#payment_method"
                                                                aria-expanded="true" aria-controls="name">
                                                            Payment Details
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="payment_method" class="collapse"
                                                     aria-labelledby="message-title-heading"
                                                     data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        @foreach($payment_methods as $key => $pmethod)
                                                            @php
                                                                $checked = '';
                                                                if(isset($accounts['sending']) && in_array($pmethod->id,$accounts['sending']['payment_method_id']))
                                                                {
                                                                    $checked = 'checked';
                                                                }
                                                            @endphp
                                                            <div class="form-group">
                                                                <input type="checkbox"
                                                                       class="pmethods-input-sending bp-input do-not-ignore"
                                                                       name="sending_payment_method_ids[]"
                                                                       value="{{$pmethod->id}}"
                                                                       onchange="paymentMethods('pmethods-input-sending')"
                                                                       {{ $checked }} required>
                                                                {{$pmethod->method_name_english}}
                                                            </div>
                                                            @foreach($pmethod->paymentDetails as $detail)
                                                                <div
                                                                    class="form-group pmethods-input-sending-{{$pmethod->id}}"
                                                                    style="display: {{(empty($checked)) ? 'none;':'block;'}}">
                                                                    <label
                                                                        for="">{{$detail->method_fields_english}}</label>
                                                                    <input type="text" class="form-control"
                                                                           placeholder="Enter {{$detail->method_fields_english}}"
                                                                           name="sending_details[{{$pmethod->id}}][{{$detail->id}}]"
                                                                           value="{{ (isset($accounts['sending']['payment_method_field_'.$detail->id])) ? $accounts['sending']['payment_method_field_'.$detail->id]:'' }}">
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>

                                            @endif

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
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group text-right">
                                            <div class="col-sm-12">
                                                <a href="{{ URL('admin/busines_plans') }}" class="btn btn-info"
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
    <!-- SummerNote -->
    <script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('assets/admin/select2/js/select2.full.js')}}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {

            $('.terms_conditions').summernote({
	    		height: ($(window).height() - 300)
	    	})

            $('.select2').select2()

            $('#categories-form').validate({
                ignore: false,
                rules:
                    {},
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
                },
                invalidHandler: function (e, validator) {
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

        function paymentMethods(_class) {
            $("." + _class).each(function (index) {
                if ($(this).is(':checked')) {
                    $('.' + _class + '-' + $(this).val()).css('display', 'block');
                } else {
                    $('.' + _class + '-' + $(this).val()).css('display', 'none');
                }
            });
        }
    </script>
@endpush
