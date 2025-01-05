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
    div#posts-datatable_length {
    padding-top: 24px;
    }
    div#posts-datatable_filter {
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
		        <h1 class="m-0">Posts</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item active">Posts</li>
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
				      	@if(have_right('Create-Posts'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/posts/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif
				      </div>
				      <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select lable-select me-2">Post Type :</label>
                                    <div class="select-group w-100">
                                        <select class="form-control" onchange="applyFilter()"  aria-label="Default select example" id="select_post_type" name="select_post_type">
                                            <option value="">Select Post Type</option>
                                            <option value="1">Simple Post</option>
                                            <option value="2">Job Post</option>
                                            <option value="3">Announcement</option>
                                            <option value="4">Product Post</option>
                                            <option value="5">Blood Post</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
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
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">From :</label>
                                <input type="date" name="from_date" id="from_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="sort-form-select lable-select  me-2">To :</label>
                                <input type="date" name="to_date" id="to_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />
                            </div>
                        </div>
                        <hr>
				        <table id="posts-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
				                <th>Title English</th>
				                <th>Username</th>
                                <th>Post Type</th>
                                <th>Status</th>
                                <th>Post Type</th>
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
                                  <th>Username</th>
                                  <th>Post Type</th>
                                  <th>Status</th>
                                  <th>Post Type</th>
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

    <!-- Modal -->
    <div class="modal fade" id="showPostModal" tabindex="-1" role="dialog" aria-labelledby="showPostModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body post_data">
                    <!-- dynamic body append here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
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
	    $('#posts-datatable').dataTable(
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
            buttons: dataCustomizetbaleAdmin("Posts",  arrywidth= [ '10%', '70%','10%','10%'],  arraycolumn =[0,1,4,5]),
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
            ajax: {
                    url: "{{ url('admin/posts') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                        d.post_type = $('#select_post_type').val();
                        d.status = $('#select_status').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'title_english', name: 'title_english'},
				{data: 'user_name', name: 'user_name'},
				{data: 'post_type', name: 'post_type'},
				{data: 'status', name: 'status'},
                {data: 'post_typeHidden',name: 'post_typeHidden',visible: false,searchable: false},
                {data: 'statusHidden',name: 'statusHidden',visible: false,searchable: false},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			]
	    }).on( 'length.dt', function () {
		}).on('page.dt', function () {
	    }).on( 'order.dt', function () {
		}).on( 'search.dt', function () {
		});
	  });
      function applyFilter()
        {
            var oTable = $('#posts-datatable').DataTable();
            oTable.ajax.reload();
        }
	  function showOverlayLoader()
	  {
	  }
	  function hideOverlayLoader()
	  {
	  }
	</script>

  <script>
      $(document).on('click', '.show_post', function () {
          let postId = $(this).attr('data-post-id');
          $.ajax({
              type: "POST",
              url: "{{route('admin.post.show')}}",
              data: {
                  '_token': "{{csrf_token()}}",
                  'post_id': postId
              },
              success: function(result) {
                  if (result.status === 200) {
                      $('.post_data').html(result.html)
                  }
              }
          });
      });
  </script>

@endpush
