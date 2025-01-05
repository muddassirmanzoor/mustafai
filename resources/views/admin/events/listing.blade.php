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
  <style>
    div#events-datatable_length {
    padding-top: 24px;
    }
    div#events-datatable_filter {
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
		        <h1 class="m-0">Events</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item active">Events</li>
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
				      	@if(have_right('Create-Events'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/events/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif
				      </div>
				      <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <h5 class="filter-heading">Event Creation Data Range :</h5>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">From :</label>
                                <input type="date" name="from_date" id="from_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">To :</label>
                                <input type="date" name="to_date" id="to_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <h5 class="filter-heading">Event Encounter Date Range :</h5>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">Start Date :</label>
                                <input type="date" name="start_date" id="start_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">End Date :</label>
                                <input type="date" name="end_date" id="end_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <h5 class="filter-heading">Event Status :</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select  lable-select me-2">Status :</label>
                                    <div class="select-group w-100">
                                        <select class="form-control" onchange="applyFilter()"  aria-label="Default select example" id="select_status" name="select_status">
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">From :</label>
                                <input type="date" name="from_date" id="from_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">To :</label>
                                <input type="date" name="to_date" id="to_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">Start Date :</label>
                                <input type="date" name="start_date" id="start_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">End Date :</label>
                                <input type="date" name="end_date" id="end_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />
                            </div> --}}
                        </div>
                        <hr>
				        <table id="events-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
				                <th>Title English</th>
				                <th>Title Urdu</th>
				                <th>Start Date Time</th>
				                <th>End Date Time</th>
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
                                  <th>Title English</th>
                                  <th>Title Urdu</th>
                                  <th>Start Date Time</th>
                                  <th>End Date Time</th>
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
	<script>
	  $(function () {
	    $('#events-datatable').dataTable(
      	{
      		sort: false,
			pageLength: 50,
			scrollX: true,
			processing: false,
			language: { "processing": showOverlayLoader()},
			drawCallback : function( ) {
		        hideOverlayLoader();
		    },
			responsive: true,
			dom: 'Blfrtip',
            buttons: dataCustomizetbaleAdmin("Events",  arrywidth= [ '20%','20%','20%','20%','20%'],  arraycolumn = [0,1,3,4,6]),
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
            ajax: {
                    url: "{{ url('admin/events') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                        d.status = $('#select_status').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'title_english', name: 'title_english'},
				{data: 'title_urdu', name: 'title_urdu'},
				{data: 'start_date_time', name: 'start_date_time'},
				{data: 'end_date_time', name: 'end_date_time'},
				{data: 'status', name: 'status'},
                {data: 'statusColumn',name: 'statusColumn',visible: false},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			],
            "drawCallback": function() {
                    $(".white-space").parent('td').css('white-space','nowrap');
                },
	    }).on( 'length.dt', function () {
		}).on('page.dt', function () {
	    }).on( 'order.dt', function () {
		}).on( 'search.dt', function () {
		});
	  });
      function applyFilter()
        {
            var oTable = $('#events-datatable').DataTable();
            oTable.ajax.reload();
        }

	  function showOverlayLoader()
	  {
	  }
	  function hideOverlayLoader()
	  {
	  }
	</script>
@endpush
