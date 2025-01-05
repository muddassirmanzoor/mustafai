@extends('admin.layout.app')

@push('header-scripts')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">

    <!-- for sweet alert  -->
    {{-- <link rel="stylesheet" href="{{asset('assets/admin/sweetalert/sweetalert.css')}}"> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">

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
                        <h1 class="m-0">Donations</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Donation</li>
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
                                @if (have_right('Create-Donations'))
                                    <h3 class="card-title">
                                        <a href="{{ URL('admin/donations/create') }}" class="btn btn-primary"> Add New </a>
                                    </h3>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
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
                                <table id="pages-datatable" class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Status</th>
                                            <th>Featured</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Status</th>
                                            <th>Featured</th>
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
    <script src="{{ asset('assets/admin/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/admin/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/admin/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/admin/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
    <!-- Global Var -->
	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>
    <!--sweat alert -->
    {{-- <script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            $('#pages-datatable').dataTable({
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
            buttons: dataCustomizetbaleAdmin("Donation",  arrywidth= [ '5%', '75%', '20%',],  arraycolumn = [0,1,3]),
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/donations') }}",
                    data: function(d) {
                    d._token = "{{ csrf_token() }}",
                    d.status = $('#select_status').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title_english',
                        name: 'title_english'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {data: 'statusColumn',name: 'statusColumn',visible: false},
                    {
                        data: 'featured',
                        name: 'featured'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "drawCallback": function() {
                    $(".white-space").parent('td').css('white-space','nowrap');
                },

            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on(
                'search.dt',
                function() {

                });
        });
        function applyFilter()
        {
            var oTable = $('#pages-datatable').DataTable();
            oTable.ajax.reload();
        }

        function showOverlayLoader() {

        }

        function hideOverlayLoader() {

        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        //___ add/remove feature donation function___//
        function is_featured(_this, id) {
            var URL = "{{ URL('admin/featured-donation') }}";
            var status = ($(_this).prop('checked') == true) ? 1 : 0;
            $.ajax({
                url: URL + '/' + id,
                data: {
                    status: status
                },
                type: "get",
                success: function(response) {
                    //___to reload datatabel___//
                    var oTable = $('#pages-datatable').DataTable();
                    oTable.ajax.reload();
                    swal("Done!", "Status Changed.", "success")
                },
                error: function(data) {}
            });
        }
    </script>
@endpush
