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

  <!-- for sweet alert  -->

 {{-- <link rel="stylesheet" href="{{asset('assets/admin/sweetalert/sweetalert.css')}}"> --}}

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

div#users-datatable_length {

padding-top: 24px;

}

div#users-datatable_filter {

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

		        <h1 class="m-0">Users</h1>

		      </div><!-- /.col -->

		      <div class="col-sm-6">

		        <ol class="breadcrumb float-sm-right">

		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

		          <li class="breadcrumb-item active">users</li>

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

				      	@if(have_right('Create-User'))

					        <h3 class="card-title">

					        	<a href="{{ URL('admin/users/create') }}" class="btn btn-primary"> Add New </a>

					        </h3>

				        @endif

                        <br>

				      </div>

				      <div class="card-body">

                        <div class="row">

                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">

                                <div class="form-group select-wrap d-flex">

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

                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 searching-element-select">

                                <div class="form-group select-wrap d-flex">

                                    <label class="sort-form-select lable-select me-2">Professions (Parent) :</label>

                                    <div class="select-group w-100">

                                        <select class="form-control js-example-basic-single" onchange="applyFilter(this)"  aria-label="Default select example" id="select_occupation" name="select_occupation">

                                            <option value="">Select Professions</option>

                                            @foreach ($occupations as $occupation)

                                             <option value="{{ $occupation->id }}">{{ $occupation->title_english }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 searching-element-select">

                                <div class="form-group select-wrap d-flex">

                                    <label class="sort-form-select lable-select me-2">Professions (Child) :</label>

                                    <div class="select-group w-100">

                                        <select class="js-example-basic-single form-control" onchange="applyOccupationFilter()"  aria-label="Default select example" id="select_child_occupation" name="select_child_occupation[]" multiple>

                                            <option value="">Select Professions</option>

                                        </select>

                                    </div>

                                </div>

                            </div>



                        </div>

                        <div class="row input-daterange">

                            <div class="col-md-4 d-flex">

                                <label class="sort-form-select lable-select  me-2">From :</label>

                                <input type="date" name="from_date" max="<?php echo date("Y-m-d"); ?>" id="from_date" onchange="applyFilter()" class="form-control" placeholder="From Date"  />

                            </div>

                            <div class="col-md-4 d-flex ">

                                <label class="sort-form-select lable-select  me-2">To :</label>

                                <input type="date" name="to_date" max="<?php echo date("Y-m-d"); ?>" id="to_date" onchange="applyFilter()" class="form-control" placeholder="To Date"  />

                            </div>

                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">

                                <div class="form-group select-wrap d-flex">

                                    <label class="sort-form-select  lable-select me-2 ">Profile Completed :</label>

                                    <div class="select-group w-100">

                                        <select class="form-control" onchange="applyFilter()"  aria-label="Default select example" id="select_profile_status" name="select_profile_status">

                                            <option value="">Select Status</option>

                                            <option value="completed">Completed</option>

                                            <option value="incompleted">Incompleted</option>

                                        </select>

                                    </div>

                                </div>

                            </div>



                        </div>

                        <div class="row">

                           <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 ">

                                <div class="form-group select-wrap d-flex">

                                    <label class="sort-form-select  lable-select me-2 w-5"> User Role :</label>

                                    <div class="select-group w-100">

                                        <select class="form-control" onchange="applyFilter()"  aria-label="Default select example" id="select_role" name="select_role">

                                            <option value="">Select Role</option>

                                            @foreach ($roles as $role)

                                             <option value="{{ $role->id }}">{{ $role->name_english }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3 searching-element-select">
                            <div class="form-group select-wrap d-flex">
                                <label class="sort-form-select lable-select me-2">Designation :</label>
                                <div class="select-group w-100">
                                    <select class="form-control" onchange="applyFilter(this)"  aria-label="Default select example" id="select_designation" name="select_designation">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name_english }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>



                        </div>

                        <div class="row border border-info " >



                            <div class="col-sm-4" id="country_div">

                                <div class="form-group user-country--select ">

                                    <label class="addres-lable">Country</label>

                                    <select onchange="adminUpdateProvince(this)"  name="country_id" id="country_id" class="form-control address-select-2">

                                        <option value="">Select Country</option>

                                        @forelse($countries as $country)

                                        <option value="{{ $country->id }}">

                                            {{ $country->name }}</option>



                                        @empty

                                        <option value="">No Contry exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4" id="province_div">

                                <div class="form-group user-country--select ">

                                    <label class="addres-lable">Province</label>

                                    <select onchange="adminUpdateDivisions(this)" name="province_id" id="province_select" class="form-control address-select-2">

                                        <option value="">Select Province</option>

                                        @forelse($provinces as $province)

                                            <option value="{{ $province->id }}">

                                                {{ $province->name }}</option>

                                                @empty

                                                    <option value="">No Province exists</option>

                                                @endforelse



                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4" id="division_div">

                                <div class="form-group user-country--select ">

                                    <label class="addres-lable">Division</label>

                                    <select onchange="adminUpdateDistricts(this)" name="division_id" id="division_select" class="form-control address-select-2">

                                        <option value="">Select Division</option>

                                        @forelse($divisions as $division)

                                        <option value="{{ $division->id }}">

                                            {{ $division->name }}</option>



                                        @empty

                                        <option value="">No Division exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4" id="city_div">

                                <div class="form-group user-country--select ">

                                    <label class="addres-lable">City</label>

                                    <select name="city_id" id="city_select" class="form-control address-select-2">

                                        <option value="">Select City</option>

                                        @forelse($cities as $city)

                                        <option value="{{ $city->id }}">

                                            {{ $city->name }}</option>



                                        @empty

                                        <option value="">No City exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4" id="district_div">

                                <div class="form-group user-country--select ">

                                    <label class="addres-lable">District</label>

                                    <select onchange="adminUpdateTehsil(this)" name="district_id" id="district_select"  class="form-control address-select-2">

                                        <option value="">Select District</option>

                                        @forelse($districts as $district)

                                        <option value="{{ $district->id }}">

                                            {{ $district->name }}</option>



                                        @empty

                                        <option value="">No District exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4">

                                <div class="form-group user-country--select " id="tehsil_div">

                                    <label class="addres-lable">Tehsil</label>

                                    <select onchange="adminUpdateZone(this)" name="tehsil_id" id="tehsil_select" class="form-control address-select-2">

                                        <option value="">Select Tehsil</option>

                                        @forelse($tehsils as $tehsil)

                                        <option value="{{ $tehsil->id }}">

                                            {{ $tehsil->name }}</option>



                                        @empty

                                        <option value="">No Tehsil exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4">

                                <div class="form-group user-country--select " id="zone_div">

                                    <label class="addres-lable">Branch</label>

                                    <select id="zone_select"  onchange="adminUpdateCouncils(this)"  name="zone_id" class="form-control address-select-2">

                                        <option value="">Select Branch</option>

                                        @forelse($zones as $zone)

                                        <option value="{{ $zone->id }}">

                                            {{ $zone->name }}</option>



                                        @empty

                                        <option value="">No Branch exists</option>

                                    @endforelse

                                    </select>

                                </div>

                            </div>



                            <div class="col-sm-4">

                                <div class="form-group user-country--select " id="union_council_div">

                                    <label class="addres-lable">Union Council</label>

                                    <select  id="union_council_id" name="union_council_id" class="form-control address-select-2">

                                        <option value="">Select Union Council</option>

                                        @forelse($union_councils as $union_council)

                                        <option value="{{ $union_council->id }}">

                                            {{ $union_council->name }}</option>



                                        @empty

                                        <option value="">No Union Council exists</option>

                                    @endforelse

                                    </select>



                                </div>

                            </div>

                            <div class="col-sm-4">

                            <button type="button "  onclick="applyFilter()" style="margin-top:30px" class="btn btn-primary m-t-2">Apply Filter



                        </button>

                            </div>

                        </div>



                        <hr>

				        <table id="users-datatable" class="table table-bordered table-striped" style="width:100%">

				          <thead>

				              <tr>

				                <th>Sr.No</th>

				                <th>Name</th>

                                <th>User Name</th>

				                <th>Phone Number</th>

				                <th>Email</th>

                                <th>Role</th>
                                
                                <th>Designation</th>

				                <th>Status</th>

				                <th>Status</th>

                                  <th>Member at</th>

				                <th>Actions</th>

				              </tr>

				          </thead>

				          <tbody>

				          </tbody>

				          <tfoot>

				              <tr>

				                <th>Sr.No</th>

				                <th>Name</th>

                                <th>User Name</th>

				                <th>Phone Number</th>

				                <th>Email</th>

                                <th>Role</th>

                                <th>Designation</th>

				                <th>Status</th>

				                <th>Status</th>

				                <th>Member at</th>

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

    <div class="modal fade" id="showUserModal" tabindex="-1" role="dialog" aria-labelledby="showPostModal" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">User Details</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body user_data">

                    <!-- dynamic body append here -->

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}

                </div>

            </div>

        </div>

    </div>

    <!-- Modal occupations user -->

    <div class="modal fade" id="showUserOccupationModal" tabindex="-1" role="dialog" aria-labelledby="showPostModal" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabelOccupation">User Profession Details</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body occupation_data">

                    <!-- dynamic body append here -->

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}

                </div>

            </div>

        </div>

    </div>



	<!-- Modal -->

    <div class="modal fade" id="subsUserModal" tabindex="-1" role="dialog" aria-labelledby="showPostModal" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">User Subscription Amount</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body ">

					<form id="subscription-form">

						<input type="hidden" name="user_id" id="subscription-user">

						<div class="form-group">

							<label for="exampleInputEmail1">Subscription amount</label>

							<input type="number" class="form-control" name="subscription_amount" id="subscription-amount" placeholder="Enter Subscription amount" required>

						</div>

						<div class="form-group">

							<label for="exampleInputPassword1">Subscription cycle Per month</label>

							<input type="number" class="form-control" name="subscription_amount_cycles" id="subscription-cycle" placeholder="Enter number of month(s) here" min="1" required>

						</div>

					</form>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                   <button type="button" class="btn btn-primary" onclick="saveSubscriptionAmount()">Save changes</button>

                </div>

            </div>

        </div>

    </div>

@endsection



@push('footer-scripts')

@include('admin.common-script.address-script')

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

	<script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>

	<!-- AdminLTE App -->

	<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

    <!-- Global Var -->

	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>

	<!-- AdminLTE for demo purposes -->

	<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>

	<!-- Page specific script -->

	{{-- sweet alert  --}}

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js">

	</script>

    <script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>

	<script>

        $(function () {
            $('#users-datatable').dataTable({
                order: [[1, 'asc']], // Default sorting by the second column (index starts from 0)
                pageLength: 50,
                scrollX: true,
                processing: false,
                language: { "processing": showOverlayLoader() },
                drawCallback: function () {
                    hideOverlayLoader();
                },
                responsive: true,
                lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/users') }}",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}",
                            d.status = $('#select_status').val();
                        d.occupation = $('#select_occupation').val();
                        d.child_occupation = $('#select_child_occupation').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.profile_status = $('#select_profile_status').val();
                        d.user_role = $('#select_role').val();
                        d.user_designation = $('#select_designation').val();
                        d.country_id = $('#country_id').val();
                        d.province_id = $('#province_select').val();
                        d.division_id = $('#division_select').val();
                        d.city_id = $('#city_select').val();
                        d.district_id = $('#district_select').val();
                        d.tehsil_id = $('#tehsil_select').val();
                        d.zone_div = $('#zone_select').val();
                        d.union_council_id = $('#union_council_select').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name_english', name: 'user_name_english', orderable: true },
                    { data: 'user_name', name: 'user_name', orderable: true },
                    { data: 'phone_number', name: 'phone_number', orderable: true },
                    { data: 'email', name: 'email', orderable: true },
                    { data: 'role.name_english', name: 'role.name_english', orderable: true },
                    {
                        data: 'designation.name_english',
                        name: 'designation.name_english',
                        defaultContent: 'N/A'
                    },
                    { data: 'status', name: 'status', orderable: true },
                    { data: 'statusColumn', name: 'statusColumn', visible: false },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: true,
                        render: function (data, type, row) {
                            var date = new Date(data);
                            var year = date.getFullYear();
                            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based
                            var day = ('0' + date.getDate()).slice(-2);
                            return year + '-' + month + '-' + day;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                dom: 'Blfrtip',
                buttons: dataCustomizetbaleAdmin("Users", arrywidth = ['10%', '20%', '20%', '15%', '15%', '10%', '10%'], arraycolumn = [0, 1, 2, 3, 4, 5, 6]),
            }).on('length.dt', function () {
            }).on('page.dt', function () {
            }).on('order.dt', function () {
            }).on('search.dt', function () {
            });
        });


        function applyFilter(_this)

        {

            let id =  $(_this).val();

            $('#select_child_occupation').find('option').not(':first').remove();

            $.ajax({

                type: "POST",

                url: "{{route('admin.user.filter-occupation')}}",

                data: {'_token': "{{csrf_token()}}", occupation_id: id},

                dataType: "json",

                success: function(result) {

                    if (result.status == 200) {

                        for (var i = 0; i < result.total; i++) {

                            var id = result['data'][i].id;

                            var name =result['data'][i].title_english;



                            var option = "<option value='" + id + "'>" + name + "</option>";

                            $("#select_child_occupation").append(option);

                        }

                    }

                }

            });



            var oTable = $('#users-datatable').DataTable();

            oTable.ajax.reload();

        }

        function applyOccupationFilter()

        {

            var oTable = $('#users-datatable').DataTable();

            oTable.ajax.reload();

        }

	  function showOverlayLoader()

	  {

	  }

	  function hideOverlayLoader()

	  {

	  }



	//___ approve/disapprove user  function___//

	function is_approved(_this,id){

		var URL = "{{ URL('admin/user-approve-feature') }}";

		var status = ($(_this).prop('checked') == true) ? 1:0;

		$.ajax({

			url: URL+'/'+id,

			data: {status:status},

			type: "get",

			success: function(response) {

			//___to reload datatabel___//

			var oTable = $('#users-datatable').DataTable();

			oTable.ajax.reload();

				swal("Done!", "Status Changed.", "success")

			},

			error: function(data) {

			}

		});

	}



	function subscriptionAmount(usrID)

	{

		$.ajax({

			url: '{{ ROUTE("admin.users.subscription-details") }}',

			data: {usrID:usrID},

			type: "get",

			success: function(response) {

				response = JSON.parse(response);

				$('#subscription-user').val( usrID );

				$('#subscription-amount').val( response.amount );

				$('#subscription-cycle').val( response.cycle );

				$('#subsUserModal').modal('show');

			},

			error: function(data) {

			}

		});

	}



	function saveSubscriptionAmount()

	{

		if($('#subscription-form').valid())

		{

			$.ajax({

				url: '{{ Route("admin.users.subscription-details-save") }}',

				data: {

					'userID':$('#subscription-user').val(),

					'subscription_amount':$('#subscription-amount').val(),

					'subscription_amount_cycles':$('#subscription-cycle').val(),

				},

				type: "get",

				success: function(response) {

					response = JSON.parse(response);

					if(response.status)

					{

						swal("Done!", response.message, "success")

					}

					else

					{

						swal("Oop!", response.message, "error")

					}

					$('#subsUserModal').modal('hide');

				},

				error: function(data) {

				}

			});

		}

	}



	</script>

    <script>

        $(document).on('click', '.show_user', function () {

            let userId = $(this).attr('data-user-id');



            $.ajax({

                type: "POST",

                url: "{{route('admin.user.show')}}",

                data: {

                    '_token': "{{csrf_token()}}",

                    'user_id': userId

                },

                success: function(result) {

                    if (result.status === 200) {

                        $('.user_data').html(result.html)

                    }

                }

            });

        });

    </script>

    <script>

        $(document).ready(function() {

                $('.js-example-basic-single').select2({

					placeholder: "Select Profession",

				});

        });

		function showUserOccupations(user_id){

			$("#showUserOccupationModal").modal('show');

			let userId = user_id;



            $.ajax({

                type: "GET",

                url: "{{route('admin.user.show-occupation')}}",

                data: {

                    '_token': "{{csrf_token()}}",

                    'user_id': userId

                },

				dataType: "json",

                success: function(result) {

					// var result =JSON.parse(result);

                    // if (result.status === 200) {

                        $('.occupation_data').html(result.html)

                    // }

                }

            });

		}



    </script>

@endpush

