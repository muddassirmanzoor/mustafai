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
  <!-- SummerNote -->
  <link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">
@endpush

@section('content')
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0">Add Donor</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/donors') }}">Blood Donors</a></li>
		          <li class="breadcrumb-item active">Add Donor</li>
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
			                <h3 class="card-title">Add Donor</h3>
			              </div>

			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/donors') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">


								<div class="row ">
									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">Full Name  <span class="text-red">*</span></label>
										<input type="text"  class="form-control" placeholder="Enter Full Name" name="full_name" value="{{$row->full_name}}" required="">
									</div>
									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">Email <span class="text-red">*</span></label>
										<input type="email"  class="form-control" placeholder="Enter Email" name="email" value="{{$row->email}}" required="">
									</div>
									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">Phone Number <span class="text-red">*</span></label>
										<input type="text"  class="form-control" placeholder="Enter Phone Number "  name="phone_number" required="" data-inputmask='"mask": "(999) 999-99999"' data-mask value="{{isset($row->phone_number) ? $row->phone_number: ''}}">
									</div>
									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">Blood Group <span class="text-red">*</span></label>
										<select name="blood_group"  id="blood_group"  class="form-control" placeholder="Enter Blood Group" required>
											<option value="">--select blood group</option>
											<option value="A+" {{ $row->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
											<option value="O+" {{ $row->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
											<option value="B+" {{ $row->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
											<option value="AB+" {{ $row->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
											<option value="A-" {{ $row->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
											<option value="O-" {{ $row->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
											<option value="B-" {{ $row->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
											<option value="AB-" {{ $row->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>

										</select>
										{{-- <input type="text"  class="form-control" placeholder="Enter Blood Group" name="blood_group" value="{{$row->blood_group}}" required=""> --}}
									</div>
                                    <div class="col-md-3 mt-3 form-group">
                                        <div class="form-group">
                                            <label class="form-label">Cities <span class="text-red">*</span></label>
                                            <select name="city_id"  id="city_id"  class="form-control" placeholder="Enter City" required>
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option  value="{{ $city->id }}" {{ $row->city_id == $city->id ? 'selected' : '' }}> {{ $city->name_english }}</option>
                                            @endforeach

                                            </select>
                                        </div>
                                    </div>

									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">DOB <span class="text-red">*</span></label>
										<input type="date"  class="form-control" placeholder="Enter DOB" name="dob" value="{{$row->dob}}" required="">
									</div>
									<div class="col-md-3 mt-3 form-group">
										<label class="form-label">Image <span class="text-red">*</span></label>
										<input type="File" class="form-control" placeholder="select Image Donor " id="imageinpt" name="image" value="{{ $row->image }}" @if(!$row->image) required @endif>

									</div>
									<div class="col-md-3 form-group">
										@if($row->image)
										<a href="{{ getS3File($row->image) }}" target="_blank">
											<img src="{{ getS3File($row->image) }}" alt="" id="sample_image" width="100" height="100">
										</a>
											<button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
										@else
											<a href="javascrit:void(0)" target="_blank">
												 <img id="sample_image" src="{{ getS3File('images/dummy-images/dummy.PNG') }}" alt="your image" width="60" height="60" />
											</a>
											<button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
										  @endif
									</div>

								</div>
                                <div class="row">
                                    <div class="  col-md-3 mt-3 ">
                                        <div class="form-group">
                                            <label class="">Cannot donate blood until following date : </label>
                                            <input type="date" class="form-control" name="eligible_after" value="{{ $row->eligible_after }}">
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="  col-md-3 mt-3 ">
										<div class="form-group ">
											<label class="">Status</label>

												<div class="icheck-primary d-inline ml-2">
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


			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/donors') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
	<!-- Page specific script -->
	<script>
		$(function () {
			$('[data-mask]').inputmask();
		//   custom_select_option();

		  	// Summernote
	    	$('.summernote').summernote({
	    		height: ($(window).height() - 300),
			    callbacks: {
			        onImageUpload: function(image) {
			            uploadImage(image[0],$(this));
			        },
					onMediaDelete : function(target) {
						// alert(target[0].src)
						console.log(target) ;
						removeImage(target[0].src)
					}
			    }
	    	})

		  	$('#page-form').validate({
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
				invalidHandler: function(e,validator)
				{
					// loop through the errors:
					for (var i=0;i<validator.errorList.length;i++){
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

		var toggler = document.getElementsByClassName("caret");
		var i;

		for (i = 0; i < toggler.length; i++) {
		  toggler[i].addEventListener("click", function() {
		    this.parentElement.querySelector(".nested").classList.toggle("active");
		    this.classList.toggle("caret-down");
		  });
		}

		function uploadImage(image,_this)
		{
			var data = new FormData();
		    data.append("image", image);
		    data.append("path", 'employee-sections');
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
		    data.append("path", 'employee-sections');

		    $.ajax({
		        url: "{{ route('admin.deleteeditorimage') }}",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: data,
		        type: "post",
		        success: function() {
					console.log("image deletes")
		        },
		        error: function(data) {
		        }
		    });
		 }
		 imageinpt.onchange = evt => {
			const [file] = imageinpt.files
			if (file) {
				sample_image.src = URL.createObjectURL(file)
				$('#sample_image').parent().attr('href',URL.createObjectURL(file));
			}
		}
		$('#clear_image').click(function(){
		event.preventDefault();
		$('#imageinpt').val('');
		$('#sample_image').parent().attr('href','https://www.freeiconspng.com/uploads/no-image-icon-6.png');
		$('#sample_image').attr('src','https://www.freeiconspng.com/uploads/no-image-icon-6.png');
		});
		// $("select").prepend("<option value='' selected='selected'>--select option--</option>");

	</script>
@endpush
