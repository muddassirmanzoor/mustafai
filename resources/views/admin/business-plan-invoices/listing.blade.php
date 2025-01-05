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
    {{-- toggle bootstrap --}}
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
                            <li class="breadcrumb-item"><a href="{{ url('admin/business-plans/applications?type=approved&plan_id='.$plan_id.'') }}">Business Applications</a></li>
                            <li class="breadcrumb-item active">Invoices</li>
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
                                <table id="busines-plan-datatable" class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Date</th>
                                        <th>Invoice</th>
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
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
    <!-- Global Var -->
	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
    <!-- Page specific script -->
    <!-- select2 -->
    <script src="{{asset('assets/admin/select2/js/select2.full.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <script>
        $(function () {

            $('#busines-plan-datatable').dataTable(
                {
                    sort: false,
                    pageLength: 10,
                    scrollX: true,
                    processing: false,
                    language: { "processing": showOverlayLoader()},
                    drawCallback : function( ) {
                        hideOverlayLoader();
                    },
                    responsive: true,
                    dom: 'Blfrtip',
                    buttons: dataCustomizetbaleAdmin("Business Plan Invoices",  arrywidth= [ '10%', '80%', '10%'],  arraycolumn = [0,1,2,]),
                    lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                    serverSide: true,
                    ajax: "{{ url('admin/business-plans/invoices?application_id='.$_GET['application_id']) }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'user', name: 'user'},
                        {data: 'date', name: 'date'},
                        {data: 'invoice', name: 'invoice'},
                        {data: 'status', name: 'status', orderable: false, searchable: false},
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
    </script>

    <script>
        function showApplicantInformation(_this)
        {
                let applicationId = $(_this).attr('data-application-id');

                $.ajax({
                    type: "POST",
                    url: "{{route('admin.business.application.show')}}",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'application_id': applicationId
                    },
                    success: function(result) {
                        if (result.status === 200) {
                            $('.application_data').html(result.html)
                        }
                    }
                });
        }

        function changeStatus(_this,id){
            var URL = "{{ URL('admin/business_application/invoices/status') }}";
            var status = ($(_this).prop('checked') == true) ? 1:0;
            $.ajax({
                url: URL+'/'+id,
                data: {status:status},
                type: "get",
                success: function(response) {
                    //___to reload datatabel___//
                    var oTable = $('#busines-plan-datatable').DataTable();
                    oTable.ajax.reload();
                    swal("Done!", "Status Changed.", "success")
                },
                error: function(data) {
                }
            });
        }

    </script>

@endpush
