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
		        <h1 class="m-0">Create Thumbnail</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/testimonials') }}">Thumbnail</a></li>
		          <li class="breadcrumb-item active">Create Thumbnail</li>
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
                  <h3 class="card-title">Thumbnail Form</h3>
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                  <form id="thumbnail-form" class="form-horizontal label-left" action="{{ URL('admin/thumbnails') }}" enctype="multipart/form-data" method="POST"> 
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="{{$action}}">
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
                    <input type="hidden" id="asset_path" value="{{ url('/')}}">

                    
                      <div class="row" >
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label>library Type</label>
                              <select class="form-control" name="type_id" id="library_type"  required onchange="get_image_thumb($(this))">
                                <option value="">----Select Library Type----</option>
                                @foreach($libraryTypes as $libraryType)       
                                  <option value="{{$libraryType->id .'_&'. $libraryType->img_thumb_nail }} " >{{$libraryType->title_english}}</option>
                                @endforeach
                              </select>                         
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label>Select File</label>
														  <input type="file"  class="form-control" placeholder="Select Image" id="imageinpt" name="file" value=""   accept="image/*"  required>
                            </div>
                          </div>
                          <div class="col-sm-4 ">
                            <div class="form-group">
                                <label>Thumbnail Image</label>
                                <a href="javascrit:void(0)" target="_blank" style="display:inherit;">
                                  <img id="sample_image" src="{{ asset('images/dummy-images/dummy.PNG') }}" alt="your image" width="60" height="60" />
                                </a>
                              {{-- <button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button> --}}
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group text-right">
                          <div class="col-sm-12">
                            <a href="{{ URL('admin/dashboard') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
		  	$('#thumbnail-form').validate({
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
   var UrlThumb = '{{url("admin/get-thumb-img")}}';
    function get_image_thumb(_this){
      var imge=_this.val().split('_&')[1];
      if( imge.length !=1){
        let sample_image = $("#asset_path").val() + '/' + imge;
        $("#sample_image").attr('src',sample_image);
      }else{
        $("#sample_image").attr('src','https://www.freeiconspng.com/uploads/no-image-icon-6.png');

      }
    }

	</script>
@endpush