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
		        <h1 class="m-0">Create Announcement</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/announcements') }}">Announcements</a></li>
		          <li class="breadcrumb-item active">Create Announcement</li>
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
                  <h3 class="card-title">Announcement Form</h3>
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                  <form id="announcement-form" class="form-horizontal label-left" action="{{ URL('admin/announcements') }}" enctype="multipart/form-data" method="POST"> 
                    {!! csrf_field() !!}

                    <input type="hidden" name="action" value="{{$action}}">
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                    <div class="accordion" id="accordionExample">
                      <div class="card">
                        <!-- For Title -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#name" aria-expanded="true" aria-controls="name">
													Title
												</button>
											</h2>
										</div>

										<div id="name" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
											  	<div class="row" >
													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Titile (English) *</label>	
															<input type="text" class="form-control" placeholder="Enter Title In English" name="title_english" value="{{ $row->title_english }}" required="">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group ">
															<label class=" form-label">Title (urdu) *</label>	
                              <input type="text" class="form-control" placeholder="Enter Title In Urdu" name="title_urdu" value="{{ $row->title_urdu }}" required="">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Title (Arabic) *</label>	
                              <input type="text" class="form-control" placeholder="Enter Title In Arabic" name="title_arabic" value="{{ $row->title_arabic }}" required="">
														</div>
													</div>
											  	</div>
											</div>
										</div>
                    <!-- For  Description -->
                      <div class="card-header" id="description-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colDescription" aria-expanded="true" aria-controls="colDescription">
                          Description
                          </button>
                        </h2>
                      </div>
                      <div id="colDescription" class="collapse" aria-labelledby="description-heading" data-parent="#accordionExample">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <label class=" form-label">Description (English) *</label>															
                                <textarea class="form-control" name="description_english" rows="5" cols="70" required>{{$row->description_english}}</textarea>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <label class=" form-label">Description (Urdu) *</label>
                                
                                <textarea class="form-control" name="description_urdu" rows="5" cols="70" required>{{$row->description_urdu}}</textarea>
                                  
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <label class=" form-label">Description (Arabic) *</label>
                                <textarea class="form-control" name="description_arabic" rows="5" cols="70" required>{{$row->description_arabic}}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                       
                        <!-- For Image -->
                        <div class="card-header" id="title-heading">
                          <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
                              Image
                            </button>
                          </h2>
                        </div>

                        <div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Image *</label>
                              <div class="col-sm-6">
                                <input type="file"  class="form-control" placeholder="Select Image" name="image" id="imageinpt" value="" @if(!$row->image) required @endif >

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

                        <!-- General -->
                        <div class="card-header" id="general-heading">
                          <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colGeneral" aria-expanded="true" aria-controls="colGeneral">
                            General
                            </button>
                          </h2>
                        </div>
                        
                        <div id="colGeneral" class="collapse" aria-labelledby="general-heading" data-parent="#accordionExample">
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
                            <a href="{{ URL('admin/pages') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
		  	$('#announcement-form').validate({
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