@extends('admin.layout.app') @push('header-scripts')
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
<!-- for sweet alert  -->{{--
<link rel="stylesheet" href="{{asset('assets/admin/sweetalert/sweetalert.css')}}"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
<style>
div#order-datatable_length {
	padding-top: 24px;
}

div#order-datatable_filter {
	margin-top: -41px;
}
</style> @endpush @section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Orders</h1> </div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
						<li class="breadcrumb-item active">Orders</li>
					</ol>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header"> @if(have_right('add-testimonial'))
							<h3 class="card-title">
                                {{-- <a href="{{ URL('admin/testimonials/create') }}" class="btn btn-primary"> Add New </a> --}}
                            </h3> @endif </div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
									<div class="form-group select-wrap d-flex align-items-center">
										<label class="sort-form-select lable-select me-2">Order Status :</label>
										<div class="select-group w-100">
											<select class="form-control" onchange="applyFilter()" aria-label="Default select example" id="select_order_status" name="select_order_status">
												<option value="">Select Order Status</option>
												<option value="1">Pending</option>
												<option value="2">Shipped</option>
												<option value="3">Completed</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-4 d-flex align-items-center">
									<label class="sort-form-select lable-select  me-2">From :</label>
									<input type="date" name="from_date" id="from_date" onchange="applyFilter()" class="form-control" placeholder="From Date" /> </div>
								<div class="col-md-4 d-flex align-items-center">
									<label class="sort-form-select lable-select  me-2">To :</label>
									<input type="date" name="to_date" id="to_date" onchange="applyFilter()" class="form-control" placeholder="To Date" /> </div>
							</div>
							<hr>
							<table id="order-datatable" class="table table-bordered table-striped" style="width:100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>User Name</th>
										<th>Status</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody> </tbody>
								<tfoot>
									<tr>
										<th>ID</th>
										<th>User Name</th>
										<th>Status</th>
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
</div> @include('admin.modals.order-details') @endsection @push('footer-scripts')
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
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
<!-- Page specific script -->
<!--sweat alert -->{{--
<script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js">
</script>
<script>
$(function() {
	$('#order-datatable').dataTable({
		sort: false,
		pageLength: 50,
		scrollX: true,
		processing: false,
		language: {
			"processing": showOverlayLoader()
		},
		drawCallback: function() {
			hideOverlayLoader();
		},
		responsive: true,
		dom: 'Blfrtip',
		buttons: dataCustomizetbaleAdmin("Orders", arrywidth = ['15%', '70%', '15%'], arraycolumn = [0, 1, 3]),
		lengthMenu: [
			[5, 10, 25, 50, 100, 200, -1],
			[5, 10, 25, 50, 100, 200, "All"]
		],
		serverSide: true,
		ajax: {
			url: "{{ url('admin/orders') }}",
			data: function(d) {
				d._token = "{{ csrf_token() }}", d.order_status = $('#select_order_status').val();
				d.from_date = $('#from_date').val();
				d.to_date = $('#to_date').val();
			}
		},
		columns: [{
			data: 'DT_RowIndex',
			name: 'DT_RowIndex',
			orderable: false,
			searchable: false
		}, {
			data: 'user_id',
			name: 'user_id'
		}, {
			data: 'status',
			name: 'status'
		}, {
			data: 'statusColumn',
			name: 'statusColumn',
			visible: false
		}, {
			data: 'action',
			name: 'action',
			orderable: false,
			searchable: false
		}, ]
	}).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt', function() {});
});

function applyFilter() {
	var oTable = $('#order-datatable').DataTable();
	oTable.ajax.reload();
}

function showOverlayLoader() {}

function hideOverlayLoader() {}

function showOrderDetails(order_id) {
	$.ajax({
		type: 'get',
		url: "{{url('admin/order-details')}}",
		data: {
			'order_id': order_id
		},
		dataType: 'JSON',
		success: function(data) {
			console.log(data);
			$("#order-details-modal").html(data.html);
			$('#order-detail-div').modal('show');
		}
	});
}
//____For Change Status Of Order__//
function orderStatusChange(id, change_number) {
	// if (confirm("Do You Want Update Record!")) {
	swal({
		title: "Do You Want Update Record?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Ok",
		closeOnConfirm: false
	}, function(isConfirm) {
		if(!isConfirm) return;
		var URLStatus = "{{ URL('admin/order-change-status') }}";
		var status = change_number;
		$.ajax({
			url: URLStatus,
			data: {
				'change_number': change_number,
				'id': id
			},
			type: "get",
			beforeSend: function() {
				// $(".button").button("disable");
				$(".preloader").addClass('adminloader');
				$('.animation__shake').show();
			},
			success: function(response) {
				//___to reload datatabel___//
				var oTable = $('#order-datatable').DataTable();
				oTable.ajax.reload();
				if(response == '1') {
					swal("Done!", "Update Shipment.", "success");
				} else {
					swal("Error!", "Shipment is not Update due to some error", "error");
				}
				$(".preloader").removeClass('adminloader');
				$('.animation__shake').hide();
			},
			error: function(data) {
				$(".preloader").removeClass('adminloader');
				$('.animation__shake').hide();
			}
		});
	});
}
//____For Virtual Product Email  share link Order__//
function orderMailVirtual(id) {
	// if (confirm("Do You Want Update Record!")) {
	swal({
		title: "Do You Want Share Email Links?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Ok",
		closeOnConfirm: false
	}, function(isConfirm) {
		if(!isConfirm) return;
		var URLStatus = "{{ URL('admin/virtual-link-mail') }}";
		$.ajax({
			url: URLStatus,
			data: {
				'id': id
			},
			type: "get",
			beforeSend: function() {
				// $(".button").button("disable");
				$(".preloader").addClass('adminloader');
				$('.animation__shake').show();
			},
			success: function(response) {
				//___to reload datatabel___//
				var oTable = $('#order-datatable').DataTable();
				oTable.ajax.reload();
				if(response == '1') {
					swal("Done!", "Email Links sent Successfully!!", "success");
				} else {
					swal("Error!", "Email Links not sent due to some error", "error");
				}
				$(".preloader").removeClass('adminloader');
				$('.animation__shake').hide();
			},
			error: function(data) {
				$(".preloader").removeClass('adminloader');
				$('.animation__shake').hide();
			}
		});
	});
}
</script>
@endpush
