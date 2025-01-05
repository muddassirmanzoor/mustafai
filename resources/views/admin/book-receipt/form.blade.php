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
		        <h1 class="m-0">Add Book Receipt</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/book-receipts') }}">Book Receipt</a></li>
		          <li class="breadcrumb-item active">Add Book Receipt</li>
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
			                <h3 class="card-title">Book Receipt Form</h3>
			              </div>

			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/book-receipts') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" id="idd" name="id" value="{{ isset($id) ? $id : '' }}">

								 {{-- title  --}}
								<div class="row">
									<div class="col-md-6">
										<label class=" col-form-label"> Title  <span class="text-red">*</span></label>
										<div class="form-group">
											<input type="text" class="form-control" placeholder="Enter  Title " name="title" value="{{ $row->title }}" required="">
										</div>		
									</div>
									{{-- book number  --}}
									<div class="col-md-6">
										<label class=" col-form-label"> Book Number  <span class="text-red">*</span></label>
										<div class="form-group">
											<input type="number" class="form-control" placeholder="Enter Book Number " id="book_number" name="book_number" value="{{ $row->book_number }}" required="">
										</div>		
									</div>
									{{-- leaf Start serial number  --}}
									<div class="col-md-6">
										<label class=" col-form-label"> Leaf Start Serial Number  <span class="text-red">*</span></label>
										<div class="form-group">
											<input  	type="number" class="form-control" placeholder="Enter Leaf Start number" id="leaf_start_number" name="leaf_start_number" value="{{ $row->leaf_start_number }}" required="" {{($row->status <2 )?'': 'readonly'}}>
											<small style="font-size: 58%;" class="text-muted">{{($row->status <2 )?'': 'You can not change leaf numbers after recieved'}}</small>
										</div>		
									</div>
									{{-- leaf End serial number  --}}
									<div class="col-md-6">
										<label class=" col-form-label"> Leaf End Serial Number  <span class="text-red">*</span></label>
										<div class="form-group">
											<input type="number" class="form-control" placeholder="Enter Leaf End number" id="leaf_end_number" name="leaf_end_number" value="{{ $row->leaf_end_number }}" required="" {{($row->status <2 )?'': 'readonly'}}>
											<small style="font-size: 58%;" class="text-muted">{{($row->status <2 )?'': 'You can not change leaf number after recieved'}}</small>

										</div>		
									</div>
									{{-- description  --}}
									<div class="col-md-12 mt-2">
										<label class="form-label">Description </label>
										<div class="form-group">
											<textarea id="summernote"  class=" form-control"  name="description" >{{$row->description}}</textarea>
										</div>
									</div>
			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/book-receipts') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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
				rules: {
					leaf_start_number: {
						required: true,
						remote: {
							type: "post",
							url: "{{ URL('admin/book-receipts') }}",
							data: { type: 'validate_data',
									 id : function(){ return $("#idd").val() },
									 leaf_end_number :  ()=>  { return  $('#leaf_end_number').val()  } ,
									 leaf_start_number : ()=> { return  $('#leaf_start_number').val()  },
								  },
								  complete: function(data){
										
									$( "#leaf_end_number" ).blur();
								  }			
									
							},
							// $( "#leaf_end_number" ).blur();

					},			
					leaf_end_number: {
						required: true,
						remote: {
							type: "post",
							url: "{{ URL('admin/book-receipts') }}",
							data: { type: 'validate_data',
									 id : function(){ return $("#idd").val() },
									 leaf_end_number : function  ()  { return  $('#leaf_end_number').val()  } ,
									 leaf_start_number : function ()  { return  $('#leaf_start_number').val()  } 
									},
									 complete: function(data){
									
										 $( "#leaf_start_number" ).blur();
									 }
						}

					},
					book_number: {
						required: true,
						remote: {
							type: "post",
							url: "{{ URL('admin/book-receipts') }}",
							data: { type: 'book_data',
									 id : function(){ return $("#idd").val() },
									 book_number : function  ()  { return  $('#book_number').val()  } ,
								  }
						}
					}
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
	</script>
@endpush
