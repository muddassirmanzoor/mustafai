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
      </style>
    @endpush
    <div class="userlists-tab">
        <div class="list-tab">
            <div class="table-form">
                <h5 class="form-title">{{__('app.list-of-orders')}}</h5>
                <div class="row align-items-center justify-content-between">



                </div>
            </div>
        </div>
        <div class="users-table mb-lg-5 mb-4" id="style-2">
            <table id="order-table" class="table border-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">{{__('app.no')}}</th>
                        <th scope="col">{{__('app.user-name')}}</th>
                        <th scope="col">{{ __('app.time') }}</th>
                        <th scope="col">{{ __('app.date') }}</th>
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

    </div>
    <!-- Create Job Post Modal -->
    <div class="modal fade common-model-style" id="order-detail-div" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.order-details') }}</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" onclick=" $('#order-detail-div').modal('hide');"></button>
                </div>
                <div class="modal-body" id="order-details-modal">

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="green-hover-bg theme-btn" data-dismiss="modal">Close</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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


        function sort_data() {
            var allow=''
            var have_permision = "<?php echo have_permission('Export-My-Orders'); ?>";
            if(have_permision){
                var allow=dataCustomizetbale("{{__('app.list-of-orders-import')}}",  arrywidth= [ '20%', '20%', '20%','20%','20%'],  arraycolumn = [0,1,2,3,5],"{{__('app.list-of-orders')}}");
                var dom='Blfrtip';
            }
            else{
                var dom='lfrtip'
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
                    url: "{{ url('user/my-orders') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                            d.col_name = $('#select_blood').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'statusHidden',
                        name: 'statusHidden',
                        visible:false
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },




                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt',
                function() {});

        }




        function showOverlayLoader() {}

        function hideOverlayLoader() {}

        function showOrderDetails(order_id){
            // alert(order_id);
		$.ajax({
			type: 'get',
			url: "{{url('user/order-details')}}",
			data: { 'order_id' : order_id },
			dataType: 'JSON',
			success: function (data) {
				console.log(data);
				$("#order-details-modal").html(data.html);
                $('#order-detail-div').modal('show');

			}
		});
	  }
    </script>
    @include('user.scripts.font-script')
@endpush
