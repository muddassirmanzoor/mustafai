@extends('user.layouts.layout')
@section('content')
    @push('styles')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <!-- tags input -->

        <style>
            div#donors-datatable_length {
            padding-top: 15px;
            }
            div#donors-datatable_filter {
            margin-top: -33px;
            }
      </style>
    @endpush
    <div class="userlists-tab">
        <div class="list-tab">
            <div class="table-form">
                <h5 class="form-title">{{__('app.list-of-donors')}}</h5>
                <div class="row align-items-center justify-content-between">
                    <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                        <div class="form-group select-wrap d-flex align-items-center">
                            <label class="sort-form-select me-2">{{__('app.search')}}</label>
                            <div class="select-group w-100">
                                <select class="form-select w-100" onchange="searchBlood()"  aria-label="Default select example" id="select_blood" name="select_blood">
                                    <option value="">{{__('app.select') . __('app.blood-group')}}</option>
                                    <option value="A+">A+</option>
                                    <option value="O+">O+</option>
                                    <option value="B+">B+</option>
                                    <option value="AB+">AB+</option>
                                    <option value="A-">A-</option>
                                    <option value="O-">O-</option>
                                    <option value="B-">B-</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 d-flex justify-content-end">
                        <button class="theme-btn-borderd-btn theme-btn text-inherit" data-toggle="modal"
                            data-target="#createPostModal">{{__('app.ask-for-blood')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="users-table mb-lg-5 mb-4" id="style-2">
            <table id="donors-datatable" class="table border-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">{{__('app.no')}}</th>
                        <th scope="col">{{__('app.donor-name')}}</th>
                        <th scope="col">{{__('app.blood-group')}}</th>
                        <th scope="col">{{ __('app.contact-name')}}</th>
                        <th scope="col">{{__('app.dob')}}</th>
                        <th scope="col">{{__('app.age')}}</th>
                        <th scope="col">{{__('app.city')}}</th>
                    </tr>
                </thead>
                <tbody id="tbody">



                </tbody>
            </table>
        </div>
    </div>

    </div>
    <!-- Create Job Post Modal -->
    <div class="modal fade library-detail common-model-style" id="createPostModal" tabindex="-1" role="dialog"
        aria-labelledby="createPostModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content create-post-modal">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{__('app.request-for-blood')}}</h4>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <form id="userPostForm" method="post" action="{{ route('user.blood.post') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">{{__('app.title')}}</label>
                            <textarea name="title" type="text" class="form-control post_title" id="exampleFormControlTextarea1"
                                placeholder="{{__('app.title')}}" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="city">{{__('app.city')}}</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="{{__('app.city')}}" required>
                        </div>
                        <div class="mb-3">
                            <label for="hospital">{{__('app.hospital')}}</label>
                            <input type="text" name="hospital" id="hospital" class="form-control" placeholder="{{__('app.hospital')}}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address">{{__('app.address')}}</label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="{{__('app.address')}}" required>
                        </div>
                        <div class="form-group">
                            <div class="qt_wrap mt">
                                <label class="form-label">{{__('app.please-upload')}}</label>
                                <div id="newRow" class="input_image_scroller"></div>
                                <button id="addRow" type="button" class="btn btn-primary">{{__('app.add-post-image')}}</button>
                                <small>({{__('app.optional')}})</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="green-hover-bg theme-btn" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="green-hover-bg theme-btn create_post">{{__('app.post')}}</button>
                    </div>
                </form>
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

    <script>
        $(function() {
            sort_data();
            search_blood_group();
        });

        function sort_data() {

            $('#donors-datatable').dataTable({

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
                buttons: [
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Donors"></i>&nbsp;{{ __("app.export-pdf") }}',
                            title: 'Donors',
                            orientation: 'landscape',
                            alignment: "center",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6],
                                alignment: "center",
                                orthogonal: "PDF",
                                modifier: {order: 'index', page: 'current'},
                            },
                            customize : function(doc) {
                                doc.content[1].table.widths = [ '8%', '15%', '15%', '15%', '15%', '15%','15%'];
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.content.splice( 1, 0, {
                                margin: [ 0, 0, 0, 12 ],
                                alignment: 'center',
                                image:'data:image/png;base64,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'
                                } );
                            }
                        }
                    ],
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,

                ajax: {
                    url: "{{ url('user/blood-bank') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                            d.col_name = $('#blood_group').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'blood_group',
                        name: 'blood_group'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },



                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt',
                function() {});

        }
        function search_blood_group() {

            $('#donors-datatable').dataTable({

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
                buttons: [
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Donors"></i>&nbsp;{{ __("app.export-pdf") }}',
                            title: 'Donors',
                            orientation: 'landscape',
                            alignment: "center",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6],
                                alignment: "center",
                                orthogonal: "PDF",
                                modifier: {order: 'index', page: 'current'},
                            },
                            customize : function(doc) {
                                doc.content[1].table.widths = [ '8%', '15%', '15%', '15%', '15%', '15%','15%'];
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.content.splice( 1, 0, {
                                margin: [ 0, 0, 0, 12 ],
                                alignment: 'center',
                                image:'data:image/png;base64,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'
                                } );
                            }
                        }
                    ],
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,

                ajax: {
                    url: "{{ url('user/blood-search') }}",
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
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'blood_group',
                        name: 'blood_group'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },



                ]
            }).on('length.dt', function() {}).on('page.dt', function() {}).on('order.dt', function() {}).on('search.dt',
                function() {});

        }

        function sortBlood() {
            $('#donors-datatable').dataTable().fnClearTable();
            $('#donors-datatable').dataTable().fnDestroy();
            sort_data()
        }
        function searchBlood() {

            $('#donors-datatable').dataTable().fnClearTable();
            $('#donors-datatable').dataTable().fnDestroy();
            search_blood_group()
        }



        function showOverlayLoader() {}

        function hideOverlayLoader() {}
        $("#addRow").click(function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="input-group" style="margin-bottom: 2%">';
            html += '<input id="files" type="file" name="files[]" class="form-control m-input post-input-file" accept="image/*">';
            html += '<div class="input-group-append d-flex">';
            html +=
                '<button id="removeRow" type="button" style="height:40px" class="btn btn-danger dynamic_files"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });

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
