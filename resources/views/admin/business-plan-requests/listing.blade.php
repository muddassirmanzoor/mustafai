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

        <!-- for sweet alert  -->
 {{-- <link rel="stylesheet" href="{{asset('assets/admin/sweetalert/sweetalert.css')}}"> --}}
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
                        <h1 class="m-0">Business Applications Requests</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/business-plans/applications?type=approved&plan_id='.$plan_id.'') }}">Business Plan Applications</a></li>
                            <li class="breadcrumb-item active">Business Applications Requests</li>
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
                                        <th>Plan Name</th>
                                        <th>User Name</th>
                                        <th>Assigned To</th>
                                        <th>Existing Date</th>
                                        <th>Requested Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Plan Name</th>
                                        <th>User Name</th>
                                        <th>Assigned To</th>
                                        <th>Existing Date</th>
                                        <th>Requested Date</th>
                                        <th>Status</th>
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

    		<!--sweat alert -->
	{{-- <script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js">
	</script>
    <script>
        $(function () {
            $('#busines-plan-datatable').dataTable(
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
                    buttons:
                    [
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Business Applications"></i>&nbsp;Export as PDF',
                            title: 'Business Applications Requests',
                            orientation: 'landscape',
                            alignment: "center",
                            exportOptions: {
                                columns: [0,1,2,3,4,5],
                                alignment: "center",
                                orthogonal: "PDF",
                                modifier: {order: 'index', page: 'current'},
                            },
                            customize : function(doc) {
                                doc.content[1].table.widths = [ '11%', '17%', '17%','17%','17%','17%'];
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.content.splice( 1, 0, {
                                margin: [  0, -70, 0, 20 ],
                                alignment: 'right',
                                image:GlobalVar.image_logo,
                                } );
                                doc.styles.title = {
                                // color: 'red',
                                fontSize: '35',
                                // margin: [ 120, 0, 0, -120],
                                // background: 'blue',
                                alignment: 'left'
                                }
                            }
                        }
                    ],
                    lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                    serverSide: true,
                    ajax: "{!! url('admin/business-plans/requests?application_id='.$_GET['application_id'].'&user_id='.$_GET['user_id']) !!}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'plan_name', name: 'plan_name'},
                        {data: 'user_name', name: 'user_name'},
                        {data: 'assigned_to', name: 'assigned_to'},
                        {data: 'existing_date', name: 'existing_date', orderable: false, searchable: false},
                        {data: 'requested_date', name: 'requested_date', orderable: false, searchable: false},
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
        function bussinessRequestchange(id,change_number,application_id=''){
            // if (confirm("Do You Want Update Record!")) {
                swal({
                title: change_number==1 ? 'Do you really want to approve the request?' : 'Do you really want to reject the request?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ok",
                closeOnConfirm: false
                },
                function (isConfirm) {
                if (!isConfirm) return;
                var URLStatus = "{{ URL('admin/bussiness-request-change') }}";
                var status = change_number;
                $.ajax({
                    url: URLStatus,
                    data: {'change_number':change_number,'id':id,'application_id':application_id},
                    type: "get",
                    beforeSend: function () {
                        // $(".button").button("disable");
                        $(".preloader").addClass('adminloader');
                        $('.animation__shake').show();
                    },
                    success: function(response) {
                    //___to reload datatabel___//
                    var oTable = $('#busines-plan-datatable').DataTable();
                    oTable.ajax.reload();
                    if(response == '1'){
                        swal("Done!", "Update Status.", "success");
                    }else{
                        swal("Error!", "Status is not updated due to some resone", "error");
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
