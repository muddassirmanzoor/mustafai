@extends('admin.layout.app')
<style>
    .select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 39px !important;
    user-select: none;
    -webkit-user-select: none;
    }
    .country-code-wrap{
        display: flex;
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0">Create Admin User</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/admins') }}">Admin Users</a></li>
		          <li class="breadcrumb-item active">Create Admin User</li>
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
			                <form id="user-form" action="{{ URL('admin/admins') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}
			                	<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id"  value="{{ isset($id) ? $id : '' }}">
			                	<input type="hidden"  id="idd" value="{{ isset($id) ? encodeDecode($id) : '' }}">
			                  	<div class="row">
			                  		<div class="col-sm-4">
				                      <!-- select -->
				                      <div class="form-group">
				                        <label>Role <span class="text-red">*</span></label>
				                        <select class="form-control" name="role_id">
				                        	<option value="">Select</option>
				                        	@foreach($roles as $role)
				                        		<option value="{{ $role->id }}" {{ ($row->role_id==$role->id) ? 'selected' : '' }}>{{ $role->name_english }}</option>
				                        	@endforeach
				                        </select>
				                      </div>
				                    </div>

				                    <div class="col-sm-4">
				                      <!-- text input -->
				                      <div class="form-group">
				                        <label>First Name <span class="text-red">*</span></label>
				                        <input type="text" class="form-control" placeholder="Enter First Name" name="first_name" value="{{ $row->first_name }}">
				                      </div>
				                    </div>
				                    <div class="col-sm-4">
				                      <div class="form-group">
				                        <label>Last Name <span class="text-red">*</span></label>
				                        <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" value="{{ $row->last_name }}">
				                      </div>
				                    </div>
			                  	</div>

			                  	<div class="row">
				                    <div class="col-sm-4">
				                      <!-- text input -->
				                      <div class="form-group">
				                        <label>Email <span class="text-red">*</span></label>
				                        <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" value="{{ $row->email }}">
				                      </div>
				                    </div>
				                    <div class="col-sm-4">
				                      <div class="form-group">
                                            <label>Phone Number <span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                {{-- <select name="country_code_id" class="form-control country-code-select" name="" id="">
                                                    @foreach ($country_codes as $code)
                                                    <option value="{{ $code->id }}" {{$row->country_code_id == $code->id ? 'selected' :'' }}>{{ $code->phonecode }}</option>
                                                    @endforeach
                                                </select> --}}
                                                <select id="id_select2_example" class="js-example-basic-single country-code-select" name="country_code_id" style="width: 200px;">
                                                    @foreach ($country_codes as $code)
                                                    <option value="{{ $code->id }}" {{$row->country_code_id == $code->id ? 'selected' :'' }}  data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" class="form-control" placeholder="Enter Phone Number" name="phone" data-inputmask='"mask": "(9999)-9999999"' data-mask value="{{ $row->phone }}" required>
                                            </div>
                                        </div>
				                    </div>

				                    <div class="col-sm-4">
                                        <div class="form-group">
                                                <label>Date Of Birth</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" data-inputmask-alias="datetime" name="dob" data-inputmask-inputformat="yyyy-mm-dd" max="<?php echo date("Y-m-d"); ?>" data-mask value="{{ $row->dob }}">
                                                </div>
                                        </div>
				                    </div>
			                  	</div>

			                  	<div class="row">
				                    <div class="col-sm-4">
				                      <!-- text input -->
				                      <div class="form-group">
				                        <label>Password <span class="text-red">*</span></label>
                                        <div class="d-flex align-items-center show-hide-pass-eye div-custom">
                                            <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" value="{{ $row->origional_password }}" {{ $action=='edit'?'':'required' }}>
                                        <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                        </div>
				                      </div>
				                    </div>
				                    <div class="col-sm-4">
				                      <div class="form-group">
				                        <label>Repeat Password <span class="text-red">*</span></label>
                                        <div class="d-flex align-items-center show-hide-pass-eye div-custom">
				                        <input type="password" class="form-control" placeholder="Enter Password" name="repeat_password" value="{{ $row->origional_password }}" {{ $action=='edit'?'':'required' }}>
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
						                        <label for="active-radio">
						                        </label>
					                      	</div>
					                      	<div class="icheck-primary d-inline">
					                      		In-Active
						                        <input type="radio" name="status" id="in-active-radio" value="0" {{ ($row->status==0) ? 'checked' : '' }}>
						                        <label for="in-active-radio">
					                        	</label>
					                      	</div>

					                    </div>
				                    </div>
			                  	</div>
			                  	<div class="row">
			                  		<div class="col-sm-12">
			                  			<a href="{{ URL('admin/admins') }}" class="btn btn-info"> Cancel </a>
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
    <script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>
	<script>
	  $(function () {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('input[name="_token"]').val()
		    }
		});
        $.validator.methods.pakPhone = function( value, element ) {
            return this.optional( element ) || /^((\+92)?(0092)?(92)?(0)?)(3)([0-9]{2})((-?)|( ?))([0-9]{7})$/gm.test( value );
        }
	  	//   $('[data-mask]').inputmask();
		  bsCustomFileInput.init();
		  $('#user-form').validate({
		    rules:
		    {
		      role_id: {
		        required: true,
		      },
		      first_name: {
		        required: true,
		      },
		      last_name: {
		        required: true,
		      },
			  email:{
				required: true,
					remote: {
						type: "post",
						url: "{{ URL('admin/users-validate') }}",
						data: { type: 'validate_data',
								table: 'admins',
								id : function(){ return $("#idd").val() },
								email : function  ()  { return  $('#email').val()  } ,
							},

					}
              },
		      phone: {
		        pakPhone: true
		      },
		      password: {
		        minlength: 8
		      },
		      repeat_password: {
		        minlength: 8,
		        equalTo : "#password"
		      },
		    //   dob: {
		    //     required: true,
		    //   },
		      status: {
		        required: true,
		      }
		    },
            messages: {
                phone: "Please Enter valid Phone Number"
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
		});
	</script>
    <script>

        function custom_template(obj)
        {
            var data = $(obj.element).data();
            var text = $(obj.element).text();
            if(data && data['img_src']){
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

        $('.select2-container--default .select2-selection--single').css({'border': '1px solid #ccd4da !important;'});

</script>
@endpush
