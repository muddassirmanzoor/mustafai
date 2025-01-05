@extends('admin.layout.app')
@push('header-scripts')
<!--GoogleFont:SourceSansPro-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!--FontAwesome-->
<link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
<!--DataTables-->
<link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!--Themestyle-->
<link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
<style>
    div#categories-datatable_length {
        padding-top: 24px;
    }

    div#categories-datatable_filter {
        margin-top: -41px;
    }
</style>
@endpush

@section('content')
<!--ContentWrapper.Containspagecontent-->
<div class="content-wrapper">
    <!--ContentHeader(Pageheader)-->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Designations</h1>
                </div><!--/.col-->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Designations</li>
                    </ol>
                </div><!--/.col-->
            </div><!--/.row-->
        </div><!--/.container-fluid-->
    </div><!--/.content-header-->

    <!--Maincontent-->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(have_right('Add-Admin-Designations-Management'))
                            <h3 class="card-title">
                                <a href="{{URL('admin/designations/create?type=1')}}" class="btn btn-primary">Add New Admin Designation</a>
                            </h3>
                            @endif
                            @if(have_right('Add-User-Designations-Management'))
                            <h3 class="card-title" style="margin-left:05px;">
                                <a href="{{URL('admin/designations/create?type=2')}}" class="btn btn-primary">Add New User Designation</a>
                            </h3>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select lable-select me-2">Type:</label>
                                    <div class="select-group w-100">
                                        <select class="form-control" aria-label="Default select example" id="type" name="type">
                                            <option value="">Select Type</option>
                                            <option value="1">Admin</option>
                                            <option value="2">User</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="categories-datatable" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th >Order</th>
                                        <th >Subscription Charges</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Order</th>
                                        <th>Subscription Charges</th>
                                        <th>Status</th>
                                        <th>Type</th>
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
    <!--Maincontent-->
</div>
@endsection

@push('footer-scripts')
<!--jQuery-->
<script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
<!--Bootstrap4-->
<script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!--DataTables&Plugins-->
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
<!--AdminLTEApp-->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
<!--GlobalVar-->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
<!--AdminLTEfordemopurposes-->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<!--Pagespecificscript-->
<script>
    var tablaTransacciones = $('#categories-datatable');
    tablaTransacciones_dt = null;
    
    $(function () {
        var tablaTransacciones_dt = $('#categories-datatable').dataTable({
            sort: false,
            pageLength: 50,
            scrollX: true,
            processing: false,
            language: {
                "processing": showOverlayLoader()
            },
            drawCallback: function () {
                $(".order-set-button").parent().addClass("set-orders");
                $(".charges-set-button").parent().addClass("set-charges");
                hideOverlayLoader();
            },
            responsive: true,
            dom: 'Blfrtip',
            buttons: dataCustomizetbaleAdmin("Designations", arrywidth = ['25%', '25%', '25%', '25%'], arraycolumn = [0, 1, 5, 6]),
            lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            serverSide: true,
            ajax: {
                url: "{{url('admin/designations')}}",
                data: function (d) {
                    d.type = $('#type').val();
                    d.search = $('input[type="search"]').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name_english', name: 'name_english', searchable: true },
                { data: 'type', name: 'type' },
                { data: 'order_rows', name: 'order_rows' },
                { data: 'subscription_charges', name: 'subscription_charges' },
                { data: 'status', name: 'status' },
                { data: 'typeColumn', name: 'typeColumn', visible: false },
                { data: 'statusColumn', name: 'statusColumn', visible: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        }).on('length.dt', function () {}).on('page.dt', function () {}).on('order.dt', function () {}).on('search.dt', function () {});
        
        $('#type').change(function () {
            var oTable = $('#categories-datatable').DataTable();
            oTable.ajax.reload();
        });

        $('input[type="search"]').change(function () {
            var oTable = $('#categories-datatable').DataTable();
            oTable.ajax.reload();
        });
    });

    function showOverlayLoader() {}

    function hideOverlayLoader() {}

    function isPositiveInteger(str) {
        if (typeof str !== 'string') {
            return false;
        }
        const num = Number(str);
        if (Number.isInteger(num) && num > 0) {
            return true;
        }
        return false;
    }

    $(document).on('keyup', '.role_input', function (_this) {
        if (!isPositiveInteger($(this).val())) {
            $(this).val('');
            return 0;
        }
        let designationId = $(this).attr('data-designation-id');
        let order = $(this).val();
        if (_this.which == 13) {
            callback(designationId, order);
        }
    });

    $(document).on("click", ".order-set-button", function () {
        let designationId = $(this).attr('data-designation-id');
        let order = $("#order_set_" + designationId).val();
        callbackOrder(designationId, order);
    });

    $(document).on("click", ".charges-set-button", function () {
        let designationId = $(this).attr('data-designation-id');
        let subscription_charges = $("#subscription_charge_" + designationId).val();
        callbackCharges(designationId, subscription_charges);
    });

    var callbackOrder = function (designationId, order) {
        $.ajax({
            type: "POST",
            url: "{{route('designation.order')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'id': designationId,
                'order': order
            },
            success: function (result) {
                if (result.status === 1) {
                    swal("Success!", "Order Updated.", "success");
                }
            }
        });
    };

    var callbackCharges = function (designationId, subscription_charges) {
        $.ajax({
            type: "POST",
            url: "{{route('designation.subscription-charges')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'id': designationId,
                'subscription_charges': subscription_charges
            },
            success: function (result) {
                if (result.status === 1) {
                    swal("Success!", "Subscription Charges Updated.", "success");
                }
            }
        });
    };
</script>
@endpush
