@extends('user.layouts.layout')
@section('content')
    @push('styles')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <!-- tags input -->

        <style>
            div#subscription-datatable_length {
            padding-top: 15px;
            }
            div#subscription-datatable_filter {
            margin-top: -33px;
            }
      </style>
    @endpush
    <div class="userlists-tab">

        <div class="list-tab">
            <div class="table-form">
                <h5 class="form-title">My Subscriptions</h5>
            </div>
        </div>

        <div class="users-table mb-lg-5 mb-4" id="style-2">
            <table id="subscription-datatable" class="table border-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">No#</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Subscription Start Date</th>
                        <th scope="col">Subscription End Date</th>
                        <th scope="col">Reciept</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
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
        $(function()
        {
            $('#subscription-datatable').dataTable({
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
                dom: 'Blfrtip',
                buttons:dataCustomizetbale("{{__('app.blood-bank-import')}}",  arrywidth= [ '8%', '15%', '15%', '15%', '15%', '15%','15%'],  arraycolumn = [0,2,3,4,5,6,7],"{{__('app.blood-bank')}}"),
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,

                ajax: {
                    url: "{{ route('user.subscriptions') }}",
                    data: function(d) {
                        // d._token = "{{ csrf_token() }}",
                        // d.group = $('#select_blood').val();
                        // d.city = $('#city_filter').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                    },
                    {
                        data: 'reciept',
                        name: 'reciept'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
                }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt',
                function() {});
        });

        function applyFilter(){
            var oTable = $('#subscription-datatable').DataTable();
			oTable.ajax.reload();
        }

        function showOverlayLoader() {}

        function hideOverlayLoader() {}

        $(document).on("change", '.post-input-file', function(e) {
            var files = e.target.files,
                filesLength = files.length;
            for (var i = 0; i < filesLength; i++) {
                var f = files[i]
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                    var file = e.target;
                    $(`<div class="bar" style="margin-left:20px">
                        <div><img style="width: 75px; height: 75px;border:2px solid black" class="imageThumb" src="${e.target.result}" title="${f.name}"/></div>
                        <br/>
                        `).insertAfter($('.dynamic_files:last'));

                });
                fileReader.readAsDataURL(f);
            }
        });
    </script>
@endpush
