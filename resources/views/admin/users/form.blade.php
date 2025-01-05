@extends('admin.layout.app')

@push('header-scripts')
{{-- Tags --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .bootstrap-tagsinput .tag {
        margin-right: 14px;
        color: white;
        background: red;
        margin-bottom: 20px;
        line-height: 32px;
        padding: 3px;
    }
    .bootstrap-tagsinput {
        line-height: 34px;
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        /* height: 39px !important; */
        user-select: none;
        -webkit-user-select: none;
    }

    .country-code-wrap {
        display: flex;
    }
    .user-country--select .select2-container .select2-selection--single {
        width: 320px !important;
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
                    <h1 class="m-0">{{ ($action=='add') ? 'Create' : 'Edit' }} User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL('admin/users') }}">users</a></li>
                        <li class="breadcrumb-item active">{{ ($action=='add') ? 'Create' : 'Edit' }} User</li>
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
                            <h3 class="card-title">User Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="customer-form" action="{{ URL('admin/users') }}" enctype="multipart/form-data" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" id="counter" value="1">
                                <input type="hidden" id="educationCounter" value="1">
                                <input type="hidden" name="action" value="{{$action}}">
                                <input type="hidden" name="id" id="id" value="{{ isset($id) ? $id : '' }}">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#general_info" aria-expanded="true" aria-controls="name">
                                                    General Information
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="general_info" class="collapse test123" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Email <span class="text-red">*</span></label>
                                                            <input type="email" class="form-control" placeholder="Enter Email" name="email" id="email" value="{{ $row->email }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>User Name <span class="text-red">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Enter User Name" name="user_name" id="user_name" value="{{ $row->user_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Phone No <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                {{-- <select name="country_code_id" class="form-control country-code-select" name="" id="">
                                                                    @foreach ($country_codes as $code)
                                                                    <option value="{{ $code->id }}" {{$row->country_code_id == $code->id ? 'selected' :'' }}>{{ $code->phonecode }}</option>
                                                                @endforeach
                                                                </select> --}}
                                                                <select id="id_select2_example" class="js-example-basic-single country-code-select" name="country_code_id" style="width: 200px;">
                                                                    @foreach ($country_codes as $code)
                                                                    <option value="{{ $code->id }}" {{$row->country_code_id == $code->id ? 'selected' :'' }} data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="number" class="form-control" placeholder="Enter Phone Number" id="phone_number" name="phone_number" value="{{ $row->phone_number }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>CNIC</label>
                                                            <input type="text" class="form-control" placeholder="Enter CNIC" name="cnic" value="{{ $row->cnic }}">
                                                        </div>
                                                    </div>
                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-4">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Name In {{ $lang->name_english }} <span class="text-red">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Enter Name In {{ $lang->name_english }}" name="user_name_{{ $lang->key }}" value="{{ $row->{'user_name_'.$lang->key} }}" required>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Address {{ $lang->name_english }}</label>
                                                            <input type="text" class="form-control" placeholder="Enter Address in {{ $lang->name_english }}" name="address_{{ $lang->key }}" value="{{ $row->{'address_'.$lang->key} }}">
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Permanent Address {{ $lang->name_english }}</label>
                                                            <input type="text" class="form-control" placeholder="Enter Permanent Address in {{ $lang->name_english }}" name="permanent_address_{{ $lang->key }}" value="{{ isset($row->permanentAddress->{'permanent_address_'.$lang->key}) ? $row->permanentAddress->{'permanent_address_'.$lang->key} : '' }}">
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @foreach (activeLangs() as $lang)
                                                    @if ($lang->key=='english')
                                                    <div class="col-sm-4">
                                                        <div class="form-group ">
                                                            <label>Blood Group {{ $lang->name_english }} </label>
                                                            <select name="blood_group_{{ $lang->key }}" class="form-control">
                                                                <option value="">--Select Blood Group</option>
                                                                <option value="A+" {{ $row->{'blood_group_'.$lang->key} == 'A+' ? 'selected' : '' }}>A+</option>
                                                                <option value="O+" {{ $row->{'blood_group_'.$lang->key} == 'O+' ? 'selected' : '' }}>O+</option>
                                                                <option value="B+" {{ $row->{'blood_group_'.$lang->key} == 'B+' ? 'selected' : '' }}>B+</option>
                                                                <option value="AB+" {{ $row->{'blood_group_'.$lang->key} == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                                <option value="A-" {{ $row->{'blood_group_'.$lang->key} == 'A-' ? 'selected' : '' }}>A-</option>
                                                                <option value="O-" {{ $row->{'blood_group_'.$lang->key} == 'O-' ? 'selected' : '' }}>O-</option>
                                                                <option value="B-" {{ $row->{'blood_group_'.$lang->key} == 'B-' ? 'selected' : '' }}>B-</option>
                                                                <option value="AB-" {{ $row->{'blood_group_'.$lang->key} == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @elseif ($lang->key=='urdu')
                                                    <div class="col-sm-4">
                                                        <div class="form-group ">
                                                            <label>Blood Group {{ $lang->name_english }} </label>
                                                            <select name="blood_group_{{ $lang->key }}" class="form-control">
                                                                <option value="">--Select Blood Group</option>
                                                                <option value="اے مثبت" {{ $row->{'blood_group_'.$lang->key}  == 'اے مثبت' ? 'selected' : '' }}>اے مثبت</option>
                                                                <option value="او مثبت" {{ $row->{'blood_group_'.$lang->key}  == 'او مثبت' ? 'selected' : '' }}>او مثبت</option>
                                                                <option value="بی مثبت" {{ $row->{'blood_group_'.$lang->key}  == 'بی مثبت' ? 'selected' : '' }}>بی مثبت</option>
                                                                <option value="اے بی مثبت" {{ $row->{'blood_group_'.$lang->key}  == 'اے بی مثبت' ? 'selected' : '' }}>اے بی مثبت</option>
                                                                <option value="اے منفی" {{ $row->{'blood_group_'.$lang->key}  == 'اے منفی' ? 'selected' : '' }}>اے منفی</option>
                                                                <option value="او منفی" {{ $row->{'blood_group_'.$lang->key}  == 'او منفی' ? 'selected' : '' }}>او منفی</option>
                                                                <option value="بی منفی" {{ $row->{'blood_group_'.$lang->key}  == 'بی منفی' ? 'selected' : '' }}>بی منفی</option>
                                                                <option value="اے بی منفی" {{ $row->{'blood_group_'.$lang->key}  == 'اے بی منفی' ? 'selected' : '' }}>اے بی منفی</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @elseif($lang->key=="arabic")
                                                    <div class="col-sm-4">
                                                        <div class="form-group ">
                                                            <label>Blood Group {{ $lang->name_english }} </label>
                                                            <select name="blood_group_{{ $lang->key }}" class="form-control">
                                                                <option value="">--Select Blood Group</option>
                                                                <option value="اے إيجابي" {{ $row->{'blood_group_'.$lang->key}  == 'اے إيجابي' ? 'selected' : '' }}>اے إيجابي</option>
                                                                <option value="او إيجابي" {{ $row->{'blood_group_'.$lang->key}  == 'او إيجابي' ? 'selected' : '' }}>او إيجابي</option>
                                                                <option value="بی إيجابي" {{ $row->{'blood_group_'.$lang->key}  == 'بی إيجابي' ? 'selected' : '' }}>بی إيجابي</option>
                                                                <option value="اے بی إيجابي" {{ $row->{'blood_group_'.$lang->key}  == 'اے بی إيجابي' ? 'selected' : '' }}>اے بی إيجابي</option>
                                                                <option value="اے سلبي" {{ $row->{'blood_group_'.$lang->key}  == 'اے سلبي' ? 'selected' : '' }}>اے سلبي</option>
                                                                <option value="او سلبي" {{ $row->{'blood_group_'.$lang->key}  == 'او سلبي' ? 'selected' : '' }}>او سلبي</option>
                                                                <option value="بی سلبي" {{ $row->{'blood_group_'.$lang->key}  == 'بی سلبي' ? 'selected' : '' }}>بی سلبي</option>
                                                                <option value="اے بی سلبي" {{ $row->{'blood_group_'.$lang->key}  == 'اے بی سلبي' ? 'selected' : '' }}>اے بی سلبي</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Tagline {{ $lang->name_english }}</label>
                                                            <input type="text" class="form-control" placeholder="Enter Tagline in {{ $lang->name_english }}" name="tagline_{{ $lang->key }}" value="{{ $row->{'tagline_'.$lang->key} }}">
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                    <div class="col-sm-4" id="country_div">
                                                        <div class="form-group user-country--select ">
                                                            <label class="addres-lable">Country</label>
                                                            <select onchange="adminUpdateProvince(this)"  name="country_id" id="country_id" class="form-control address-select-2">
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
                                                    <div class="col-sm-4" id="province_div">
                                                        <div class="form-group user-country--select ">
                                                            <label class="addres-lable">Province</label>
                                                            <select onchange="adminUpdateDivisions(this)" name="province_id" id="province_select" class="form-control address-select-2">
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

                                                    <div class="col-sm-4" id="division_div">
                                                        <div class="form-group user-country--select ">
                                                            <label class="addres-lable">Division</label>
                                                            <select onchange="adminUpdateDistricts(this)" name="division_id" id="division_select" class="form-control address-select-2">
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


                                                    <div class="col-sm-4" id="city_div">
                                                        <div class="form-group user-country--select ">
                                                            <label class="addres-lable">City</label>
                                                            <select name="city_id" id="city_select" class="form-control address-select-2">
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


                                                    <div class="col-sm-4" id="district_div">
                                                        <div class="form-group user-country--select ">
                                                            <label class="addres-lable">District</label>
                                                            <select onchange="adminUpdateTehsil(this)" name="district_id" id="district_select"  class="form-control address-select-2">
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
                                                        <div class="form-group user-country--select " id="tehsil_div">
                                                            <label class="addres-lable">Tehsil</label>
                                                            <select onchange="adminUpdateZone(this)" name="tehsil_id" id="tehsil_select" class="form-control address-select-2">
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
                                                        <div class="form-group user-country--select " id="zone_div">
                                                            <label class="addres-lable">Branch</label>
                                                            <select id="zone_select"  onchange="adminUpdateCouncils(this)"  name="zone_id" class="form-control address-select-2">
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
                                                        <div class="form-group user-country--select " id="union_council_div">
                                                            <label class="addres-lable">Union Council</label>
                                                            <select  id="union_council_id" name="union_council_id" class="form-control address-select-2">
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


                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="sort-form-select me-2">Post Code</label>
                                                            <input id="postcode" type="text" class="form-control permanent-address" placeholder="Enter Post Code" name="postcode" value="{{ $row->postcode }}">

                                                        </div>
                                                    </div>


                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>About {{ $lang->name_english }}</label>
                                                            <textarea id="about_{{ $lang->key }}" type="text" class="form-control" placeholder="Enter About in {{ $lang->name_english }}" name="about_{{ $lang->key }}" value="{{ $row->{'about_'.$lang->key} }}">{{ $row->{'about_'.$lang->key} }}</textarea>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @foreach (activeLangs() as $lang)
                                                    <div class="col-sm-4">
                                                        <label for="Skills">Skills in {{ $lang->name_english }}</label>
                                                        <input id="tags-input" type="text" class="form-control" data-role="tagsinput" name="skills_{{ $lang->key }}" placeholder="comma seprated skills" value="{{$row->{'skills_'.$lang->key} }}" />
                                                    </div>
                                                    @endforeach
                                                    <div class="col-sm-4">
                                                        <label for="Skills">Roles</label>
                                                        <div class="form-group role-sec">
                                                            <select class="form-control select2 user-inputs" name="role_id" required>
                                                                <option value="">Select Role</option>
                                                                @foreach($roles as $role)
                                                                @php
                                                                $selected = '';
                                                                if ($row->role_id == $role->id) {
                                                                $selected = 'selected';
                                                                }
                                                                @endphp
                                                                <option value="{{$role->id}}" {{$selected}}>{{$role->name_english}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="Skills">Designation</label>
                                                        <div class="form-group">
                                                            <select class="form-control select2 user-inputs" name="designation_id" >
                                                                <option value="">Select Designation</option>
                                                                @foreach($designations as $designation)
                                                                @php
                                                                $selected = '';
                                                                if ($row->designation_id == $designation->id) {
                                                                $selected = 'selected';
                                                                }
                                                                @endphp
                                                                <option value="{{$designation->id}}" {{$selected}}>{{$designation->name_english}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Experience --}}
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#user_exp" aria-expanded="true" aria-controls="name">
                                                    User Experience
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="user_exp" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div id="menue-rows" class="mt-2">
                                                        @foreach($row->experience as $key => $experience)
                                                        {{-- {{ dd($experience) }} --}}
                                                        <div class="row menu-rows">
                                                            @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Title ({{ $lang->name_english }})</label>
                                                                    <input type="text" class="form-control" placeholder="Enter Title in {{ $lang->name_english }}" name="title_{{ $lang->key }}[]" value="{{isset($experience['title_'.$lang->key]) ? $experience['title_'.$lang->key] : ''}}">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Experience Company ({{ $lang->name_english }})</label>
                                                                    <input type="text" class="form-control" placeholder="Enter Experience Company in {{ $lang->name_english }}" name="experience_company_{{ $lang->key }}[]" value="{{isset($experience['experience_company_'.$lang->key]) ? $experience['experience_company_'.$lang->key] : ''}}">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @foreach (activeLangs() as $lang)
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Location ({{ $lang->name_english }})</label>
                                                                    <input type="text" class="form-control" placeholder="Enter Location in {{ $lang->name_english }} " name="experience_location_{{ $lang->key }}[]" value="{{isset($experience['experience_location_'.$lang->key]) ? $experience['experience_location_'.$lang->key] : ''}}">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            <div class="col-sm-12">
                                                                <div class="form-group d-flex align-items-center">
                                                                    <input class="is_currently_working currently_work" id="is_currently_working_{{ $experience['id'] }}" type="checkbox" data-id="{{ $experience['id'] }}" name="is_currently_working[]" value="1" {{ $experience['is_currently_working'] == 1 ? 'checked' : '' }}>
                                                                    <label class="mb-0 ml-1">Do you Currently Work at this company</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Start Date</label>
                                                                    <input class="form-control experience_start_date_dynamic" type="date" data-id="{{ $experience['id'] }}" name="experience_start_date[]" max="<?php echo date("Y-m-d"); ?>" value="{{isset($experience['experience_start_date']) ? $experience['experience_start_date'] : ''}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group experience_end_date_div" id="end_date_{{ $experience['id'] }}"  style="{{ $experience['is_currently_working']==1 ? 'display:none' : 'display:block'}}">
                                                                    <label>End Date</label>
                                                                    <input class="form-control experience_end_date_dynamic_{{ $experience['id'] }}" type="date" name="experience_end_date[]" max="<?php echo date('Y') . '-12-31'; ?>" value="{{isset($experience['experience_end_date']) ? $experience['experience_end_date'] : ''}}">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-1"><button type="button" class="btn btn-danger form-control" style="margin-top:32px;width:auto" onclick="removeRow($(this))">Remove</button></div>

                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                    <div class="d-flex justify-content-end align-items-end w-100">
                                                        <button type="button" class="btn btn-primary mt-2 mb-2" onclick="addNewMenuRow()">Add New Row</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header" id="title-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#user_edu" aria-expanded="true" aria-controls="name">
                                                    User Education
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="user_edu" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div id="edu-menu-rows" class="mt-2">
                                                        @foreach ($row->education as $edu)
                                                        <div id="education-row">
                                                            <div class="row menu-rows">
                                                                @foreach (activeLangs() as $lang)
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Institute {{ $lang->name_english }}</label>
                                                                        <input class="form-control" placeholder="Enter Institute in {{ $lang->name_english }}" name="institute_{{ $lang->key }}[]" value="{{isset($edu['institute_'.$lang->key]) ? $edu['institute_'.$lang->key] : ''}}">
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                                @foreach (activeLangs() as $lang)
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Degree Name {{ $lang->name_english }}</label>
                                                                        <input class="form-control" placeholder="Enter Degree Name in {{ $lang->name_english }}" name="degree_name_{{ $lang->key }}[]" value="{{isset($edu['degree_name_'.$lang->key]) ? $edu['degree_name_'.$lang->key] : ''}}">
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Start Date</label>
                                                                        <input class="form-control start_date_education_dynamic" type="date" data-id="{{ $edu['id'] }}" name="start_date[]" max="<?php echo date("Y-m-d"); ?>" value="{{isset($edu['start_date']) ? $edu['start_date'] : ''}}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group append_experience_end_date_div">
                                                                        <label>End Date</label>
                                                                        <input class="form-control start_date_education_dynamic_{{$edu['id']}}"  type="date" name="end_date[]" max="<?php echo date('Y') . '-12-31'; ?>" value="{{isset($edu['end_date']) ? $edu['end_date'] : ''}}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button type="button" class="btn btn-danger form-control mb-3" style="margin-top:32px;width:auto" onclick="removeRow($(this))">Remove</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-end">
                                                    <button type="button" class="btn btn-primary  mb-2" onclick="appendEducationDiv()">Add New Row</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header" id="general-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colProfessions" aria-expanded="true" aria-controls="colProfessions">
                                                    Professions
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="colProfessions" class="collapse" aria-labelledby="general-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                @forelse($occupations as $occupation)
                                                <div class="parent-checkbox">
                                                    <div class="iagree_radio form-check-new chck-boxe" id="parent_{{ $occupation->id }}">
                                                        <input type="checkbox" name="occupation_ids[]" id="parent-id_{{ $occupation->id }}" onclick="parentFunction($(this))" class="req_q parent-check" value="{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'checked' : '' }} >
                                                        <label for="parent-id_{{ $occupation->id }}">{{ $occupation->title_english }}</label>
                                                    </div>
                                                    <div class="child-checkbox ml-3">
                                                    @if ($occupation->subProfession()->count())
                                                        @foreach ( $occupation->subProfession as $child )
                                                            <div class="iagree_radio form-check-new chck-boxe parent-id_{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'style=display:block' : 'style=display:none'}} >
                                                                <input type="checkbox" name="occupation_ids[]" id="news-regarding_{{ $child->id }}" class="req_q" value="{{ $child->id }}" {{ in_array($child->id, $userOccupationIds) ? 'checked' : '' }} >
                                                                <label for="news-regarding_{{ $child->id }}">{{ $child->title_english }}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="parent-checkbox">
                                                    {{__('app.no-data-available')}}
                                                </div>

                                                @endforelse
                                                <div class="iagree_radio form-check-new chck-boxe my_25">
                                                    <input type="checkbox" name="check" id="other-profession-id" onclick="otherProfessionFunction($(this))" class="req_q" value="true">
                                                    <label for="other-profession-id">{{ __('app.others') }}</label>
                                                </div>
                                                <div class="form-group" id="other-profession" style="display: none">
                                                    <input type="text" name="other_profession" class="form-control" placeholder="{{ __('app.enter-profession') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header" id="general-heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colGeneral" aria-expanded="true" aria-controls="colGeneral">
                                                    General
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="colGeneral" class="collapse" aria-labelledby="general-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Password <span class="text-red">*</span></label>
                                                            <div class="d-flex align-items-center show-hide-pass-eye div-custom">
                                                                <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" value="{{ $row->original_password }}" {{ $action=='edit'?'':'required' }}>
                                                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Repeat Password <span class="text-red">*</span></label>
                                                            <div class="d-flex align-items-center show-hide-pass-eye div-custom">
                                                                <input type="password" class="form-control" placeholder="Enter Password" name="repeat_password" value="{{ $row->original_password }}" {{ $action=='edit'?'':'required' }}>
                                                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <br>
                                                            <div class="icheck-primary d-inline">
                                                                Active
                                                                <input type="radio" name="status" id="active-radio" value="1" {{ ($row->status==1) ? 'checked' : '' }}>
                                                                <label for="active-radio" class="ml-1">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                In-Active
                                                                <input type="radio" name="status" id="in-active-radio" value="0" {{ ($row->status==0) ? 'checked' : '' }}>
                                                                <label for="in-active-radio" class="ml-1">
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Profile Visibility</label>
                                                            <br>
                                                            <div class="icheck-primary d-inline">
                                                                Public
                                                                <input type="radio" name="is_public" id="is_public-radio" value="1" {{ ($row->is_public==1) ? 'checked' : '' }}>
                                                                <label for="is_public-radio" class="ml-1">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                Private
                                                                <input type="radio" name="is_public" id="private-radio" value="0" {{ ($row->is_public==0) ? 'checked' : '' }}>
                                                                <label for="private-radio" class="ml-1">
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="{{ URL('admin/users') }}" class="btn btn-info"> Cancel </a>
                                        <button type="submit" class="btn btn-primary float-right"> {{ ($action=='add') ? 'Save' : 'Update' }} </button>
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
{{-- experience partial --}}
<div id="new-menue-row" style="display: none">
    <div class="row menu-rows">
        @foreach (activeLangs() as $lang)
        <div class="col-sm-4">
            <div class="form-group">
                <label>Title {{ $lang->name_english }}</label>
                <input class="form-control" placeholder="Enter Title in {{ $lang->name_english }}" name="title_{{ $lang->key }}[]">
            </div>
        </div>
        @endforeach
        @foreach (activeLangs() as $lang)
        <div class="col-sm-4">
            <div class="form-group">
                <label>Experience Company {{ $lang->name_english }}</label>
                <input class="form-control" placeholder="Enter Experience Company in {{ $lang->name_english }}" name="experience_company_{{ $lang->key }}[]">
            </div>
        </div>
        @endforeach
        @foreach (activeLangs() as $lang)
        <div class="col-sm-4">
            <div class="form-group">
                <label>Location {{ $lang->name_english }}</label>
                <input class="form-control" placeholder="Enter Location  in {{ $lang->name_english }}" name="experience_location_{{ $lang->key }}[]">
            </div>
        </div>
        @endforeach
        <div class="col-sm-12">
            <div class="form-group d-flex align-items-center">
                <input class="is_currently_working append_currently_work" id="do_check_1" type="checkbox" name="is_currently_working[]" value="1">
                <label class="mb-0 ml-1">Do you Currently Work at this company</label>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Start Date</label>
                <input class="form-control experience_start_date" id="experience_start_date_1" type="date" max="<?php echo date("Y-m-d"); ?>" name="experience_start_date[]" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="end_datee_1" class="form-group append_experience_end_date_div">
                <label>End Date</label>
                <input class="form-control" id="experience_end_date_1" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="experience_end_date[]">
            </div>
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-danger form-control" style="margin-top:32px;width:auto" onclick="removeRow($(this))">Remove</button>
        </div>
    </div>
</div>
{{-- education partial --}}
<div id="new-education-row" style="display: none">
    <div class="row menu-rows">
        @foreach (activeLangs() as $lang)
        <div class="col-sm-4">
            <div class="form-group">
                <label>Institute {{ $lang->name_english }}</label>
                <input class="form-control" placeholder="Enter Institute in {{ $lang->name_english }}" name="institute_{{ $lang->key }}[]">
            </div>
        </div>
        @endforeach
        @foreach (activeLangs() as $lang)
        <div class="col-sm-4">
            <div class="form-group">
                <label>Degree Name {{ $lang->name_english }}</label>
                <input class="form-control" placeholder="Enter Degree Name in {{ $lang->name_english }}" name="degree_name_{{ $lang->key }}[]">
            </div>
        </div>
        @endforeach
        <div class="col-sm-4">
            <div class="form-group">
                <label>Start Date</label>
                <input class="form-control start_date_education" id="start_date_education_1" type="date" max="<?php echo date("Y-m-d"); ?>" name="start_date[]" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group append_experience_end_date_div">
                <label>End Date</label>
                <input class="form-control" id="end_date_education_1" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="end_date[]" required>
            </div>
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-danger form-control" style="margin-top:32px;margin-bottom: 32px;width:auto" onclick="removeEduRow($(this))">Remove</button>
        </div>
    </div>
</div>
<hr>
@endsection

@push('footer-scripts')
{{-- Common adress script --}}
@include('admin.common-script.address-script')
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
{{-- tag --}}
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
<!-- SummerNote -->
<script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
<!-- Page specific script -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.address-select-2').select2();
    });
</script>
<script>
    function addNewMenuRow() {
        var stcounter = $("#counter").val();
        var counter = parseInt(stcounter) + 1;
        var $stringmenu = $('#new-menue-row').clone();
        $stringmenu.find('[id*="1"]').each(function() {
            this.id = this.id.replace('1', counter);
        });
        $('#menue-rows').append($stringmenu.html());
        $("#counter").val(counter);

    }

    function appendEducationDiv() {
        var stcounter = $("#educationCounter").val();
        var counter = parseInt(stcounter) + 1;
        var $stringmenu = $('#new-education-row').clone();
        $stringmenu.find('[id*="1"]').each(function() {
            this.id = this.id.replace('1', counter);
        });
        $('#edu-menu-rows').append($stringmenu.html());
        $("#educationCounter").val(counter);
        // var edu_form_div = $('#new-education-row').html();
        // $('#edu-menu-rows').append(edu_form_div);
    }

    function removeRow(_this) {
        $(_this).parent().parent().remove();
    }

    function removeEduRow(_this) {
        $(_this).parent().parent().remove();
    }

    $(function() {
        $.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('input[name="_token"]').val()
		    }
		});
        $.validator.methods.pakPhone = function(value, element) {
            return this.optional(element) || /^((\+92)?(0092)?(92)?(0)?)(3)([0-9]{2})((-?)|( ?))([0-9]{7})$/gm.test(value);
        }
        $('#customer-form').validate({
            ignore: false,
            rules: {
                phone_number: {
                   
                    remote: {
							type: "post",
							url: "{{ URL('admin/users-phone-validate') }}",
							data: { type: 'validate_data',
								     table: 'users',
									 id : function(){ return $("#id").val() },
									 phone_number : function  ()  { return  $('#phone_number').val()  } ,
								  },

						}
                },
                password: {
                    minlength: 8
                },
                email:{
                    required: true,
						remote: {
							type: "post",
							url: "{{ URL('admin/users-validate') }}",
							data: { type: 'validate_data',
								     table: 'users',
									 id : function(){ return $("#id").val() },
									 email : function  ()  { return  $('#email').val()  } ,
								  },

						}
                },
                repeat_password: {
                    minlength: 8,
                    equalTo: "#password"
                },

            },
            messages: {
                phone_number: {
                    pakPhone: "Please Enter Valid Phone Number",
                    remote:"Phone number should be unique"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                // $(element).addClass('is-invalid');
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
</script>
<script>
    let tagInputEle = $('#tags-input');
    tagInputEle.tagsinput();
    $('#customer-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
<script>
    $(document).on('change', '.currently_work', function() {
        var id = $(this).data("id");
        if (this.checked) {
            $('#end_date_' + id).css('display', 'none')
            return;
        }
        $('#end_date_' + id).css('display', 'block')
    });

    $(document).on('change', '.append_currently_work', function() {
        if (this.checked) {
            // $('.append_experience_end_date_div').css('display', 'none')
            $("#end_datee_" + this.id.split("_")[2]).css('display', 'none')
            return;
        }
        $("#end_datee_" + this.id.split("_")[2]).css('display', 'block')
    });
    $(document).ready(function() {
        $('#about_english').summernote({
            height: 200,
        });
        $('#about_urdu').summernote({
            height: 200,
        });
        $('#about_arabic').summernote({
            height: 200,
        });
    });
</script>
<script>
    function custom_template(obj) {
        var data = $(obj.element).data();
        var text = $(obj.element).text();
        if (data && data['img_src']) {
            img_src = data['img_src'];
            template = $("<div class='country-code-wrap'><img src=\"" + img_src + "\" style=\"width:29%;height:24px;object-fit: contain;\"/><p style=\"font-weight: 500;margin-left: 10px;font-size:14pt;text-align:center;\">" + text + "</p></div>");
            return template;
        }
    }

    var options = {
        'templateSelection': custom_template,
        'templateResult': custom_template,
    }

    $('.js-example-basic-single').select2(options);

    $('.select2-container--default .select2-selection--single').css({
        'border': '1px solid #ccd4da !important;'
    });
</script>
<script>
    $(document).on('change', '.start_date_education', function() {
        var minDate=$("#start_date_education_" + this.id.split("_")[3]).val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $("#end_date_education_" + this.id.split("_")[3]).attr('min', minDate);
        $("#end_date_education_" + this.id.split("_")[3]).val('');
    });

    $(document).on('change', '.start_date_education_dynamic', function() {
        var id = $(this).data("id");
        var minDate=$(this).val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $('.start_date_education_dynamic_' + id).attr('min', minDate);
        $('.start_date_education_dynamic_' + id).val('');
    });
</script>
<script>
    $(document).on('change', '.experience_start_date', function() {
        var minDate=$("#experience_start_date_" + this.id.split("_")[3]).val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $("#experience_end_date_" + this.id.split("_")[3]).attr('min', minDate);
        $("#experience_end_date_" + this.id.split("_")[3]).val('');
    });

    $(document).on('change', '.experience_start_date_dynamic', function() {
        var id = $(this).data("id");
        var minDate=$(this).val();
        var originalDate = new Date(minDate);
        var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = newDate.toISOString().split('T')[0];
        $('.experience_end_date_dynamic_' + id).attr('min', minDate);
        $('.experience_end_date_dynamic_' + id).val('');
    });
</script>
<script>
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
</script>
@endpush
