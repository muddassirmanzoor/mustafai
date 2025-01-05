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
    <!-- select2 -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container .select2-selection--single {
            height: 40px;
        }
    </style>

@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Cabinet</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/cabinets') }}">Cabinets</a></li>
                            <li class="breadcrumb-item active">Create Cabinet</li>
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
                                <h3 class="card-title">Cabinet Form</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="cabinet-form" class="form-horizontal label-left"
                                    action="{{ URL('admin/cabinets') }}" enctype="multipart/form-data" method="POST">
                                    {!! csrf_field() !!}

                                    <input type="hidden" name="action" value="{{ $action }}">
                                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <!-- Cabinet level -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#cabinet-level" aria-expanded="true" aria-controls="name">
                                                        Cabinet Level
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="cabinet-level" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="pl-3">
                                                        <div class="form-check pr-2">
                                                            <input class="form-check-input p-2" type="radio" name="cabinet_level" id="country_level" value="country" {{ $row->cabinet_level === 'country' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Country Level
                                                            </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                            <input class="form-check-input p-2" type="radio" name="cabinet_level" id="province_level" value="province" {{ $row->cabinet_level === 'province' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Province Level
                                                            </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="division_level" value="division" {{ $row->cabinet_level === 'division' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios2">
                                                            Division Level
                                                        </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="district_level" value="district" {{ $row->cabinet_level === 'district' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios3">
                                                            District Level
                                                        </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="city_level" value="city" {{ $row->cabinet_level === 'city' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios3">
                                                            City Level
                                                        </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="tehsil_level" value="tehsil" {{ $row->cabinet_level === 'tehsil' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios3">
                                                            Tehsil Level
                                                        </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="branch_level" value="branch" {{ $row->cabinet_level === 'branch' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios3">
                                                            Branch Level
                                                        </label>
                                                        </div>
                                                        <div class="form-check pr-2">
                                                        <input class="form-check-input" type="radio" name="cabinet_level" id="union_counsil_level" value="union_counsil" {{ $row->cabinet_level === 'union_counsil' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exampleRadios3">
                                                            Union Counsil Level
                                                        </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For address -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#address" aria-expanded="true" aria-controls="name">
                                                        Address
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="address" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row country--select">
                                                        <div class="col-sm-4" id="country_div" style="display: none;">
                                                            <div class="form-group ">
                                                                <label class="addres-lable">Country</label>
                                                                <select onchange="adminUpdateProvince(this)"  name="country_id" id="country_id" class="form-control js-example-basic-single">
                                                                    <option value="">Select Country</option>
                                                                    @forelse($countries as $country)
                                                                    <option value="{{ $country->id }}"
                                                                        {{ $row->country_id == $country->id ? 'selected' : '' }}>
                                                                        {{ $country->name }}</option>

                                                                    @empty
                                                                    <option value="">No Contry exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4" id="province_div" style="display: none;">
                                                            <div class="form-group ">
                                                                <label class="addres-lable">Province</label>
                                                                <select onchange="adminUpdateDivisions(this)" name="province_id" id="province_select" class="form-control js-example-basic-single">
                                                                    <option value="">Select Province</option>
                                                                     @forelse($provinces as $province)
                                                                        <option value="{{ $province->id }}"
                                                                            {{ $row->province_id == $province->id ? 'selected' : '' }}>
                                                                            {{ $province->name }}</option>
                                                                            @empty
                                                                                <option value="">No Province exists</option>
                                                                            @endforelse

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4" id="division_div" style="display: none;">
                                                            <div class="form-group ">
                                                                <label class="addres-lable">Division</label>
                                                                <select onchange="adminUpdateDistricts(this)" name="division_id" id="division_select" class="form-control js-example-basic-single">
                                                                    <option value="">Select Division</option>
                                                                    @forelse($divisions as $division)
                                                                    <option value="{{ $division->id }}"
                                                                        {{ $row->division_id == $division->id ? 'selected' : '' }}>
                                                                        {{ $division->name }}</option>

                                                                    @empty
                                                                    <option value="">No Division exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4" id="city_div" style="display: none;">
                                                            <div class="form-group ">
                                                                <label class="addres-lable">City</label>
                                                                <select name="city_id" id="city_select" class="form-control js-example-basic-single">
                                                                    <option value="">Select City</option>
                                                                    @forelse($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        {{ $row->city_id == $city->id ? 'selected' : '' }}>
                                                                        {{ $city->name }}</option>

                                                                    @empty
                                                                    <option value="">No City exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4" id="district_div" style="display: none;">
                                                            <div class="form-group ">
                                                                <label class="addres-lable">District</label>
                                                                <select onchange="adminUpdateTehsil(this)" name="district_id" id="district_select"  class="form-control js-example-basic-single">
                                                                    <option value="">Select District</option>
                                                                    @forelse($districts as $district)
                                                                    <option value="{{ $district->id }}"
                                                                        {{ $row->district_id == $district->id ? 'selected' : '' }}>
                                                                        {{ $district->name }}</option>

                                                                    @empty
                                                                    <option value="">No District exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group " id="tehsil_div" style="display: none;">
                                                                <label class="addres-lable">Tehsil</label>
                                                                <select onchange="adminUpdateZone(this)" name="tehsil_id" id="tehsil_select" class="form-control js-example-basic-single">
                                                                    <option value="">Select Tehsil</option>
                                                                    @forelse($tehsils as $tehsil)
                                                                    <option value="{{ $tehsil->id }}"
                                                                        {{ $row->tehsil_id == $tehsil->id ? 'selected' : '' }}>
                                                                        {{ $tehsil->name }}</option>

                                                                    @empty
                                                                    <option value="">No Tehsil exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group " id="zone_div" style="display: none;">
                                                                <label class="addres-lable">Branch</label>
                                                                <select id="zone_select"  onchange="adminUpdateCouncils(this)"  name="zone_id" class="form-control js-example-basic-single">
                                                                    <option value="">Select Branch</option>
                                                                    @forelse($zones as $zone)
                                                                    <option value="{{ $zone->id }}"
                                                                        {{ $row->zone_id == $zone->id ? 'selected' : '' }}>
                                                                        {{ $zone->name }}</option>

                                                                    @empty
                                                                    <option value="">No Branch exists</option>
                                                                @endforelse
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group " id="union_council_div" style="display: none;">
                                                                <label class="addres-lable">Union Council</label>
                                                                <select  id="union_council_id" name="union_council_id" class="form-control js-example-basic-single">
                                                                    <option value="">Select Union Council</option>
                                                                    @forelse($union_councils as $union_council)
                                                                    <option value="{{ $union_council->id }}"
                                                                        {{ $row->union_council_id == $union_council->id ? 'selected' : '' }}>
                                                                        {{ $union_council->name }}</option>

                                                                    @empty
                                                                    <option value="">No Union Council exists</option>
                                                                @endforelse
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- For Name -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#name" aria-expanded="true" aria-controls="name">
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
                                                                    <label class="form-label">Name ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter Name {{ $lang->name_english }}" name="name_{{ $lang->key }}"
                                                                        value="{{ $row->{'name_'.$lang->key} }}" required="">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Parent -->
                                            {{-- @if(!$cabinets->isEmpty())
                                                <div class="card-header" id="title-heading">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#parent" aria-expanded="true"
                                                            aria-controls="parent">
                                                            Parent Cabinet
                                                        </button>
                                                    </h2>
                                                </div>
                                            @endif
                                            <div id="parent" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group ">
                                                                <ul id="cabinets-tree">
                                                                    @foreach ($cabinets as $cabinet)
                                                                        <li>
                                                                            {{ $cabinet->name_english }}
                                                                            <input type="radio" name="parent_id"
                                                                                value="{{ $cabinet->id }}">
                                                                            @if (count($cabinet->childs))
                                                                                @include('admin.cabinet.manageChild',
                                                                                    ['childs' => $cabinet->childs])
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <!-- For Users -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button id="user-btn-col" class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#users" aria-expanded="true" aria-controls="users">
                                                        Users
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="users" class="collapse" aria-labelledby="message-title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body" id="user-section">
                                                    @if(isset($cabinet_users_ids))
                                                        @foreach($cabinet_users_ids as $cuser)
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group user-sec">
                                                                        <select class="select2 user-inputs user-only form-control" name="users[]" id=""
                                                                            required
                                                                            data-dropdown-css-class="select2-purple"
                                                                            style="width: 100%;" readonly>
                                                                            {{-- <option value="">Select User</option> --}}
                                                                            @foreach ($users->where('id',$cuser['user_id']) as $user)
                                                                                @php
                                                                                    $name = $user->user_name;
                                                                                    $selected = '';
                                                                                    if (!empty($user->role)) {
                                                                                        $name .= ' (' . $user->role->name_english . ')';
                                                                                    }
                                                                                    if ($cuser['user_id'] == $user->id) {
                                                                                        $selected = 'selected';
                                                                                    }
                                                                                @endphp
                                                                                <option value="{{ $user->id }}" {{$selected}}> {{ $name }} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group user-sec">
                                                                        <select class="select2 user-inputs form-control" name="designations[]" required>
                                                                            <option value="">Select Designation</option>
                                                                                @foreach($designations as $designation)
                                                                                    @php
                                                                                        $selected = '';
                                                                                        if ($cuser['designation_id'] == $designation->id) {
                                                                                            $selected = 'selected';
                                                                                        }
                                                                                    @endphp
                                                                                    <option value="{{$designation->id}}" {{$selected}}>{{$designation->name_english}}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <button class="btn btn-danger" type="button" onclick="removeRow($(this))">Remove</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <button class="btn btn-primary pull-right ml-4" type="button" onclick="addNewUserRow()">Add User</button>
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
                                                <a href="{{ URL('admin/cabinets') }}" class="btn btn-info"
                                                    style="margin-right:05px;"> Cancel </a>
                                                <button onclick="submitForm()" type="button" class="btn btn-primary float-right">
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
    <!-- select2 -->
    {{-- <script src="{{ asset('assets/admin/select2/js/select2.full.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('admin.common-script.address-script')

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
    <!-- Page specific script -->
    <script>
        var rows = 0;
        $(function() {

            if($( "#users .user-inputs" ).length)
            {
                rows = 1;
            }

            $('#cabinet-form').validate({
                ignore: false,
                rules: {},
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

        function addNewUserRow()
        {
            var country_id=$('#country_id').val();
            var province_id=$('#province_select').val();
            var division_id=$('#division_select').val();
            var district_id=$('#district_select').val();
            var city_id=$('#city_select').val();
            var tehsil_id=$('#tehsil_select').val();
            var zone_id=$('#zone_select').val();
            var union_council_id=$('#union_council_id').val();
            // var HTML = $('#dup-rows').html();
            // $('#user-section').append( HTML );
            $.ajax({
                type: "GET",
                url: "{{route('admin.cabinet-user-select')}}",
                data: {'_token': "{{csrf_token()}}",country_id:country_id ,province_id: province_id,division_id: division_id,district_id: district_id ,city_id :city_id,tehsil_id: tehsil_id ,zone_id: zone_id,union_council_id:union_council_id},
                dataType: "json",
                success: function(result) {
                    if (result.status == 200) {
                        $('#user-section').append(result.html);
                        // Initialize Select2 on the newly added elements
                        $('.js-example-basic-single').select2();
                        rows = 1;
                    }
                    else{
                        swal("Oops!",result.message, "error");
                    }
                }
            });
            if($( ".user-inputs:visible" ).length)
            {
                rows = 1;
            }
        }
        function removeRow(_this)
        {
            $(_this).parent().parent().remove();
            if($( ".user-inputs:visible" ).length)
            {
                rows = 1;
            }
            else
            {
                rows = 0;
            }
        }
        function submitForm()
        {
            if( $('#cabinet-form').valid() )
            {
                if(rows)
                {
                    if(!$( ".user-inputs:visible" ).length)
                    {
                        $('#user-btn-col').trigger('click');
                    }

                    var users = [];
                    var isError = 0;

                    $( ".user-only:visible" ).each(function( index )
                    {
                        if(jQuery.inArray($(this).val(), users) == -1)
                        {
                            users.push($(this).val());
                        }
                        else
                        {
                            isError = 1;
                            swal("Oops!", "Duplicate user found.", "error");
                            return false;
                        }
                    });

                    $( ".user-inputs:visible" ).each(function( index )
                    {
                        if(!$(this).val())
                        {
                            isError = 1;
                            // alert('User and role should not be empty.');
                            swal("Oops!", "User and role should not be empty.", "warning");
                            return false;
                        }
                    });

                    if(!isError)
                    {
                        $('#cabinet-form').submit();
                    }
                }
                else
                {
                    // alert( 'Should select one user.' );
                    swal("Oops!", "Should select one user.", "warning");
                }
            }
        }
    </script>
@endpush
