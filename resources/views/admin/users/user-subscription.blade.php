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



    input:checked+.slider {

        background-color: #2196F3;

    }



    input:focus+.slider {

        box-shadow: 0 0 1px #2196F3;

    }



    input:checked+.slider:before {

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

                    <h1 class="m-0">User Subscription</h1>

                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">subscription</li>

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

                            {{-- <h3 class="card-title">

                                <a href="{{ URL('admin/users/create') }}" class="btn btn-primary"> Add New </a>

                            </h3> --}}

                            @endif

                            <br>

                        </div>

                        <div class="card-body">



                            <input type="hidden" value="{{ url('admin/users/'.$id.'/subscription') }}" id="url">

                            <table id="users-datatable" class="table table-bordered table-striped" style="width:100%">

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Amount</th>

                                        <th>Receipt Status</th>

                                        <th>Receipt Status</th>

                                        <th>Subscription Status</th>

                                        <th>Subscription Status</th>

                                        <th>Payment Status</th>

                                        <th>Payment Status</th>

                                        <th>Subscription Start Date</th>

                                        <th>Subscription Start Date</th>

                                        <th>Subscription End Date</th>

                                        <th>Subscription End Date</th>

                                        <th>Subscription Months</th>

                                        <th>Subscription Months</th>

                                        <th>Payment Method</th>

                                        <th>Payment Method</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                </tbody>

                                <tfoot>

                                    <tr>

                                        <th>ID</th>

                                        <th>Amount</th>

                                        <th>Receipt Status</th>

                                        <th>Receipt Status</th>

                                        <th>Subscription Status</th>

                                        <th>Subscription Status</th>

                                        <th>Payment Status</th>

                                        <th>Payment Status</th>

                                        <th>Subscription Start Date</th>

                                        <th>Subscription Start Date</th>

                                        <th>Subscription End Date</th>

                                        <th>Subscription End Date</th>

                                        <th>Subscription Months</th>

                                        <th>Subscription Months</th>

                                        <th>Payment Method</th>

                                        <th>Payment Method</th>

                                        <th>Action</th>

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

<div class="modal fade" id="showSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="showSubscriptionModal" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Subscription Details</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body subscription_data">

                <!-- dynamic body append here -->

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                {{--<button type="button" class="btn btn-primary">Save changes</button>--}}

            </div>

        </div>

    </div>

</div>
<div class="modal fade common-model-style" id="editSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.subscription-detail') }}</h4>

                <button type="button" class="btn-close close" id="subscription-close-btn" data-dismiss="modal"></button>

            </div>
            <form id="edit-subscription-form" enctype="multipart/form-data" method="post" action="{{ route('admin.update.receipt') }}">
                @csrf
                <input type="hidden" name="subscription_id" id="subscription_id">

                <div class="modal-body">

                <div class="col-lg-12 mb-xxl-3 mb-1">

                    <div class="form-group">

                        <label ><b>{{ __('app.upload-reciept') }} <span class="text-red">*</span></b></label>

                        <input type="file" name="reciept" id="reciept" class="form-control" accept="image/*" required>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="green-hover-bg theme-btn" onclick="updateReceipt($(this))">{{ __('app.pay-now') }}</button>

            </div>
            </form>

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

    $(function() {

        var url = $('#url').val();

        $('#users-datatable').dataTable({

            sort: false, pageLength: 50, scrollX: true, processing: false, language: {

                "processing": showOverlayLoader()

            },

            drawCallback: function() {

                hideOverlayLoader();

            },

            responsive: true,

            lengthMenu: [

                [5, 10, 25, 50, 100, 200, -1],[5, 10, 25, 50, 100, 200, "All"]

            ],

            serverSide: true,

            ajax: url,

            columns:

            [

                {  data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},

                {  data: 'amount', name: 'amount'},

                {  data: 'receipt_status', name: 'receipt_status'},

                {  data: 'receipt_statusHidden', name: 'receipt_statusHidden',visible:false},

                {  data: 'subscription_status', name: 'subscription_status' },

                {  data: 'subscription_statusHidden', name: 'subscription_statusHidden',visible:false },

                {  data: 'status', name: 'status', orderable: false, searchable: false},

                {  data: 'statusHidden', name: 'statusHidden', orderable: false, searchable: false,visible:false},

                {  data: 'subscription_start_date', name: 'subscription_start_date' },

                {  data: 'subscription_start_dateHidden', name: 'subscription_start_dateHidden',visible:false },

                {  data: 'subscription_end_date', name: 'subscription_end_date' },

                {  data: 'subscription_end_dateHidden', name: 'subscription_end_dateHidden',visible:false },

                {  data: 'subscription_moths', name: 'subscription_moths' },

                {  data: 'subscription_mothsHidden', name: 'subscription_mothsHidden',visible:false },

                {  data: 'subscription_method', name: 'subscription_method' },

                {  data: 'subscription_methodHidden', name: 'subscription_methodHidden',visible:false },


                {  data: 'action', name: 'action', orderable: false, searchable: false},

            ],

            dom: 'Blfrtip',

            buttons: dataCustomizetbaleAdmin("User Subscription", arrywidth = ['10%', '10%', '15%', '5%', '5%', '10%', '10%', '15%', '15%'], arraycolumn = [0, 1,3,5,7,9, 11 ]),

         }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt', function() {});

    });



    function showOverlayLoader() {}



    function hideOverlayLoader() {}



    function updateSubscriptionStatus(_this) {

        var id=$(_this).attr('data-id');

        var subs_status=$(_this).val();

        // alert($(_this).attr('data-id'));

        // alert($(_this).val());

        $.ajax({

                url: "{{ URL('/admin/subscription-status-update') }}",

                data: {

                    status: subs_status,

                    subscription_id: id

                },

                type: "get",

                success: function(response) {

                    var response = JSON.parse(response);

                    console.log(response.status);

                    //___to reload datatabel___//

                    var oTable = $('#users-datatable').DataTable();

                    oTable.ajax.reload();

                    if (response.status == 1) {

                        swal("Done!", response.message, "success");

                    } else {

                        swal("Error!", response.message, "error");

                    }

                },

                error: function(data) {}

            });

    }

</script>

<script>

    $(document).on('click', '.show_subscription', function () {

        let subscription_Id = $(this).attr('data-subscription-id');

        $.ajax({

            type: "POST",

            url: "{{route('admin.subscription.show')}}",

            data: {

                '_token': "{{csrf_token()}}",

                'subscription_Id': subscription_Id

            },

            success: function(result) {

                if (result.status === 200) {

                    $('.subscription_data').html(result.html)

                }

            }

        });

    });

    $(document).ready(function() {
        // When the modal is about to be shown
        $('#editSubscriptionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var subscriptionId = button.data('subscription-id'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#subscription_id').val(subscriptionId); // Set the subscription ID in the hidden input
        });
    });

    function updateReceipt() {
        $('#edit-subscription-form').submit();
    }
</script>

@endpush

