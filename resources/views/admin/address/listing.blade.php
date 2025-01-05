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
@endpush

@section('content')
	<!-- Content Wrapper. Contains page content -->

	@php
		$segment_url=request()->segment(2);
	@endphp
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0 text-capitalize">{{request()->segment(2)}}</h1>
				<input type="hidden" id="segmentType" value="{{request()->segment(2)}}">
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item active text-capitalize	">{{$segment_url}} </li>
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
				      	@if(have_right('Create-Address'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/'.request()->segment(2).'/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif
				      </div>
				      <div class="card-body">
				        <table id="pages-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
								@if(request()->segment(2) != 'union-councils' && (request()->segment(2) == 'provinces' ||request()->segment(2) == 'countries'))
				                <th >Countries</th>
                                @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'divisions')
                                <th >Provinces</th>
                                @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'districts')
                                <th >Divisions</th>
                                @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'tehsils')
                                <th >Districts</th>
                                @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'branches')
                                <th >Tehsils</th>
                                @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'cities')
                                <th >Provinces</th>
								@else
									<th>Tehsils</th>
									<th>Branches</th>
								@endif
				                <th>{{ str_replace('-', ' ', ucfirst(request()->segment(2))) }}</th>
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
                                    @if(request()->segment(2) != 'union-councils' && (request()->segment(2) == 'provinces' ||request()->segment(2) == 'countries'))
                                    <th >Countries</th>
                                    @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'divisions')
                                    <th >Provinces</th>
                                    @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'districts')
                                    <th >Divisions</th>
                                    @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'tehsils')
                                    <th >Districts</th>
                                    @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'branches')
                                    <th >Tehsils</th>
                                    @elseif(request()->segment(2) != 'union-councils' && request()->segment(2) == 'cities')
                                    <th >Provinces</th>
                                    @else
                                        <th>Tehsils</th>
                                        <th>Branches</th>
                                    @endif
                                    <th>{{ str_replace('-', ' ', ucfirst(request()->segment(2))) }}</th>
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

		var visiblee = true;
		if ($('#segmentType').val() == "countries") {
			visiblee = false;
			var arrywidth = ['33%','33%','33%'];
			var arraycolumn = [0,2,4];
		}else{
			var arrywidth = ['25%','25%','25%','25%'];
			var arraycolumn = [0,1,2,4];
		}

		var str = $("#segmentType").val();

		// Capitalize the first letter of the string
		var capitalizedStr = str.charAt(0).toUpperCase() + str.slice(1).replace(/-/g, ' ')

		if($('#segmentType').val() != "union-councils")
		{

			$('#pages-datatable').dataTable(
			{
				sort: false,
				pageLength: 50,
				scrollX: true,
				processing: false,
				language: { "processing": showOverlayLoader()},
				drawCallback : function( ) {
					hideOverlayLoader();
				},
				dom: 'Blfrtip',
				responsive: true,
				// buttons: dataCustomizetbaleAdmin("Address", arrywidth = [ '25%', '25%', '25%','25%'] , arraycolumn = [0,1,2,4]),
				buttons: dataCustomizetbaleAdmin(capitalizedStr, arrywidth = arrywidth , arraycolumn = arraycolumn),
				lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
				serverSide: true,
				ajax: "{{ url('admin/'.$segment_url) }}",
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
					{data: 'parent_name', name: 'parent_name',visible: visiblee},
					{data: 'name_english', name: 'name_english'},
					{data: 'status', name: 'status'},
					{data: 'statusColumn',name: 'statusColumn',visible: false},
					{data: 'action', name: 'action', orderable: false, searchable: false},
				]
			}).on( 'length.dt', function () {
			}).on('page.dt', function () {
			}).on( 'order.dt', function () {
			}).on( 'search.dt', function () {
			});
		}else{
			$('#pages-datatable').dataTable(
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
				buttons: dataCustomizetbaleAdmin(capitalizedStr, arrywidth = [ '20%', '20%', '20%','20%','20%'] , arraycolumn = [0,1,2,3,5]),
				lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
				serverSide: true,
				ajax: "{{ url('admin/'.$segment_url) }}",
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
					{data: 'tehsil', name: 'tehsil'},
					{data: 'branch', name: 'branch'},
					{data: 'name_english', name: 'name_english'},
					{data: 'status', name: 'status'},
					{data: 'statusColumn',name: 'statusColumn',visible: false},
					{data: 'action', name: 'action', orderable: false, searchable: false},
				]
			}).on( 'length.dt', function () {
			}).on('page.dt', function () {
			}).on( 'order.dt', function () {
			}).on( 'search.dt', function () {
			});

		}
	  });
	  function showOverlayLoader()
	  {
	  }
	  function hideOverlayLoader()
	  {
	  }
	</script>
@endpush
