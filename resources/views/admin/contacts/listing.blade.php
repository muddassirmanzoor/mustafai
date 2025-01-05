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
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Queries</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Queries</li>
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
                                {{-- @if (have_right('add-admin'))
					        <h3 class="card-title">
					        	<a href="{{ URL('admin/ceomessage/create') }}" class="btn btn-primary"> Add New </a>
					        </h3>
				        @endif --}}
                            </div>
                            <div class="card-body">
                                <table id="pages-datatable" class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Message</th>
                                            <th>Update Status</th>
                                            <th>Message</th>
                                            <th>status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Message</th>
                                            <th>Update Status</th>
                                            <th>Message</th>
                                            <th>status</th>
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
    <div class="modal fade" id="showNoteModal" tabindex="-1" role="dialog" aria-labelledby="showPostModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contact Query Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body note_data">
                    <!-- dynamic body append here -->
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    <button onclick="updateNote()" type="button" class="btn btn-primary note_close_button">
                        Add Note
                    </button>
                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script> --}}
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
    <!-- Global Var -->
    <script src="{{ asset('assets/admin/dist/js/binary-image.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>
    <!-- Page specific script -->
    <!--sweat alert -->
    {{-- <script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
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
                buttons: dataCustomizetbaleAdmin("Contacts", arrywidth = ['10%', '25%', '45%','20%'],arraycolumn = [0, 1, 4,5]),
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,
                ajax: "{{ url('admin/contacts') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: 'UpdateStatus',
                        name: 'UpdateStatus',
                    },
                    {
                        data: 'messageHiden',
                        name: 'messageHiden',
                        visible: false
                    },
                    {
                        data: 'status_hidden',
                        name: 'status_hidden',
                        visible: false,
                        searchable: true

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on(
                'search.dt',
                function() {});
        });

        function showOverlayLoader() {}

        function hideOverlayLoader() {}

        function changeStatuscontact(id, val) {
            $.ajax({
                url: "{{ URL('/admin/contacts-status') }}",
                data: {
                    status: val,
                    id: id
                },
                type: "get",
                success: function(response) {

                    //___to reload datatabel___//
                    var oTable = $('#pages-datatable').DataTable();
                    oTable.ajax.reload();
                    if (response == '1') {
                        swal("Done!", "Status Changed.", "success");
                    } else {
                        swal("Error!", "Status Is Not Updated", "error");
                    }
                },
                error: function(data) {}
            });
        }
    </script>
    <script>
        $(document).on('click', '.show_note', function () {
            let id = $(this).attr('data-contact-id');

            $.ajax({
                type: "get",
                url: "{{route('admin.contacts.show')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'id': id
                },
                success: function(result) {
                    if (result.status === 200) {
                        $('.note_data').html(result.html)
                    }
                }
            });
        });
    </script>
    <script>
        function updateNote()
        {
            var formData = new FormData($('#noteForm')[0]);
            $.ajax({
                type: "POST",
                url: "{{route('admin.contacts.show')}}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function (data) {
                    if (data.status == 'success') {
                        try {
                            swal.fire('Done', data.message, 'success')
                        }
                        catch (err) {
                            swal('Done', data.message, 'success')
                        }
                    }
                    $('.preloader').hide();
                    $('#showNoteModal .close').click();
                }
            });
        }
    </script>
@endpush
