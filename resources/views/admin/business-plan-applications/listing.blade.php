@extends('admin.layout.app')
@push('header-scripts')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                        <h1 class="m-0">Business Applications</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/busines_plans') }}">Business Plan</a>
                            </li>
                            <li class="breadcrumb-item active">Business Applications</li>
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
                                <h3 class="card-title">
                                    <a href="{{ URL('admin/business-plans/applications?type=unapproved&plan_id='.$plan_id.'') }}"
                                       class="btn {{ request()->get('type')=='unapproved'? 'btn-primary': 'btn-secondary' }}">
                                        Pending Applications </a>
                                </h3>

                                <h3 class="card-title ml-2">
                                    <a href="{{ URL('admin/business-plans/applications?type=approved&plan_id='.$plan_id.'') }}"
                                       class="btn {{ request()->get('type')=='approved'? 'btn-primary': 'btn-secondary' }}">
                                        Approved Applications </a>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="busines-plan-datatable" class="table table-bordered table-striped"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Plan Name</th>
                                        <th>User Name</th>
                                        <th>Defaulter Dates</th>
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
                                        <th>Plan Name</th>
                                        <th>User Name</th>
                                        <th>Defaulter Dates</th>
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

    <!-- Modal -->
    <div class="modal fade" id="showBusinessPlanModal" tabindex="-1" role="dialog"
         aria-labelledby="showBusinessPlanModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Business Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body business_data">
                    <!-- dynamic body append here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showApplicationModal" tabindex="-1" role="dialog" aria-labelledby="showApplicationModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body application_data">
                    <!-- dynamic body append here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>

    <!-- Relief date Modal -->
    <div class="modal fade" id="showReliefDateModal" tabindex="-1" role="dialog" aria-labelledby="showReliefDateModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relief Dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body dates_data">
                    <!-- dynamic body append here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>

    <!-- Defaulter Dates Modal -->
    <div class="modal fade profile-modlal library-detail common-model-style" id="defaulterDatesModal" tabindex="-1" role="dialog" aria-labelledby="defaulterDatesModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-green" id="exampleModalLabel"><b>Defaulter Dates</b></h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <input type="hidden" value="" class="to_send_email_id">
                <input type="hidden" value="" class="to_send_plan">
                <div class="defaulter-dates-body">

                </div>
                <div class="modal-footer">
                    <button style="margin-left: 150px" onclick="sendReminderEmail()" class="btn btn-primary">Send Reminder Email</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('footer-scripts')
    <!-- jQuery -->
    <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/admin/jquery-validation/jquery.validate.min.js') }}"></script>
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
    <!-- select2 -->
    <script src="{{asset('assets/admin/select2/js/select2.full.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <script>
        $(function () {
            $('#busines-plan-datatable').dataTable(
                {
                    sort: false,
                    pageLength: 50,
                    scrollX: true,
                    processing: false,
                    language: {"processing": showOverlayLoader()},
                    drawCallback: function () {
                        hideOverlayLoader();
                    },
                    responsive: true,
                    dom: 'Blfrtip',
                    buttons: dataCustomizetbaleAdmin("Business Applications",  arrywidth= ['16%', '22%', '40%', '22%'],  arraycolumn = [0, 1, 2, 5]),
                    lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                    serverSide: true,
                    ajax: "{!! url('admin/business-plans/applications?type='.$_GET['type'].'&plan_id='.$_GET['plan_id']) !!}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'plan', name: 'plan'},
                        {data: 'user', name: 'user'},
                        {data: 'defaulter_dates', name: 'defaulter_dates'},
                        {data: 'status', name: 'status', orderable: false, searchable: false},
                        {data: 'statusHidden',name: 'statusHidden',visible: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                }).on('length.dt', function () {
            }).on('page.dt', function () {
            }).on('order.dt', function () {
            }).on('search.dt', function () {
            });
        });

        function showOverlayLoader() {
        }

        function hideOverlayLoader() {
        }
    </script>

    <script>
        function showApplicantInformation(_this) {
            let applicationId = $(_this).attr('data-application-id');

            $.ajax({
                type: "POST",
                url: "{{route('admin.business.application.show')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'application_id': applicationId
                },
                success: function (result) {
                    if (result.status === 200) {
                        $('.application_data').html(result.html)
                    }
                }
            });
        }

        function showReliefDates(_this) {
            let applicationId = $(_this).attr('data-application-id');
            $.ajax({
                type: "POST",
                url: "{{route('admin.business.application.relief-dates')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'application_id': applicationId
                },
                success: function (result) {
                    if (result.status === 200) {
                        $('.dates_data').html(result.html)
                    }
                }
            });
        }

        function saveRelief(action = '', id = '') {
            if ($('#relief-form').valid()) {
                swal({
                        title: id == '' ? 'Do you want to add relief date?' : 'Do you want to update relief date?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (!isConfirm) return;

                        $.ajax({
                            type: "POST",
                            url: "{{route('admin.business.application.relief-dates-add')}}",
                            data: {
                                '_token': "{{csrf_token()}}",
                                'application_id': $('#rapplication_id').val(),
                                'start_date': $('#rstart_date').val(),
                                'end_date': $('#rend_date').val(),
                                'id': id
                            },
                            success: function (result) {
                                result = JSON.parse(result);
                                swal("Done!", result.message, "success");
                                $('#showReliefDateModal').modal('hide');
                            }
                        });

                    });
            }
        }

        var toSendDates = '';

        function showDefaulterDates(_this) {
            toSendDates = $(_this).attr('data-dates')
            let dates = $(_this).attr('data-dates').split(',')
            let userId = $(_this).attr('data-user-id')
            let planName = $(_this).attr('data-plan-name')

            $('.to_send_email_id').val(userId)
            $('.to_send_plan').val(planName)
            $('.defaulter-dates-body').html('')
            for(let i=0; i<dates.length; i++) {
                $('.defaulter-dates-body').append(`
                    <span class="mb-2">${dates[i]}</span>
                `)
            }
        }

        function sendReminderEmail(_this) {
            let userId = $('.to_send_email_id').val()
            let planName = $('.to_send_plan').val()
            $.ajax({
                type: "POST",
                url: "{{ route('invoice.reminder') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'user_id': userId,
                    'reminder_dates': toSendDates,
                    'plan_name': planName
                },
                success: function(result) {
                    $('#defaulterDatesModal').modal('toggle');
                    if(result.status == 1) {
                        // alert(result.message)
                        swal("Done!", result.message, "success");
                    }
                }
            });
        }


    </script>

@endpush
