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

<!-- select2 -->
 <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.css')}}">

  {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
@endpush

@section('content')
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0">Add Library Section</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/ceomessage') }}">Library Seciton</a></li>
		          <li class="breadcrumb-item active">Add Library Section</li>
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
			                <h3 class="card-title">Library Section Form</h3>
			              </div>

			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/library-section') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

								<div class="accordion" id="accordionExample">
									<div class="card">
										{{-- For Title  --}}

										<!-- For Title -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#title" aria-expanded="true" aria-controls="title">
													Title
												</button>
											</h2>
										</div>

										<div id="title" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
														@foreach (activeLangs() as $lang)
															<div class="col-sm-4">
																<div class="form-group ">
																<label>Title ({{ ucfirst($lang->key)}}) <span class="text-red">*</span></label>
																<input type="text" class="form-control" placeholder="Title {{ ucfirst($lang->key) }}" name="title_{{$lang->key}}" required value="{{ $row->{'title_'.$lang->key.''} }}">
																</div>
															</div>
														@endforeach
														{{-- <div class="col-sm-4">
															<div class="form-group ">
																<label>Title Urdu <span class="text-red">*</span></label>
																<input type="text" class="form-control" placeholder="Title Urdu" name="title_urdu" required value="{{ $row->title_urdu }}">
															</div>
														</div>
														<div class="col-sm-4">
															<div class="form-group ">
																<label>Title Arabic <span class="text-red">*</span></label>
																<input type="text" class="form-control" placeholder="Title Arabic" name="title_arabic" required value="{{ $row->title_arabic }}">
															</div>
														</div> --}}
													</div>
											</div>
										</div>


										<!-- For Title -->
										<div class="card-header " id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#extentions" aria-expanded="true" aria-controls="title">
													Select Extensions
												</button>
											</h2>
										</div>

										<div id="extentions" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
														<div class="col-sm-4">
															<div class="form-group ">
																@php
																	$exArray = array();
																@endphp
																@if($action == 'edit')
																	@foreach($row->libraryextentions as $val)
																			@php
																				array_push($exArray, $val->extention);
																			@endphp
																	@endforeach
																@endif

																{{-- <input type="text" value="{{$exArray}}"> --}}
																<label>Extensions  <span class="text-red">*</span></label>
																<select class="select2 form-control" name="extentions[]" value="JPG,JPEG" multiple="multiple" style="width: 100%"  required>
																	@if($row->id == 1)
																	<optgroup label="Images Extention">
																			<option value="JPG" {{in_array("JPG", $exArray)?'selected':''}}>JPG</option>
																			<option value="JPEG"  {{in_array("JPEG", $exArray)?'selected':''}}>JPEG</option>
																			<option value="JFIF" {{in_array("JFIF", $exArray)?'selected':''}}>JFIF</option>
																			<option value="PNG" {{in_array("PNG", $exArray)?'selected':''}}> PNG</option>
																			<option value="SVG" {{in_array("SVG", $exArray)?'selected':''}}>SVG</option>
																			<option value="WEBP" {{in_array("WEBP", $exArray)?'selected':''}}>WEBP</option>
																			<option value="GIF" {{in_array("GIF", $exArray)?'selected':''}}>GIF</option>
																	  </optgroup>
																	  @elseif($row->id == 2)
																	  <optgroup label="Video Extentions">
																			<option value="MP4" {{in_array("MP4", $exArray)?'selected':''}}>MP4</option>
																			<option value="MOV" {{in_array("MOV", $exArray)?'selected':''}}>MOV</option>
																			<option value="WMV" {{in_array("WMV", $exArray)?'selected':''}}>WMV</option>
																			<option value="AVI" {{in_array("AVI", $exArray)?'selected':''}}>AVI</option>
																			<option value="AVCHD" {{in_array("AVCHD", $exArray)?'selected':''}}>AVCHD</option>
																			<option value="MKV" {{in_array("MKV", $exArray)?'selected':''}}>MKV</option>
																	  </optgroup>
																	  @elseif($row->id == 3)
																	  <optgroup label="Audio Extentions">
																			<option value="M4A" {{in_array("M4A", $exArray)?'selected':''}}>M4A</option>
																			<option value="FLAC" {{in_array("FLAC", $exArray)?'selected':''}}>FLAC</option>
																			<option value="MP3" {{in_array("MP3", $exArray)?'selected':''}}>MP3</option>
																			<option value="WAV" {{in_array("WAV", $exArray)?'selected':''}}>WAV</option>
																			<option value="WMA" {{in_array("WMA", $exArray)?'selected':''}}>WMA</option>
																			<option value="AAC" {{in_array("AAC", $exArray)?'selected':''}}>AAC</option>
																	  </optgroup>
																	  @elseif($row->id == 4)
																	  <optgroup label="Book Extentions">
																			<option value="PDF" {{in_array("PDF", $exArray)?'selected':''}}>PDF</option>
																	    </optgroup>
																		@elseif($row->id == 5)
																		<optgroup label="Document Extentions">
																			<option value="PDF" {{in_array("PDF", $exArray)?'selected':''}}>PDF</option>
																	  	</optgroup>
																	  @else
																	@endif
																  </select>
															</div>
														</div>
													</div>
											</div>
										</div>
										<!-- For Image -->
										<!-- For message title general  -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
													Icon
												</button>
											</h2>
										</div>

										<div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Image <span class="text-red">*</span></label>
													<div class="col-sm-6">
														<input type="File" class="form-control imageinpt"  data-preview-id="sample_image" placeholder="Enter Message Title " id="imageinpt" name="icon" value="{{ $row->icon }}" @if(!$row->icon) required @endif accept="image/*">
														<small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 214 x 214 </span> pixels</small>

													</div>
													<div class="col-sm-2">
														@if($row->icon)
														<a href="{{ getS3File($row->icon) }}" target="_blank">
															<img src="{{ getS3File($row->icon) }}" alt="" id="sample_image" width="100" height="100">
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


										<!-- For Libray Type -->
										{{-- <div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#libtype" aria-expanded="true" aria-controls="message">
													Library Type
												</button>
											</h2>
										</div> --}}

										{{-- <div id="libtype" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="row">
													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Library Type <span class="text-red">*</span></label>
																<select class="form-control" name="lib_type" required>
																	<option value="">-- Select Dropdown --</option>
																	<option value="0" @if($action == 'edit')  {{($row->lib_type   == 0?'selected':'') }}  @endif>Library </option>
																	<option value="1" @if($action == 'edit')  {{ ($row->lib_type  == 1?'selected':'')  }}  @endif>Gallery </option>
																</select>
														</div>
													</div>

												</div>

											</div>
										</div> --}}
										<!-- For Message -->
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
																<label class="form-label">Content ({{ucfirst($lang->key)}}) <span class="text-red">*</span></label>
																	<textarea id="summernote"  class="summernote " name="content_{{$lang->key}}" required>{{$row->{'content_'.$lang->key.''} }}</textarea>
															</div>
														</div>
													@endforeach
													{{-- <div class="col-sm-4">
														<div class="form-group">
															<label class="form-label">Content (Urdu) <span class="text-red">*</span></label>
															<textarea id="summernote"  class="summernote " name="content_urdu" required>{{$row->content_urdu}}</textarea>

														</div>
													</div>

													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Content (Arabic) <span class="text-red">*</span></label>
															<textarea id="summernote"  class="summernote " name="content_arabic" required>{{$row->content_arabic}}</textarea>

														</div>
													</div> --}}
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

			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/library-section') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
	{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

	    <!-- select2 -->
		<script src="{{asset('assets/admin/select2/js/select2.full.js')}}"></script>
	<script>
	  $(function () {
		  	// Summernote
	    	$('.summernote').summernote({
	    		height: ($(window).height() - 300),
			    callbacks: {
			        onImageUpload: function(image) {
			            uploadImage(image[0]);
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
	</script>
	<script>
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
		    data.append("path", 'ceo-message');
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
		    data.append("path", 'ceo-message');
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
		$(document).ready(function() {
    		$('.select2').select2({

			});
		});
	</script>
@endpush
