@extends('user.layouts.layout')
@push('styles')

   <link rel="stylesheet" href="{{asset('assets/home/css/file-manager.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Flipbook StyleSheet -->
 <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

 <!-- Icons Stylesheet -->
 <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />
@endpush
@section('content')

<input type="hidden" id="type_idlib" value="{{$type_idlib}}" >
<input type="hidden" value="{{$album_id}}" id="album_id">
<div class="row dashbord-serch-items justify-content-end align-items-end">
    <div class="col-md-6 col-12 d-flex">
    <input type="text" id="search-libray"  class="form-control" placeholder="{{ __('app.search-library') }}"> <button class="btn btn-primary" onclick="getLibSearchResult($(this))">{{__('app.search')}}</button>
    </div>
</div>
<div class="container-width" id="dynamic_div">


</div>
{{-- @include('home.home-sections.get-in-touch') --}}
    <!-- Modal -->
    <div class="modal library-detail common-model-style fade file-manager-modal" id="copy-text-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.file-link') }}</h4>
                </div>
                <div class="modal-body d-flex">
                    <input class="form-control" type="text" readonly value="Hello World" id="copy-text-input">
                    <button type="button"  class="btn btn-primary " onclick="copyText()">
                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                    <!-- {{ __('app.copy-link') }} -->
                    </button>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="green-hover-bg theme-btn" >{{ __('app.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<!-- tags input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Flipbook main Js file -->
<script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
<script>

    $("#search-libray").on('keypress',function(e) {
            if(e.which == 13) {
                getLibSearchResult('');
            }
        });


        function getLibSearchResult(_this){
            var albumid = $('#album_id').val();

            var searchData = $("#search-libray").val();
            var type_idd =   $("#type_idlib").val();
            $.ajax({
                type: 'get',
                url: "{{url('/search-library')}}",
                data: { searchData: searchData ,type_id:type_idd },
                success: function (response) {
                    var response = JSON.parse(response);
                    if(response != ''){

                        $('#dynamic_div').html(response);
                    }else{
                        openpartialAlbum(albumid);
                    }
                }
            });
        }

    // share link model open //
    function shareLibLink(_this){
       $("#copy-text-modal").modal('show');
       $("#copy-text-input").val(_this.attr('data-link'));
    }

    // copy text file //
    function copyText(){
            // Get the text field
            var copyText = document.getElementById("copy-text-input");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            toastr.options =
                {
                    "closeButton" : true,
                    "progressBar" : true
                }
            toastr.success("{{__('app.copy-text-success')}}");
            // Alert the copied text
            // alert("Copied the text: " + copyText.value);
        }


    $( document ).ready(function() {
        var albumid = $('#album_id').val();
        // alert(albumid)
        openpartialAlbum(albumid);
    })
    function openpartialAlbum(albumid){
        $.ajax({
            type: "GET",
            url: "{{ url('album')}}",
            data: {id:albumid},
                success: function (response) {
                    var response = JSON.parse(response);
                    $('#dynamic_div').html(response);
                }

            });
    }

    function getlibApiPreviwer(_this){
        var fileType =$('#fileType').val()
        var clickid=_this.attr('data-attr');
        var dataSrc=_this.attr('data-src');
        if(fileType == 'image'){
            loadlibraryApi();
            $("#dynamic_"+clickid).click();
        }else if(fileType == 'document'){
            return false;
            // alert(dataSrc)
            // $('#detail-dir-modal').modal('show')
            // $('#detail-dir-modal').find('.modal-body').html('<iframe src="'+dataSrc+'" width="100%" height="500vh" style="border: none;"  allowFullScreen> </iframe>')
        }else if(fileType == 'video' || fileType == 'audio'){
                $.ajax({
                    type: "GET",
                    url: "{{url('view-playlist')}}",
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

        function loadlibraryApi(){
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
        function updateIframe(_this){
             var data_src       =  _this.attr('data-src')
             var data_title      =  _this.attr('data-title')
             var data_id         =  _this.attr('data-id')
             var data_createdat  =  _this.attr('data-createdat')
             var type_data      =  _this.attr('type-data');

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
            // $('#iframe_id').attr('src',data_src);
            $('.file_tile').text(data_title);
            $('#file_created').text(data_createdat);
        }

        $(document).on("hide.bs.modal","#detail-dir-modal-video",function() {
            $('#detail-dir-modal-video').find('.modal-body').html('');
        });


    //__________for more data____________//
    function viewMore(typeDir)
    {
        var lastId=$('.'+typeDir+':last').attr('data-last-id');
        var libType=$('.'+typeDir+':last').attr('data-type');
        var parent_id=$('.'+typeDir+':last').attr('data-parent-id');
        $.ajax({
            type: 'get',
            url: '{{ URL("album-load") }}',
            data: { type: libType,lastId:lastId,limit:8,parent_id:parent_id,typeDir:typeDir },
            success: function (data) {
                if( data.length > 0){
                    $('.'+typeDir+':last').after(data);
                }else{
                    $("#load-lib_"+typeDir).css('display',"none");
                }
            }
        });
    }
</script>
@endpush
