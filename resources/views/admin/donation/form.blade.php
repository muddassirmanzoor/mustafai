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
		        <h1 class="m-0">Add Donation </h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/donations') }}">Donations</a></li>
		          <li class="breadcrumb-item active">Add Donation</li>
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
			                <h3 class="card-title">Donation Form</h3>
			              </div>

			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/donations') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

								<div class="accordion" id="accordionExample">
									<div class="card">
										<!-- For Title -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colsTitle" aria-expanded="true" aria-controls="colsTitle">
													Title
												</button>
											</h2>
										</div>

										<div id="colsTitle" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
											  	<div class="row" >
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Title ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                    <input type="text"  class="form-control" placeholder="Enter Title {{ $lang->name_english }}" name="title_{{ $lang->key }}" value="{{$row->{'title_'.$lang->key} }}" required="">
                                                            </div>
                                                        </div>
                                                    @endforeach
											  	</div>
											</div>
										</div>
										<!-- For Price -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#Price" aria-expanded="true" aria-controls="Price">
													Donation Amount
												</button>
											</h2>
										</div>

										<div id="Price" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
														<div class="col-sm-4">
															<div class="form-group ">
																<label class="form-label">Donation Amount <span class="text-red">*</span></label>
																	<input type="number"   class="form-control" placeholder="Enter Donation Amount" name="price" value="{{$row->price}}" required="">
															</div>
														</div>
													</div>
											</div>
										</div>
										<!-- For Donation Category -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#donation_category" aria-expanded="true" aria-controls="Price">
													Donation Category
												</button>
											</h2>
										</div>

										<div id="donation_category" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
														<div class="col-sm-4">
                                                            <div class="form-group ">
                                                              <label class="form-label">Donation Category <span class="text-red">*</span></label>
                                                                <select class="form-control" name="donation_category_id" required>
                                                                  <option value="">--select donation category---</option>
                                                                  @forelse($donation_categories as $category)
                                                                    <option value="{{ $category->id }}" @if($category->id == $row->donation_category_id) selected @endif>{{ $category->name_english }}</option>
                                                                  @empty
                                                                    <option value="">no type found!</option>
                                                                  @endforelse
                                                                </select>
                                                            </div>
                                                          </div>
													</div>
											</div>
										</div>

										<!-- For DonationType -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colPrice" aria-expanded="true" aria-controls="colPrice">
													Donation Type
												</button>
											</h2>
										</div>

										<div id="colPrice" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
														<div class="col-sm-4">
															<div class="form-group ">
																<label class="form-label">Donation Type <span class="text-red">*</span></label>
																	<select name="donation_type" class="form-control" required>
																		<option value="">--select donation</option>
																		<option value="1" @if($row->donation_type == 1) selected @endif>Public</option>
																		<option value="2"  @if($row->donation_type == 2) selected @endif>Private</option>
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
													 File
												</button>
											</h2>
										</div>

										<div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">File <span class="text-red">*</span></label>
													<div class="col-sm-6">
														<input type="file"  class="form-control" placeholder="Select Image" id="imageinpt" name="file" value="" >
														<small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 240 x 240 </span> pixels</small>
													</div>
													<div class="col-sm-2">
														@if($row->file)
														<a href="{{ getS3File($row->file) }}" target="_blank">
															<img src="{{ getS3File($row->file) }}" alt="" id="sample_image" width="100" height="100">
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

											</div>
										</div>
										<!-- For Content -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#message" aria-expanded="true" aria-controls="message">
													Description
												</button>
											</h2>
										</div>

										<div id="message" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="row">
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Description ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                    <textarea id="summernote"  class="summernote" name="description_{{ $lang->key }}" required>{{$row->{'description_'.$lang->key} }}</textarea>
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
										<!-- end gernal -->
									</div>
								</div>
			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/donations') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
		    data.append("path", 'slider-images');

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
			data.append("path", 'slider-images');

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
	</script>
@endpush
