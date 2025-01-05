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
                    @if(have_permission('Search-By-Blood-Group'))
                        <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{__('app.search-by-blood-group')}}</label>
                                <div class="select-group w-100">
                                    <select class="form-select w-100" onchange="applyFilter()"  aria-label="Default select example" id="select_blood" name="select_blood">
                                        <option value="">{{ __('app.select-blood-group') }}</option>
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
                    @endif

                    @if(have_permission('Search-By-Cities'))
                            <div class="col-xl-4 col-sm-6 mb-sm-0 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{__('app.search-by-city')}}</label>
                                    <div class="select-group w-100">
                                        <select class="form-select w-100" onchange="applyFilter()"  aria-label="Default select example" id="city_filter" name="select_blood">
                                            <option value="">{{ __('app.select-city') }}</option>
                                            <option value="my">{{ __('app.my-city') }}</option>
                                            <option value="all">{{ __('app.all-city') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    @endif
                    @if(have_permission('Create-Blood-Bank-Post'))
                        <div class="col-xl-4 d-flex justify-content-end">
                            <button class="theme-btn-borderd-btn theme-btn text-inherit" data-toggle="modal"
                                data-target="#createPostModal">{{__('app.ask-for-blood')}}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="users-table mb-lg-5 mb-4" id="style-2">
            <table id="donors-datatable" class="table border-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">{{__('app.no')}}</th>
                        <th scope="col">{{__('app.donor-name')}}</th>
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
                        <div class="apply_for_whom d-flex justify-content-between mb-3">
                            <div>
                                <label for="">{{__('app.apply-as-your-self')}}</label>
                                <input type="radio" name="blood_for" value="0" checked>
                            </div>
                            <div>
                                <label for="">{{__('app.apply-for-someone-else')}}</label>
                                <input type="radio" value="1" name="blood_for">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">{{__('app.title')}}</label>
                            <textarea name="title" type="text" class="form-control post_title" id="exampleFormControlTextarea1"
                                placeholder="{{__('app.title')}}" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="city">{{__('app.city')}}</label>
                            <select name="city" id="" class="form-control" required>
                                <option value="">{{ __('app.select-city') }}</option>
                                @forelse($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @empty
                                    <option value="">{{ __('app.no-data') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select w-100"  aria-label="Default select example" id="select_blood" name="blood_group" required>
                                <option value="">{{ __('app.select-blood-group') }}</option>
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
                <input type="hidden" id="counter" value="1">
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
        $(function()
        {
            var allow=''
            var have_permision = "<?php echo have_permission('Export-Blood-Bank'); ?>";
            if(have_permision){
                var allow=dataCustomizetbale("{{__('app.blood-bank-import')}}",  arrywidth= [ '8%', '15%', '15%', '15%', '15%', '15%','15%'],  arraycolumn = [0,2,3,4,5,6,7],"{{__('app.blood-bank')}}");
                var dom='Blfrtip';
            }
            else{
                var dom='lfrtip'
            }
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
                dom: dom,
                buttons:allow,
                lengthMenu: [
                    [5, 10, 25, 50, 100, 200, -1],
                    [5, 10, 25, 50, 100, 200, "All"]
                ],
                serverSide: true,

                ajax: {
                    url: "{{ url('user/blood-bank') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                        d.group = $('#select_blood').val();
                        d.city = $('#city_filter').val();
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
                        data: 'full_nameHidden',
                        name: 'full_nameHidden',
                        visible: false
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
        });

        function applyFilter(){
            var oTable = $('#donors-datatable').DataTable();
			oTable.ajax.reload();
        }

        function showOverlayLoader() {}

        function hideOverlayLoader() {}

        $("#addRow").click(function() {
            var counter=$("#counter").val();
            var html = '';
            html += '<div id="inputFormRow_'+counter+'">';
            html += '<div class="input-group" style="margin-bottom: 2%">';
            html += '<input id="files_'+counter+'" onchange="loadFile($(this),event)" type="file" name="files[]" class="form-control m-input post-input-file" accept="image/*">';
            html += '<div class="input-group-append d-flex">';
            html +=`<div class="bar" style="margin-left:20px">
                        <img  id="preview_image_${counter}" style="width: 75px; height: 75px;border:2px solid black" class="imageThumb d-none" src="" title=""/>
                    `;
            html +=  '<button onclick="removeRow('+counter+')" id="removeRow_'+counter+'" type="button" style="height:40px" class="btn btn-danger dynamic_files"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
            $("#counter").val(parseInt(counter)+parseInt(1));
        });

        // remove row
        // $(document).on('click', '#removeRow', function() {
        //     $(this).closest('#inputFormRow').remove();
        // });
        function removeRow(id){
            $("#inputFormRow_"+id).remove();

        }

        // $(document).on("change", '.post-input-file', function(e) {
        //     var files = e.target.files,
        //         filesLength = files.length;
        //     for (var i = 0; i < filesLength; i++) {
        //         var f = files[i]
        //         var fileReader = new FileReader();
        //         fileReader.onload = (function(e) {
        //             var file = e.target;
        //             $(`<div class="bar" style="margin-left:20px">
        //                 <div><img style="width: 75px; height: 75px;border:2px solid black" class="imageThumb" src="${e.target.result}" title="${f.name}"/></div>
        //                 <br/>
        //                 `).insertAfter($('.dynamic_files:last'));

        //         });
        //         fileReader.readAsDataURL(f);
        //     }
        // });
        var loadFile = function(_this, event) {
            var file = $(_this).prop('files')[0];

            if (file.type.match(/image.*/)) {
                var index = _this.attr('id').split("_")[1];
                var output = document.getElementById('preview_image_' + index);
                output.classList.remove("d-none");
                output.src = URL.createObjectURL(file);
                output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
                };
            } else {
                _this.val('');
                swal(AlertMessage.invalid_file_type, AlertMessage.invalid_file, "error")


                // alert("Invalid file type. Please select an image file.");
            }
        };

    </script>
    @include('user.scripts.font-script')
@endpush
