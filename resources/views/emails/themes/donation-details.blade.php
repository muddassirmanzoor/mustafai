@extends('user.layouts.layout')

@section('content')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- tags input -->

<style>
    div#my-donation_length {
    padding-top: 15px;
    }
    div#my-donation_filter {
    margin-top: -33px;
    }
</style>
@endpush

<div class="userlists-tab">
    <div class="list-tab">
        <nav class="d-none">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <button class="nav-link donation-class active " id="nav-Donations-tab"   type="button"   data-val="my-donation" onclick="getDonationDetails('my-donation','nav-Donations-tab')">My Donations</button>
                <button class="nav-link donation-class  " id="nav-don-Cause-tab"   type="button"   data-val="donation-cause" onclick="getDonationDetails('donation-cause','nav-don-Cause-tab')">Donation Cause</button>
                <button class="nav-link donation-class  " id="nav-pend-Payments-tab"   type="button"  data-val="pending-payments" onclick="getDonationDetails('pending-payments','nav-pend-Payments-tab')">Pending Payments</button>
                <button class="nav-link donation-class  " id="nav-Advanc-Payments-tab"   type="button"  data-val="advance-payments" onclick="getDonationDetails('advance-payments','nav-Advanc-Payments-tab')">Advance Payments</button>
                <button class="nav-link donation-class  " id="nav-Reports-tab"   type="button" data-val="reports"  onclick="getDonationDetails('reports','nav-Reports-tab')">Reports</button>
            </div>
            </nav>
            <div class="table-form">
                <h5 class="form-title">{{__('app.donation-details')}}</h5>

                </form>
            </div>
            <div class="" id="dynamic-html">

            </div>
    </div>
</div>




@endsection
@push('scripts')
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
<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
 <script>
    // alert("ok");
    $(function() {
        var type = $('.donation-class.active').attr('data-val');
        var tabId = $('.donation-class.active').attr('id');
        getDonationDetails(type,tabId);
    });

    function getDonationDetails(type='',tabId='')
    {
        $('.donation-class').removeClass('active');
        $("#"+tabId).addClass('active');
        $.ajax({
            type: 'get',
            url: '{{ URL("user/donation-partial") }}',
            data: { type: type },
            success: function (data) {

                $("#dynamic-html").html(data);
                var delayInMilliseconds = 1000; //1 second

                setTimeout(function() {

                sort_data("my-donation",type);
                }, delayInMilliseconds);
            }
        });
    }

    function sort_data(table_id,type) {
        // alert(type);
        // $('#'+table_id).dataTable().fnClearTable();
        // $('#'+table_id).dataTable().fnDestroy();
        // if(type=="my-donation"){}

            var allow=''
            var have_permision = "<?php echo have_permission('Export-My-Donations'); ?>";
            if(have_permision){
                var allow=dataCustomizetbale("{{__('app.donation-details-import')}}",  arrywidth= [ '8%', '10%', '62%', '10%', '10%',],  arraycolumn = [0,1,2,3,5],"{{__('app.donation-details')}}");
                var dom='Blfrtip';
            }
            else{
                var dom='lfrtip'
            }
            $('#'+table_id).dataTable({

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
                sort: false,
                pageLength: 50,
                scrollX: true,
                processing: false,
                bDestroy: true,
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
                    url: "{{ url('user/donation-tables') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                            d.col_name = '';
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'donation_id',
                        name: 'donation_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
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
    function showOverlayLoader()
    {
    }
    function hideOverlayLoader()
    {
	}
 </script>
 @include('user.scripts.font-script')
@endpush
