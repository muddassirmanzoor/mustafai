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

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
        div#admins-datatable_length {
        padding-top: 24px;
        }
        div#admins-datatable_filter {
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
		        <h1 class="m-0">Admin</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item active">Admin Users</li>
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
				      	@if(have_right('Create-Admin'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/admins/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif
				      </div>
				      <div class="card-body">
						<div class="row">
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                                <div class="form-group select-wrap d-flex">
                                    <label class="sort-form-select  lable-select me-2">Status :</label>
                                    <div class="select-group w-100">
                                        <select class="form-control"   aria-label="Default select example" id="select_status" name="select_status">
											<option value="">All</option>
											<option value="active">Active</option>
											<option value="inactive">inActive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 searching-element-select">
                                <div class="form-group select-wrap d-flex">
                                    <label class="sort-form-select lable-select me-2">Email :</label>
                                    <div class="select-group w-100">
                                        <select class="form-control js-example-basic-single2"   aria-label="Default select example" id="select_email" name="select_email">
                                            <option value="">Search Email</option>
                                            @foreach ($emails as $email)
                                             <option value="{{ $email }}">{{ $email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 searching-element-select">
                                <div class="form-group select-wrap d-flex">
                                    <label class="sort-form-select lable-select me-2">Roles :</label>
                                    <div class="select-group w-100">
                                        <select class="js-example-basic-single form-control"   aria-label="Default select example" id="select_role" name="select_role" >
											<option value="">Select Role</option>
											@foreach ($roles as $role)
												<option value="{{ $role->id }}">{{ $role->name_english }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
				        <table id="admins-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
				                <th>Role</th>
				                <th>Name</th>
				                <th>Email</th>
				                <th>D-O-B</th>
				                <th>Status</th>
				                <th>Status</th>
				                <th>Actions</th>
				              </tr>
				          </thead>
				          <tbody>
				          </tbody>
				          <tfoot>
				              <tr>
				                <th>ID</th>
				                <th>Role</th>
				                <th>Name</th>
				                <th>Email</th>
				                <th>D-O-B</th>
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
	<script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>
	<script>
	  $(function () {
		$('.js-example-basic-single').select2({
			placeholder: "Select Data",
		});
		$('.js-example-basic-single2').select2({
			placeholder: "Select Data",
		});

	    $('#admins-datatable').dataTable(
      	{

      		sort: false,
			pageLength: 50,
			scrollX: true,
			processing: false,
			language: { "processing": showOverlayLoader()},
			drawCallback : function( ) {
		        hideOverlayLoader();
		    },
			initComplete: function(settings, json) {
        console.log(json); // log the data received by the DataTable
    },
			responsive: true,
			dom: 'Blfrtip',
            buttons: dataCustomizetbaleAdmin("Admin Users",  arrywidth= [ '2%', '20%', '20%','20%', '20%','19%'],  arraycolumn = [0,1,2,3,4,6]),
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
			ajax: {
                    url: "{{ url('admin/admins') }}",
                    data: function(d) {
						d._token = "{{ csrf_token() }}",
						d.statusColumn = $('#select_status').val();
						d.email = $('#select_email').val();
						d.role = $('#select_role').val();
                    }
                },
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'role', name: 'role'},
				{data: 'name', name: 'name'},
				{data: 'email', name: 'email'},
				{data: 'dob', name: 'dob'},
				{data: 'status', name: 'status'},
                {data: 'statusColumn',name: 'statusColumn',visible: false},
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

	  $('#select_status, #select_role, #select_email').change(function () {
			$('#admins-datatable').DataTable().ajax.reload();
		});
	</script>
@endpush
