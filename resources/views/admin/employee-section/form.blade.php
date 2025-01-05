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
		        <h1 class="m-0">Add Team Members</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/employee-sections') }}">Team Members</a></li>
		          <li class="breadcrumb-item active">Add Team Members</li>
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
			                <h3 class="card-title">Team Members Form</h3>
			              </div>
			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/employee-sections') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

								<div class="accordion" id="accordionExample">
									<div class="card">
										<!-- For Designation -->
										<div class="card-header" id="designation-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#coldesgination" aria-expanded="true" aria-controls="coldesgination">
													Designation
												</button>
											</h2>
										</div>
										<div id="coldesgination" class="collapse" aria-labelledby="desgination" data-parent="#accordionExample">
											<div class="card-body">
											  	<div class="row" >
                                                    @foreach (activeLangs() as $lang)
													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Designation ({{ $lang->name_english }}) <span class="text-red">*</span></label>
																<input type="text"  class="form-control" placeholder="Enter Designation {{ $lang->name_english }}" name="designation_{{ $lang->key }}" value="{{$row->{'designation_'.$lang->key} }}" required="">
														</div>
													</div>
                                                    @endforeach
											  	</div>
											</div>
										</div>
										<!-- For Section   -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#section" aria-expanded="true" aria-controls="section">
													 Section Details
												</button>
											</h2>
										</div>
										<div id="section" class="collapse" aria-labelledby="section-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Select Section <span class="text-red">*</span></label>
													<div class="col-sm-6">
														<select class="form-control" name="section_id" required>
                                                            <option value="">-- Select Dropdown --</option>
															@forelse($sections as $section)
																<option value="{{ $section->id }}"  {{ $section->id == $row->section_id? 'selected':''  }}>{{ $section->name_english }}</option>
															@empty
																<option value="">no type found!</option>
															@endforelse
														</select>
													</div>
												</div>
											</div>
										</div>
										<!-- For Image -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" id="image-btn-link" type="button" data-toggle="collapse" data-target="#image-accordian" aria-expanded="true" aria-controls="image">
													 Image
												</button>
											</h2>
										</div>
										<div id="image-accordian" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Image <span class="text-red">*</span></label>
													<div class="col-sm-6">
														<input type="file"  class="form-control" placeholder="Select Image" name="image" id="imageinpt" @if (!$row->image) required @endif>
														{{-- <br/> --}}
														<small>
															<span class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 400 x 400 </span> pixels</span> <br />
															"Add image with transparent background.
															On top, there should be 100-pixel gap between the object and the image border"
														</small>
													</div>
													<div class="col-sm-2">
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
											  	<div class="row" >
                                                    @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="form-label">Name ({{ $lang->name_english }}) <span class="text-red">*</span></label>
                                                                    <input type="text"  class="form-control" placeholder="Enter Name {{ $lang->name_english }}" name="name_{{ $lang->key }}" value="{{$row->{'name_'.$lang->key} }}" required="">
                                                            </div>
                                                        </div>
                                                    @endforeach
											  	</div>
											</div>
										</div>
										<!-- For Short Description -->
										<div class="card-header" id="short-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colShortDescription" aria-expanded="true" aria-controls="colShortDescription">
												Short Description
												</button>
											</h2>
										</div>
										<div id="colShortDescription" class="collapse" aria-labelledby="short-heading" data-parent="#accordionExample">
											<div class="card-body">
											  	<div class="row">
                                                    @foreach (activeLangs() as $lang)
													<div class=" col-sm-4 ">
														<div class="form-group ">
															<label class=" col-form-label">Short Description ({{ $lang->name_english }}) <span class="text-red">*</span></label>
															<textarea class="form-control ml-2" name="short_description_{{ $lang->key }}" placeholder="Enter Short Description In {{ $lang->name_english }}" required="">{{$row->{'short_description_'.$lang->key} }}</textarea>
														</div>
													</div>
                                                    @endforeach
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
																<label class=" col-form-label">Content  ({{ $lang->name_english }}) <span class="text-red">*</span></label>
																<textarea id="summernote"  class="summernote " name="content_{{ $lang->key }}" required="">{{$row->{'content_'.$lang->key} }}</textarea>
															</div>
														</div>
													@endforeach
													{{-- <div class="col-sm-4">
														<div class="form-group ">
															<label class=" col-form-label">Content (Urdu) <span class="text-red">*</span></label>
															<textarea id="summernote2" class="summernote "  name="content_urdu" required="">{{$row->content_urdu}}</textarea>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group ">
															<label class=" col-form-label">Content (Arabic) <span class="text-red">*</span></label>
															<textarea id="summernote3" class="summernote "  name="content_arabic" required="">{{$row->content_arabic}}</textarea>
														</div>
													</div> --}}
												</div>
											</div>
										</div>
										<!-- For Albums -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#albums" aria-expanded="true" aria-controls="albums">
													Assigned Albums
												</button>
											</h2>
										</div>
										<div id="albums" class="collapse" aria-labelledby="albums-heading" data-parent="#accordionExample">
											<div class="">
												<div class="row card card-primary">
													@foreach($album_type as $type)
														<h3 class="album-headings col-form-label card-header">
															{{ $type->title_english }}
														</h3>
														<div class="col-12 card-body max-limit-of-albums">
															<div class="form-group ">
																<ul style="list-style: none;" class="tree-of-albums">
																	@forelse($type->libraryAlbum as $album)
																		@if(!empty($album->parent_id))
																			@continue;
																		@endif
																		<li>
																			<input type="checkbox" name="albums[][{{ $type->id }}]" value="{{$album->id}}" {{ (in_array($album->id,$assigned_albums)) ? 'checked':'' }}>
																			{{  $album->title_english }}
																			@if(count($album->childrens->toArray()))
																				@include('admin.partial.album-child',['childs' => $album->childrens,'type_id'=>$type->id,'assigned_albums' => $assigned_albums])
																			@endif
																		</li>
																	@empty
																		<li>
																			Not Created
																		</li>
																	@endforelse
																</ul>
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
									  </div>
									</div>
								</div>
			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/employee-sections') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
        $('#imageinpt').attr('required', true);
		});
		// $("select").prepend("<option value='' selected='selected'>--select option--</option>");

	</script>
   {{-- <script>
    $(document).ready(function() {
      // When the form is submitted
      $('#page-form').on('submit', function(e) {
        e.preventDefault();

        // Get the file input element
        var input = $('#imageinpt')[0];

        // Get the file object
        var file = input.files[0];

        // Create a new Image object
        var img = new Image();

        // Set the source of the image to the file object
        img.src = URL.createObjectURL(file);

        // Wait for the image to load
        img.onload = function() {
          // Get the dimensions of the image
          var width = this.naturalWidth;
          var height = this.naturalHeight;

          // Check if the dimensions are valid
          if (width == 400 && height == 400) {
            // The dimensions are valid
            console.log('The image dimensions are valid.');
            // Submit the form if the dimensions are valid
            $('#page-form')[0].submit();
          } else {
            // The dimensions are not valid
            console.log('The image dimensions are not valid.');
            $('#image-error').css('display', 'block');
            $('#image-accordian').addClass('show');
          }
        };
      });
    });
  </script> --}}
  {{-- <script>
    $(document).ready(function() {
        $('input[type="file"]').on('change', function() {
            var inputFile = $(this);
            var file = inputFile[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    var width = this.width;
                    var height = this.height;
                    if (width == 400 && height == 400) {
                        $('#image-error').css('display', 'none');
                        // alert('Valid image dimensions');
                    } else {
                        // alert('Invalid image dimensions');
                        $('#image-error').css('display', 'block');
                        $('#image-accordian').addClass('show');
                    }
                };
            };
            reader.readAsDataURL(file);
        });
    });
</script> --}}
@endpush
