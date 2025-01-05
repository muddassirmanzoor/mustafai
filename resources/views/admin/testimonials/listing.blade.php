@extends('admin.layout.app')

@push('header-scripts')
   <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
  <style>



	.switch {
	position: relative;
	display: inline-block;
	width: 60px;
	height: 34px;
	}

	.switch input {
	opacity: 0;
	width: 0;
	height: 0;
	}

	.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: .4s;
	transition: .4s;
	}

	.slider:before {
	position: absolute;
	content: "";
	height: 26px;
	width: 26px;
	left: 4px;
	bottom: 4px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
	}

	input:checked + .slider {
	background-color: #2196F3;
	}

	input:focus + .slider {
	box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	border-radius: 34px;
	}

	.slider.round:before {
	border-radius: 50%;
	}
    div#testimonials-datatable_length {
    padding-top: 24px;
    }
    div#testimonials-datatable_filter {
    margin-top: -41px;
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
		        <h1 class="m-0">Testimonials</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item active">Testimonials</li>
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
				  <div class="col-12">
				    <div class="card">
				      <div class="card-header">
				      	@if(have_right('Create-Testimonials'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/testimonials/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif
				      </div>
				      <div class="card-body">
				        <table id="testimonials-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
				                <th>Name</th>
				                <th>Message</th>
				                <th style="width: 200px">Order</th>
				                <th>Featured</th>
				                <th>Status</th>
				                <th>Actions</th>
				              </tr>
				          </thead>
				          <tbody>
				          </tbody>
				          <tfoot>
				              <tr>
                                  <th>ID</th>
                                  <th>Name</th>
				                	<th>Message</th>
				                	<th>Order</th>
				                	<th>Featured</th>
				                	<th>Status</th>
                                  <th>Actions</th>
				              </tr>
				          </tfoot>
				        </table>
				      </div>
				    </div>
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
	<!-- DataTables  & Plugins -->
	<script src="{{asset('assets/admin/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/admin/jszip/jszip.min.js')}}"></script>
	<script src="{{asset('assets/admin/pdfmake/pdfmake.min.js')}}"></script>
	<script src="{{asset('assets/admin/pdfmake/vfs_fonts.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('assets/admin/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
	<!-- AdminLTE App -->
	<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
    <!-- Global Var -->
	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
	<!-- Page specific script -->
		<!--sweat alert -->
	{{-- <script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js">
	</script>
	<script>
	  $(function () {
	    $('#testimonials-datatable').dataTable(
      	{
      		sort: false,
			pageLength: 50,
			scrollX: true,
			processing: false,
			language: { "processing": showOverlayLoader()},
			drawCallback : function( ) {
				$(".order-set-button").parent().addClass("set-orders");
		        hideOverlayLoader();
		    },
			responsive: true,
			dom: 'Blfrtip',
            buttons: dataCustomizetbaleAdmin("Testimonials",  arrywidth= ['10%', '10%', '80%'],  arraycolumn = [0, 1, 2]),
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
			ajax: "{{ url('admin/testimonials') }}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'name_english', name: 'name_english'},
				{data: 'message_english', name: 'message_english'},
				{data: 'order', name: 'order'},
				{data: 'featured', name: 'featured'},
				{data: 'status', name: 'status'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			]
	    }).on( 'length.dt', function () {
		}).on('page.dt', function () {
	    }).on( 'order.dt', function () {
		}).on( 'search.dt', function () {
		});
	  });
	  function showOverlayLoader()
	  {
	  }
	  function hideOverlayLoader()
	  {
	  }

	  	//___ add/remove feature donation function___//
	function is_featured_testimonial(_this,id){
		var URL = "{{ URL('admin/testimonials/featured-testimonials') }}";
		var status = ($(_this).prop('checked') == true) ? 1:0;
		$.ajax({
			url: URL+'/'+id,
			data: {status:status},
			type: "get",
			success: function(response) {
			//___to reload datatabel___//
			var oTable = $('#testimonials-datatable').DataTable();
			oTable.ajax.reload();
			if(response == '1'){
				swal("Done!", "Status Changed.", "success");
			}else{
				swal("Error!", "You can not set more than 5 testimonials as featured, please remove any one", "error");
			}
			},
			error: function(data) {
			}
		});
	}

	function isPositiveInteger(str) {
		if (typeof str !== 'string') {
			return false;
		}

		const num = Number(str);

		if (Number.isInteger(num) && num > 0) {
			return true;
		}

		return false;
	}

	$(document).on('keyup', '.testimonial_input', function (_this) {

		if(!isPositiveInteger($(this).val())){
				$(this).val('');
				return 0;
		 }
		let testimonialId = $(this).attr('data-testimonial-id')
          let order = $(this).val()

          if (_this.which == 13) {
			callback(testimonialId,order)
          }
      })
	  $(document).on("click",".order-set-button",function() {
            $(this).prop('disabled', true);
			let testimonialId = $(this).attr('data-testimonial-id')
			let order = $("#order_set_"+testimonialId).val()
			callback(testimonialId,order)
	 });

	 var callback = function(testimonialId,order) {
			$.ajax({
					type: "POST",
					url: "{{ route('testimonial.order') }}",
					data: {
						'_token': "{{ csrf_token() }}",
						'id': testimonialId,
						'order': order
					},
					success: function(result) {
						if (result.status === 1) {
							swal("Success!", "Order Updated.", "success");
                            $("button[data-testimonial-id='" + testimonialId + "']").prop('disabled', false);
						}
					}
			});

	  };

      // set order of testimonial script
    //   $(document).on('keypress', '.testimonial_input', function (_this) {
    //       let testimonialId = $(this).attr('data-testimonial-id')
    //       let order = $(this).val()

    //       if (_this.which == 13) {
    //           $.ajax({
    //               type: "POST",
    //               url: "{{ route('testimonial.order') }}",
    //               data: {
    //                   '_token': "{{ csrf_token() }}",
    //                   'id': testimonialId,
    //                   'order': order
    //               },
    //               success: function(result) {
    //                   if (result.status === 1) {
    //                       swal("Success!", "Order Updated.", "success");
    //                   }
    //               }
    //           });
    //       }
    //   })

	</script>
@endpush
