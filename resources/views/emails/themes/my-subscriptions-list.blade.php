@extends('user.layouts.layout')
@section('content')
    @push('styles')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/assets/admin/css/custom.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
        <!-- tags input -->

        <style>
            div#order-table_length {
            padding-top: 15px;
            }
            div#order-table_filter {
            margin-top: -33px;
            }
            #subscription-close-btn{
                padding: 17px !important;
            }
        </style>
    @endpush
    <div class="userlists-tab">
        <div class="list-tab">
            <div class="table-form">
                <h5 class="form-title">{{ __('app.list-of-subscription') }}</h5>
                <div class="row align-items-center justify-content-between">
                </div>
            </div>
        </div>
        <div class="users-table mb-lg-5 mb-4" id="style-2">
            <table id="order-table" class="table border-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">{{__('app.no')}}</th>
                        <th scope="col">{{__('app.amount')}}</th>
                        <th scope="col">{{__('app.start-date')}}</th>
                        <th scope="col">{{__('app.end-date')}}</th>
                        <th scope="col">{{__('app.amount-reciept')}}</th>
                        <th scope="col">{{__('app.status')}}</th>
                        <th scope="col">{{__('app.status')}}</th>
                        <th scope="col">{{ __('app.action')}}</th>
                    </tr>
                </thead>
                <tbody id="">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Job Post Modal -->
    <div class="modal fade common-model-style" id="pay-subscription-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.subscription-detail') }}</h4>
                    <button type="button" class="btn-close close" id="subscription-close-btn" data-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="pay-subscription-modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="green-hover-bg theme-btn" onclick="payNow($(this))">{{ __('app.pay-now') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- tags input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
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
    <!-- Global Var -->
	<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>

    <script>
        $(function() {
            sort_data();
        });

        function sort_data()
        {
            var allow=''
            var have_permision = "<?php echo have_permission('Export-My-Subscription'); ?>";
            if(have_permision){
                var allow=dataCustomizetbale("{{ __('app.list-of-subscription-import') }}",  arrywidth= [ '25%', '25%', '25%','25%'],  arraycolumn = [0,1,2,3],"{{ __('app.list-of-subscription') }}");
                var dom='Blfrtip';
            }
            else{
                var dom='lfrtip';
            }
            $('#order-table').dataTable({

                "language": {
                    "oPaginate": {
                        "sFirst":    `{{__('app.first')}}`,
                        "sLast":    `{{__('app.last')}}`,
                        "sNext":    `{{__('app.next')}}`,
                        "sPrevious": `{{__('app.previous')}}`,
                    },
                    "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                    "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                    "sSearch":  `{{__('app.search')}}`,
                    "sZeroRecords":   `{{__('app.no-data-available')}}`,
                    "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

                },
                sort: true,
                pageLength: 50,
                scrollX: true,
                processing: false,
                bDestroy: true,
                // language: {
                //     "processing": showOverlayLoader()
                // },
                drawCallback: function() {
                    hideOverlayLoader();
                },
                responsive: true,
                dom: dom,
                buttons:allow,
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,

                ajax: {
                    url: "{{ url('user/my-subscriptions') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                            d.col_name = $('#select_blood').val();
                    }
                },

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'amount',  name: 'amount' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'reciept', name: 'reciept' },
                    { data: 'is_paid', name: 'is_paid' },
                    { data: 'statusHidden', name: 'statusHidden', visible:false },
                    { data: 'action', name: 'action' },
                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt',
                function() {});
        }

        function showOverlayLoader() {}

        function hideOverlayLoader() {}

        function paySubscriptionFee(id)
        {
            $('#pay-subscription-modal').modal('show');

            $.ajax({
                type: 'get',
                url: `/user/pay-subscription/${id}`,
                dataType: 'JSON',
                success: function (data)
                {
                    $("#pay-subscription-modal-body").html(data.html);
                    $('#pay-subscription-modal').modal('show');
                }
            });
        }

        function payNow() {
           if($('#pay-subscription-form').validate()) 
           {
            $('#pay-subscription-form').submit()
           }
            // if(!$("input[name='mustafai_account_id']").is(':checked') || $('#account_title').val() === '' || $('#account_number').val() === '' ||  $('#bank_name').val() === '' || $('#reciept')[0].files.length === 0) {
            //     return Swal.fire( '{{ __('app.fill-all-required') }}' , '', 'error');
            // }
            
        }
    </script>
    <script>
        $(document).on("click","#subscription-close-btn",function() {
        $("#pay-subscription-modal").modal('hide');
        });
    </script>
    @include('user.scripts.font-script')
@endpush
