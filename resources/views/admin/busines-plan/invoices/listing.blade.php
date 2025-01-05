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
   <!-- select2 -->
   <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.css')}}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
   <style>
    div#busines-plan-datatable_length {
    padding-top: 24px;
    }
    div#busines-plan-datatable_filter {
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
		        <h1 class="m-0">Business Plan Invoices</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ url('admin/busines_plans') }}">Business Plans</a></li>
		          <li class="breadcrumb-item active">Busines Plan Invoices</li>
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
				      <div class="card-body">
                        {{-- <div class="row">
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
                        </div> --}}
                        <hr>
				        <table id="busines-plan-datatable" class="table table-bordered table-striped" style="width:100%">
				          <thead>
				              <tr>
				                <th>ID</th>
				                <th>Date</th>
				                <th>Assign To</th>
				                <th>Status</th>
				                <th>Actions</th>
				              </tr>
				          </thead>
				          <tbody>
							  @foreach($results as $key => $result)
							  	<tr>
									<td>{{ $key+1 }}</td>
									<td>{{$result['date']}}</td>
									<td>{{$result['user_name']}}</td>
									<td>{{$result['status']}}</td>
									<td>
										@if($result['status'] != 'Paid')
										<a href="javascript:void(0)" data-selected-date="{{ $result['date'] }}" data-plan-id="{{$result['plan_id']}}" data-toggle="modal"  onclick="showPayNow(this)" class="btn btn-primary ml-2" title="Pay Now"><i class="fa fa-check-square" aria-hidden="true"></i></a>
										@endif
									</td>
								</tr>
							  @endforeach
				          </tbody>
				          <tfoot>
				              <tr>
                                  <th>ID</th>
								  <th>Date</th>
								  <th>Assign To</th>
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

	 <!-- PayNow -->
	 <div class="modal fade" id="payNowModal" tabindex="-1" role="dialog" aria-labelledby="payNowModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pay Now</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body application_data">
                    <table class="table">
						<thead>
							<tr>
								<th>Total Users</th>
								<th>Total Approved Users</th>
								<th>Total Amount</th>
								<th>Recieved Amount</th>
							</tr>
						</thead>
						<tbody id="pay-now-body">

						</tbody>
					</table>

					<form action="" id="pay-now-form">
						<input type="hidden" name="plan_id" class="form-control" id="usr-plan">
						<input type="hidden" name="selected_date" class="form-control" id="usr-selected-date">

						<div class="form-group">
							<label for="usr">Selected Date:</label>
							<input type="text" class="form-control" id="usr-date" readonly>
						</div>
						<div class="form-group">
							<label for="usr">User Name:</label>
							<input type="text" class="form-control" id="usr-name" readonly>
						</div>
						<div class="form-group">
							<label for="usr">Amount:</label>
							<input type="text" name="amount" class="form-control" id="usr-amount" required>
						</div>
						<div class="form-group">
							<label for="pwd">Reciept:</label>
							<input type="file" class="form-control" id="reciept" name="reciept" required>
						</div>
						<div class="form-group">
							<label for="pwd">Accounts:</label>
							<div id="account-details">
							</div>
						</div>
					</form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="payNow($(this))">Pay Now</button>
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
	<!-- AdminLTE for demo purposes -->
	<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
    <!-- Global Var -->
	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
	<!-- Page specific script -->
  <!-- select2 -->
  <script src="{{asset('assets/admin/select2/js/select2.full.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

  <!-- jquery-validation -->
	<script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
	<script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>

	<script>
        $(document).ready( function () {
        $('#busines-plan-datatable').DataTable({
            dom: 'Blfrtip',
            buttons: dataCustomizetbaleAdmin("Business Plan Invoices",  arrywidth= [ '10%', '30%', '30%','30%',],  arraycolumn =  [0,1,2,3]),
        });
        } );

		function showPayNow(_this)
		{
			let planID = $(_this).attr('data-plan-id');
			let selectedDate = $(_this).attr('data-selected-date');

			$.ajax({
				type: "POST",
				url: "{{route('admin.business.application.pay-now-details')}}",
				data: {
					'_token': "{{csrf_token()}}",
					'planID': planID,
					'selectedDate' : selectedDate,
				},
				success: function(result) {
					result = JSON.parse(result);

					if(result.status)
					{
						var tableHtml = '<tr><td>'+result.total_required_users+'</td><td>'+result.total_approved_users+'</td><td>'+result.total_amount+'</td><td>'+result.total_recieved_amount+'</td></tr>';
						$('#pay-now-body').html(tableHtml);

						$('#usr-plan').val(planID);
						$('#usr-selected-date').val(selectedDate);
						$('#usr-date').val(result.selected_date_human);
						$('#usr-name').val(result.user_name);
						$('#usr-amount').val(result.total_recieved_amount);

						var accountDiv = $('#account-details');
						accountDiv.html('');
						$.each(result.accounts, function( index, value ) {
							var accountHTML = '</br><label>'+index+'</label>';
							accountHTML = accountHTML + '</br><input type="radio" name="account_id" class="" value="'+value.id+'" required>';
							accountHTML = accountHTML + '<label for="">'+value.name+'</label>';
							accountDiv.append(accountHTML);
						});

						$('#payNowModal').modal('show');
					}
					else
					{
						swal("Oops!", result.message, "error")
					}
				}
			});
		}

		function payNow(_this)
		{
			if($('#pay-now-form').valid())
			{
				var formData = new FormData($('#pay-now-form')[0]);
				$.ajax({
					type: "POST",
					url: "{{route('admin.business.application.pay-now')}}",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					dataType: 'JSON',
					success: function(data) {
						if(data.status == 1)
						{
							swal("Cool!", data.message, "success")
							$('#payNowModal').modal('hide');
						}
						else
						{
							swal("Oops!", data.message, "error")
						}
					},
				});
			}
		}
	</script>

@endpush
