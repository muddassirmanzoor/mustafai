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
  <style>
	.error{
		color:red;
	}
  </style>
@endpush

@php
    $url=url()->current();
@endphp
@section('content')
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0">Add Product Payment Method</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/product-payment-method') }}">Product Payment Method</a></li>
		          <li class="breadcrumb-item active">Add Product Payment Method</li>
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
			                <h3 class="card-title">Product Payment Form</h3>
			              </div>
			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="donate-now-form" class="form-horizontal label-left" action="{{ URL('admin/product-payment-method') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" id="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
								<input type="hidden" id="counter" value="2">
								<div class="card-header">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="payment_method">Payment Method <span class="text-red">*</span></label>
                                                <select name="payment_method_id" id="payment_method" class="form-control"  placeholder="Enter Payment Method" required onchange="get_payment_data($(this))"  {{ ($action =='edit')?'disabled':''}}>
                                                    <option value="">--Select Payment Method--</option>
                                                    @foreach($payment_methods as $key=>$val)
                                                    <option value="{{$val->id}}" data-attr="{{$val->id}}" @if(isset($id)) {{ ($val->id ==$id )?'selected':''}}  @endif>{{$val->method_name_english}}</option>
                                                    @endforeach
                                                </select>
											</div>
										</div>
									</div>
									<div class="dynamic_row" id="dynamic_row">
										@if($action == 'edit')
												@foreach($productPaymentMethod as $key=>$val)
                                                {{-- <h6>{{$val->account_title}}</h6> --}}
                                              <div class="row">
                                                    <div class="col-lg-4 mb-xxl-3 mb-1  common_div" style="" >
                                                        <label for="">Account Title:</label>
                                                        <input type="text" class="form-control" value="{{ $val->account_title }}" disabled>
                                                    </div>
                                              </div>
												{{-- {{dd($val->donationPaymentMethodDetails())}} --}}
												@foreach($val->productPaymentMethodDetails as $keyDetails=>$valDonationMethodDetails)
													<div class="row">
                                                        <div class="col-lg-4 mb-xxl-3 mb-1  common_div" style="" >
                                                            <div class="form-group">
                                                                <label >{{$valDonationMethodDetails->PaymentMethodDetail->method_fields_english}} <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control" id="dynamic_fields_{{$valDonationMethodDetails->id}}" name="method_details[{{$valDonationMethodDetails->id}}]" placeholder="Enter value" required value="{{$valDonationMethodDetails->payment_method_field_value}}">
                                                            </div>
                                                            <span  onclick="deleteProductPayment({{$val->id}},$(this))"  class = "btn btn-sm btn-danger">Remove</span>
                                                        </div>
                                                    </div>
                                                    <hr>
												@endforeach

											@endforeach
										@endif

									</div>
								</div>
			                	<div class="card-body">
				                  	<div class="form-group text-right">
				                  		<div class="col-sm-12">
				                  			<a href="{{ URL('admin/product-payment-method') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
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

			//_________Add New Methods In Jquery Validation___________________///
			jQuery.validator.addMethod("phoneNumber", function (phone_number, element) {
				phone_number = phone_number.replace(/\s+/g, "");
				return this.optional(element) || phone_number.length > 9 &&
					phone_number.match(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im);
			}, "Please specify a valid phone number");
			jQuery.validator.addMethod("isEmail", function (email, element) {
				email = email.replace(/\s+/g, "");
				return this.optional(element) || email.length > 9 &&
					email.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
			}, "Please specify a valid email");


			$('#donate-now-form').validate({
				ignore: ":not(:visible)",
				rules: {
					phone: {
						phoneNumber: true
					},
					'method_details[1]': {
						phoneNumber: true
					},
					'method_details[2]': {
						phoneNumber: true
					},
					email: {
						isEmail: true
					},

					//____________for Account Number Validation__________//
					'method_details[4]': {
						required: function (element) {
							return (($("#dynamic_fields_7").val() == '') ? true : false);
						}
					},
					//_______________For Iban Validation
					'method_details[7]': {
						required: function (element) {
							return (($("#dynamic_fields_4").val() == '') ? true : false);
						}
					}


				},

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


		function get_payment_data(_this) {
			var payemnt_method = _this.val();
			var action=$("#action").val();
			$.ajax({
		        url: "{{ url('admin/get-payment-fields') }}"+"/"+payemnt_method+"/"+action,
		        type: "get",
		        success: function(data) {
					console.log(data)
					$("#dynamic_row").html(data)
		        },
		        error: function(data) {
		        }
		    });

		}
        function deleteProductPayment(id,_this)
    {
        $.ajax({
            type: "get",
            url: "{{route('admin.delete-product-payment')}}",
            data:{id:id},
            success: function(result)
            {
                result = JSON.parse(result);
                if(result.status == 'deleted')
                {
                    window.location.href = "{{$url}}";
                    swal("Done!", "Update Shipment.", "success");

                }
            }
        });
    }


	</script>
@endpush
