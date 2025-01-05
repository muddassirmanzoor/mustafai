

@extends('admin.layout.app')
@push('header-scripts')
   <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
   <!-- SummerNote -->
   <link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">

   <link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">

   {{-- file manager template css  --}}
   <link rel="stylesheet" href="{{asset('assets/admin/css/file-manager.css')}}">

   {{-- sweat alert  --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">

   {{-- template library  --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css" integrity="sha512-0/rEDduZGrqo4riUlwqyuHDQzp2D1ZCgH/gFIfjMIL5az8so6ZiXyhf1Rg8i6xsjv+z/Ubc4tt1thLigEcu6Ug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  {{-- dropify css  --}}

  <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .dropify-wrapper .dropify-message span.file-icon {
        font-size: 22px;
        color: #CCC;
    }
    .text-muted{
        max-height: 200px; 
        overflow-y: auto;
        text-align: justify;
    }
    .sweet-alert {
        width: 600px !important;
    }
  </style>
@endpush

@section('content')
@if(session()->has('breadcrumb-item'))
 {{Session::forget('breadcrumb-item')}}
@endif
    <div class="content-wrapper">
        <div class="container-fluid flex-grow-1 light-style container-p-y">
            <div class="container-m-nx container-m-ny bg-lightest mb-3">
                <ol class="breadcrumb text-big container-p-x py-3 m-0">
                    <li class="breadcrumb-item">
                        <a href="{{url('admin/dashboard')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('admin/library/' . $libraryType->id . '/edit')}}" class="text-capitalize">{{$libraryType->title_english}}</a>
                    </li>
                    {{-- <li class="breadcrumb-item active">site</li> --}}
                </ol>

                <hr class="m-0" />

                <input type="hidden" class="form-control" name="type_id" id="library_type" value="{{$libraryType->id}}" >

                {{-- <input type="text" class="form-control" name="libExtentions" id="libExtentions" value="{{ implode(",",$libraryType->libraryextentions->extention->toArray() )}}" > --}}
                <div  style="display: none" class="progress mt-3" style="height: 25px">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                </div>
                <div class="file-manager-actions container-p-x py-2">
                    <div>
                        @if($libraryType->id == 1 && have_right('Create-Album-Image-Gallery'))
                            <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid='NULL' onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                            @elseif($libraryType->id == 2 && have_right('Create-Album-Video-Gallery'))
                            <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid='NULL' onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                            @elseif($libraryType->id == 3 && have_right('Create-Album-Audio-Gallery'))
                            <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid='NULL' onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                            @elseif($libraryType->id == 4 && have_right('Create-Album-Book-Gallery'))
                            <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid='NULL' onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                            @elseif($libraryType->id == 5 && have_right('Create-Album-Document-Gallery'))
                            <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid='NULL' onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                            @else
                        @endif
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-default md-btn-flat dropdown-toggle px-2" data-toggle="dropdown"><i class="ion ion-ios-settings"></i></button>
                                @if($libraryType->id == 1 && have_right('Remove-Image-Gallery'))
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"  onclick="removeDir($(this),'all')">Remove Selected</a>
                                        </div>
                                    @elseif($libraryType->id == 2 && have_right('Remove-Video-Gallery'))
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"  onclick="removeDir($(this),'all')">Remove Selected</a>
                                        </div>
                                    @elseif($libraryType->id == 3 && have_right('Remove-Audio-Gallery'))
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"  onclick="removeDir($(this),'all')">Remove Selected</a>
                                        </div>
                                    @elseif($libraryType->id == 4 && have_right('Remove-Book-Gallery'))
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"  onclick="removeDir($(this),'all')">Remove Selected</a>
                                        </div>
                                    @elseif($libraryType->id == 5 && have_right('Remove-Document-Gallery'))
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"  onclick="removeDir($(this),'all')">Remove Selected</a>
                                        </div>
                                    @else

                                @endif
                        </div>
                    </div>
                    <div>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons" style="display: none;">
                            <label class="btn btn-default icon-btn md-btn-flat active"> <input type="radio" name="file-manager-view" value="file-manager-col-view" checked="" /> <span class="ion ion-md-apps"></span> </label>
                            <label class="btn btn-default icon-btn md-btn-flat"> <input type="radio" name="file-manager-view" value="file-manager-row-view" /> <span class="ion ion-md-menu"></span> </label>
                        </div>
                    </div>
                </div>

                <hr class="m-0" />
            </div>

            <div class="file-manager-container file-manager-col-view">
                <div class="file-manager-row-header">
                    <div class="file-item-name pb-2">Filename</div>
                    <div class="file-item-changed pb-2">Changed</div>
                </div>

                <div class="file-item back_directory d-none" data_parent_id="" data_libType={{$id}} onclick="openDirectory($(this))">
                    <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
                    <a href="javascript:void(0)" class="file-item-name" data_parent_id="" data_libType={{$id}} onclick="openDirectory($(this))">
                        ..
                    </a>
                </div>
                @forelse($row as $key=>$val)
                    <div class="file-item directories" id="box_{{$val->id}}" data_last_id="{{$val->id}}">
                        <div class="file-item-select-bg bg-primary" ></div>
                            <label class="file-item-checkbox custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input check-boxes" data_dir_type="folder" data-id="remove_{{$val->id}}"/>
                                <span class="custom-control-label"></span>
                            </label>
                        <div  data_libType="{{$val->type_id}}" data_parent_id="{{$val->id}}" onclick="openDirectory($(this))">
                            @if(empty($val->icon))
                                <div class="file-item-icon far fa-folder text-secondary"></div>
                                @else
                                <div class="file-item-icon far fa-folder text-secondary"></div>
                            @endif
                                <a href="javascript:void(0)" class="file-item-name">
                                    {{$val->title_english}}
                                </a>

                            <div class="file-item-changed">{{$val->created_at}}</div>
                        </div>
                        <div class="file-item-actions btn-group">
                            <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" data_libType="{{$val->type_id}}" data_parent_id="{{$val->id}}" onclick="openDirectory($(this))">Open Directory</a>
                                @if($libraryType->id == 1 && have_right('Remove-Image-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                    @elseif($libraryType->id == 2 && have_right('Remove-Video-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                    @elseif($libraryType->id == 3 && have_right('Remove-Audio-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                    @elseif($libraryType->id == 4 && have_right('Remove-Book-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                    @elseif($libraryType->id == 5 && have_right('Remove-Document-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                    @else
                                @endif
                                @if($libraryType->id == 1 && have_right('Update-Image-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                    @elseif($libraryType->id == 2 && have_right('Update-Video-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                    @elseif($libraryType->id == 3 && have_right('Update-Audio-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                    @elseif($libraryType->id == 4 && have_right('Update-Book-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                    @elseif($libraryType->id == 5 && have_right('Update-Document-Gallery'))
                                        <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                    @else
                                @endif
                            </div>
                        </div>
                    </div>

                @empty
                @endforelse

            </div>
        </div>
        <div class="modal fade " id="detail-dir-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="  " id="modal-data">
                    {{-- <div class=" d-flex justify-content-end libray-modal-btn-close">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> --}}
                    <div class="modal-body">

                    </div>
                </div>
              </div>
            </div>
        </div>
        {{-- modal end  --}}
    </div>

@endsection

@push('footer-scripts')
    <!-- Larage File resumable -->
    <script src="{{asset('assets/admin/dist/js/resumable.js')}}"></script>

    <!-- jQuery -->
    <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- jquery-validation -->
    <script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('assets/admin/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/admin/inputmask/jquery.inputmask.min.js')}}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
    <!-- Page specific script -->
    <!-- SummerNote -->
    <script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
    {{-- sweat alert  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

    {{-- dropify cdn  --}}
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

    // $.validator.addMethod("maxImageDimension", function(value, element, params) {
    //     var img = new Image();
    //     img.src = value;

    //     alert(params[0]);
    // return this.optional(element) || (img.width > params[0] && img.height > params[1]);
    // }, "Maximum dimension exceeded");

    $(document).ready(function() {
        $(document).on('change', '#imageinpt', function() {
        // $('#imageinpt').on('change', function() {
            var file = this.files[0];
            var img = new Image();
            var reader = new FileReader();

            reader.onload = function(e) {
                img.src = reader.result;

                var srcpreview=$("#imageinpt").attr('data-default-file');
                $(".dropify-render").html('');

                setTimeout(function(){
                    $(".dropify-render").html('');
                    $(".dropify-render").html(`<img src="${img.src}" />`)
                }, 1000);

                $(img).on('load', function() {
                    // if (img.width >= 215 || img.height >= 215) {
                    // // alert("Image dimension should be 214 x 214");
                    // swal("Oops!", "Max Image dimension should be 214 x 214.", "error")

                    // var drEvent = $('.dropify').dropify();

                    // drEvent = drEvent.data('dropify');
                    // drEvent.resetPreview();
                    // $("#imageinpt").val('');

                    // }
                });
            };

            reader.readAsDataURL(file);
        });
    });

    function loadlibraryApi(){
        // alert('lib');
        const viewer = new Viewer(document.getElementById('images'), {
        toolbar: {
                zoomIn: 4,
                zoomOut: 4,
                oneToOne: 4,
                reset: 4,
                prev: 4,
                play: {
                show: 4,
                size: 'large',
                },
                next: 4,
                rotateLeft: 4,
                rotateRight: 4,
                flipHorizontal: 4,
                flipVertical: 4,
            },
            title:false

        });

    }
    function submitForm(){

        $('#detail-form').validate({
            ignore: false,
            rules:
            {
                // img_thumb_nail: {
                //     maxImageDimension: [214, 214]
                // }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
            invalidHandler: function(e,validator)
            {
                // loop through the errors:
                for (var i=0;i<validator.errorList.length;i++){
                    // "uncollapse" section containing invalid input/s:
                    $(validator.errorList[i].element).closest('.collapse').collapse('show');
                }
            }
        });
    }


    function createdirectory(_this){
        var type_id = _this.attr('data_libtype')
        var directory_type =_this.attr('data_dirtype')
        var parent_id =_this.attr('data_parentid')

    $.ajax({
        type: "POST",
        url: "{{ url('admin/create-directory')}}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {type_id:type_id,directory_type:directory_type,parent_id:parent_id},
        beforeSend: function () {
                // $(".preloader").addClass('adminloader');
                // $('.animation__shake').show();
            },
            success: function (response) {
                var response = JSON.parse(response);
                $(".back_directory:last").after(response);
                // $(".preloader").removeClass('adminloader');
                // $('.animation__shake').hide();
            }

        });
    }

    function getDirDetails(_this){
        var id = _this.attr('data_libid')
        var directory_type = _this.attr('data_typedir')
        $.ajax({
            type: "GET",
            url: "{{ url('admin/dir-details')}}",
            data: {id:id,directory_type:directory_type},
            success: function (response) {
                var response = JSON.parse(response);
                $('#detail-dir-modal').modal('show')
                $('#detail-dir-modal').find('.modal-body').html(response)
                $("#files").val(directory_type);
                // dropifyIntialize();
                $('.dropify').dropify({

                });
                var srcpreview=$("#imageinpt").attr('data-default-file');
                $(".dropify-render").html(`<img src="${srcpreview}" />`)
                submitForm();
            }

        });
    }

    function dropifyIntialize(){

        $('.dropify').dropify({
            tpl: {
                preview: '<div class="dropify-preview"><span class="dropify-render"></span></div>'
            },
            messages: {
                default: 'Drag and drop a file here or click',
                replace: 'Drag and drop or click to replace',
                remove:  'Remove',
                error:   'Ooops, something wrong happened.'
            },
            error: {
                fileSize: 'The file size is too big (214 max).',
                fileExtension: 'The selected file is not allowed. Please upload a valid (214) file.'
            },
            preview: {
                el: '.dropify-render',
                render: function (file, preview) {
                    if (file.type == 'image/webp') {
                        alert('web')
                        var image = new Image();
                        image.src = window.URL.createObjectURL(file);
                        image.onload = function() {
                            var canvas = document.createElement('canvas');
                            canvas.width = image.width;
                            canvas.height = image.height;
                            canvas.getContext('2d').drawImage(image, 0, 0);
                            preview.appendChild(canvas);
                        };
                    } else {
                        alert("image.jpg")
                        var icon = $('<i class="file-icon" />').addClass('icon-' + file.extension);
                        preview.html(icon);
                    }
                }
            }
        });

    }

    var prev_data_parent_id;
    function openDirectory(_this,back=null,library_type='',parent_id='',page = 1){
        var data_parent_id=_this.attr('data_parent_id');
        if(data_parent_id == prev_data_parent_id){
            return false;
        }
        prev_data_parent_id = data_parent_id;

        _this.dblclick(function(e) {
            return false; // reject the openDirectory() function
        });

        var data_libType=_this.attr('data_libType')
        // var data_parent_id=_this.attr('data_parent_id');
        var data_back = _this.attr('data_back');
        if (typeof data_back !== 'undefined' && data_back !== false) {
            var data_back = data_back;
        }else{
            var data_back = '';
        }

        $.ajax({
            type: "GET",
            url: "{{ url('admin/open-dir')}}",
            data: {data_libType:data_libType,data_parent_id:data_parent_id,data_back:data_back},
            success: function (response) {
                var response = JSON.parse(response);
                $('.content-wrapper').html(response.html);
                uploadFile();
                //  loadlibraryApi();
            }

        });
    }

    var currentPage = 1; // Initialize the current page

    function loadMore() {
        $('#load-more-lib').text('Processing...');
        // Add spinner icon
        $('#load-more-lib').append('<i class="fa fa-spinner fa-spin"></i>');
        currentPage++; // Increment the current page
        var library_type = $('#library_type').val();
        var parent_id = $('#parent_id').val();
        var data_back = '';
        $.ajax({
            type: "GET",
            url: "{{ url('admin/open-dir')}}",
            data: {data_libType:library_type,data_parent_id:parent_id,data_back:data_back,page: currentPage,load_more:1},
            success: function (response) {
                $('#load-more-lib i.fa-spinner').remove();
                $('#load-more-lib').text('View More');
                var response = JSON.parse(response);
                $('.dynamic_load_data').append(response.html);
                uploadFile();
                console.log(response.load_more);
                //  loadlibraryApi();
                if (response.load_more < 21) {
                    $('#load-more-sec').css('visibility', 'hidden'); // Hide if no more pages
                } else {
                    $('#load-more-sec').css('visibility', 'visible'); // Show the load more section
                }
            }

        });

    }

    // upload file data //
    if($("#library_type").val() == 1){
        var allowTypes = ['gif','png','jpg','jpeg','webp'];

    }else if($("#library_type").val() == 2)
    {
        var allowTypes = ['mp4','mov','wmv','avi','avchd','mkv'];

    }else if($("#library_type").val() == 3)
    {
        var allowTypes= ['m4a','flac','mp3','wav','wma','aac'];

    }else if($("#library_type").val() == 4 || $("#library_type").val() == 5){
        var allowTypes= ['pdf'];

    }
    var libId =$("#library_type").val();


    function uploadFile(){
        var albumId = $("#parent_id").val();
        // alert(albumDetailId);
        var browseFile = $('#browseFile');
        var resumable = new Resumable({
        target: "{{ url('admin/save-files-ajax')}}/&?"+'albumId='+albumId,
        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
        fileType: allowTypes,
        data: {albumId:albumId},
        chunkSize: 10*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
        headers: {
            'Accept' : 'application/json'
        },
        testChunks: false,
        throttleProgressCallbacks: 1,
        });
        resumable.assignBrowse(browseFile[0]);
        // alert(resumable); return 0;

        // trigger when file picked
        resumable.on('fileAdded', function (file) {
            // alert("added");
            showProgress();
            resumable.upload() // to actually start uploading.
        });

        resumable.on('fileProgress', function (file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });

        resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete

            // alert("add");
            var response = JSON.parse(response);
            $('.content-wrapper').html(response);
            uploadFile();

        });
        const errorFilesArray = [];
        // trigger when there is any error
        resumable.on('fileError', function (file, response) {
            errorFilesArray.push(file.fileName);
            // alert('file uploading error.');
            swal("Failed Files!", errorFilesArray.join('\n'), "error",)
            
    });
    let progress = $('.progress');

    function showProgress() {
        progress.find('.progress-bar').css('width', '0%');
        progress.find('.progress-bar').html('0%');
        progress.find('.progress-bar').removeClass('bg-success');
        progress.show();
    }

    function updateProgress(value) {
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    function hideProgress() {
        progress.hide();
    }

    } // end of upload function


    function removeDir(_this,removeType=null)
    {
        swal({
        title: "Do You Want Delete ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ok",
        closeOnConfirm: false
        }, function (isConfirm) {
        if (!isConfirm) return;
            if(removeType == 'all'){
                $('.check-boxes').each(function(){
                    if(this.checked){
                        var id = this.getAttribute('data-id').split("_")[1];
                        var dirtype = this.getAttribute('data_dir_type');
                        $.ajax({
                        type: "GET",
                        url: "{{ url('admin/remove-dir')}}",
                        data: {id:id,dirtype:dirtype},
                            success: function (response) {
                                $("#box_"+id).remove();
                                swal("Done!", "Deleted Successfully.", "success");
                            }
                        });
                    }
                });

            }else{
                var id = _this.attr('id').split("_")[1];
                var dirtype = _this.attr('data_dir_type');
                $.ajax({
                type: "GET",
                url: "{{ url('admin/remove-dir')}}",
                data: {id:id,dirtype:dirtype},
                    success: function (response) {
                        $("#box_"+id).remove();
                        swal("Done!", "Deleted Successfully.", "success");
                    }
                });
            }
        });
    }

    function testFunction(){
        return false;
        // alert("ok");
    }

    function getlibApiPreviwer(_this){
        var fileType =$('#fileType').val()
        var clickid=_this.attr('data-attr');

        if(fileType == 'image'){
            loadlibraryApi();
            $("#dynamic_"+clickid).click();
        }else if(fileType == 'document'){
            return false;
            // alert(fileType);
            // var dataSrc=_this.attr('data-src');
            // alert(dataSrc);
            // $('#detail-dir-modal').modal('show')
            // $('#detail-dir-modal').find('.modal-body').html('<iframe  src="'+dataSrc+'" width="100%" height="500vh" style="border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowFullScreen> </iframe>')

            // $('#modal-data').html("<p>fasdfsadfsadfsadfdsa</p>")
        }else if(fileType == 'video' || fileType == 'audio'){
            // alert(fileType);

            $.ajax({
                type: "GET",
                url: "{{url('admin/view-playlist')}}",
                data: { clickid: clickid },
                success: function (response) {

                    var response = JSON.parse(response);
                    $('#detail-dir-modal-video').modal('show')
                    $('#detail-dir-modal-video').find('.modal-body').html(response);
                    $("#list_"+clickid).children('img').addClass('activefile')

                },

            });

        }
    }

    $(document).on("click",".flowpaper_arrow",function() {
        alert("click");
    });

    function updateIframe(_this){
        var data_src =  _this.attr('data-src')
        var data_title = _this.attr('data-title')
        var data_id = _this.attr('data-id')
        var data_createdat = _this.attr('data-createdat')
        var type_data = _this.attr('type-data');
        if(type_data == 1){
            $('#iframe_id').html(' <iframe  width="800"  height="510" src="'+data_src+'" allowfullscreen></iframe>');
        }else if(type_data == 0){
        $('#iframe_id').html('<video width="800"   height="610" controls src="'+data_src+'"> </video>');
        }else{
        var data_image = _this.attr('data-image')
        $('#iframe_id').html('<div class="audio-backimgh"> <img src="'+data_image+'"/></div><audio controls width="500" height="310" class="audio-frame"> <source src="'+data_src+'" > </audio>');
        }

        $('.list_img').removeClass('activefile')
        _this.children('img').addClass('activefile')
        $('.file_tile').text(data_title);
        $('#file_created').text(data_createdat);
    }

    $(document).on("hide.bs.modal","#detail-dir-modal-video",function() {
        $('#detail-dir-modal-video').find('.modal-body').html('');
    });


    function isValidURL(str) {
        if(/^(http(s):\/\/.)[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/g.test(str)) {
            return true;
            console.log('YES');
        } else {
            return false;
            console.log('NO');
        }
    }
    //___________upload file Link____________//
    function uploadLink(){
        event.preventDefault();
        var _videoUrl = $("#link_upload").val();
        _videoUrl =_videoUrl.replace(/\s+/g, '')

        if(_videoUrl.split(".com")[0] == 'https://www.youtube'){
            _videoUrl=_videoUrl.split('&list')[0];
            _videoUrl=_videoUrl.split('&list')[0];
                _videoUrl=_videoUrl.replace('watch?v=','embed/');
        }else if(_videoUrl.split(".com")[0] == 'https://drive.google'){
                _videoUrl=_videoUrl.replace('/view?','/preview?');
        }else if(_videoUrl.split(".com")[0] == 'https://www.dailymotion'){
                _videoUrl=_videoUrl.replace('/video/','/embed/video/');

        }
        else{
            swal("Error!", "Please enter valid youtube or google drvie video link", "error");
            $("#link_upload").val('');
            return 0;
        }

        var libraryAlbumId =$("#libAlbumId").val();
        $.ajax({
            type: "POST",
            url: "{{ url('admin/save-files-ajax')}}/"+libraryAlbumId,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {videUrl:_videoUrl,libraryAlbumId:libraryAlbumId},
            success: function (response) {
                var response = JSON.parse(response);
                // $('#detail-dir-modal').modal('hide');
                $('.content-wrapper').html(response);
                uploadFile();
                $('#detail-dir-modal-youtube').modal('hide');
                $('.modal-backdrop').css('display','none')
                $("#link_upload").val('');
            },
        });

    }
    function openLinkModal(){


    }
</script>
@endpush
