@extends('home.layout.app')
@push('header-scripts')
 <!-- Flipbook StyleSheet -->
 <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

 <!-- Icons Stylesheet -->
 <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />
   <link rel="stylesheet" href="{{asset('assets/home/css/file-manager.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<input type="text" value="{{$empId}}" id="emp_id">

<div class="container-width" id="dynamic_div">
        <input type="hidden" value="{{$album_id}}" id="album_id">

</div>
@include('home.home-sections.get-in-touch')

@endsection


@push('footer-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Flipbook main Js file -->
<script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
<script>

    $( document ).ready(function() {
        var albumid = $('#album_id').val();
        var empId = $('#emp_id').val();
        // alert(albumid)
        openpartialAlbum(albumid,empId);
    })
    function openpartialAlbum(albumid,empId){
        $.ajax({
            type: "GET",
        url: "{{ url('album')}}",
            data: {id:albumid,empId:empId},
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
                        $("#load-lib").css('display',"none");
                    }
                }
            });
        }
</script>
@endpush
