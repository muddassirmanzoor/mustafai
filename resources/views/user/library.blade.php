@extends('user.layouts.layout')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="mustafai-lib-de userlists-tab">
    <div class="list-tab">
        <input type="hidden" id="type_idlib"  >
        <input type="hidden" value="" id="album_id">
        <div class="d-flex row dashbord-serch-items justify-content-end align-items-end mb-5">
            <div class="col-md-6 col-12 d-flex">
                <input type="text" id="search-libray"  class="form-control" placeholder="{{ __('app.search-library') }}"> <button class="btn btn-primary" onclick="getLibSearchResult($(this))">{{__('app.search')}}</button>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @php
                $allowActive  = 1 ;
                  @endphp
                @foreach($libraryTypes as $key => $type)

                @php   $title_english = $type->title_english;
                $title_english = explode(" ",$title_english);
                $permission_name = "View-".$title_english[0]."-Library";

                  @endphp

                {{-- @if(have_permission($permission_name))
                View-Image-Library --}}
                @if(have_permission($permission_name))
                    <button class="nav-link {{  ($allowActive == 1) ? 'active':'' }} lib-tab-headers lib-{{$type->id}}" data-cl="lib-{{$type->id}}" data-val="{{$type->id}}" data-val-encode="{{encodeDecode($type->id)}}"    type="button"  onclick="getLibrarySections('{{$type->id}}','lib-{{$type->id}}')">
                            {{ ucfirst($type->title) }}
                    </button>
                    @php
                    $allowActive  = 0 ;
                      @endphp
                    @endif

                @endforeach

            </div>
          </nav>

          <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-library-tab1" role="tabpanel" aria-labelledby="nav-library-tab">
                    <div class="tab-info">
                        <div class="fa-3x d-flex justify-content-center align-items-center d-none" id="libe-preloader">
                            <i class="fa fa-spinner fa-spin"></i>
                        </div>
                            {{-- <p>{{ __('app.view-library-content') }}</p> --}}



                            <div class="row user-lib-row gallery_data parent-library libuser" id="lib-tab-content">

                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-5">
                                <button type="button" id="load-lib" class="theme-btn-borderd-btn theme-btn text-inherit" onclick="viewMore(8)">{{ __('app.view-more') }}</button>
                            </div>
                    </div>
                </div>
          </div>
    </div>
</div>
@include('home.modals.image-modal')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('user.scripts.library-script')

    <script>

//    alert("ok")
   $("#search-libray").on('keypress',function(e) {
        if(e.which == 13) {
            getLibSearchResult('');
        }
    });
   function shareLibLink(_this){
       $("#copy-text-modal").modal('show');
       $("#copy-text-input").val(_this.attr('data-link'));
    }
    //    for search libary content
    function getLibSearchResult(_this){
        var type_idd = $('.lib-tab-headers.active').attr('data-val-encode');
        var albumid = $('#album_id').val();
         var searchData = $("#search-libray").val();
        $.ajax({
            type: 'get',
            url: "{{url('/search-library')}}",
            data: { searchData: searchData ,type_id:type_idd,dashboard : "1" },
            beforeSend: function() {
                $('.gallery_data').addClass('d-none')
              $("#libe-preloader").removeClass('d-none')
           },
            success: function (response) {
                var response = JSON.parse(response);
                if(response != ''){
                    $('#lib-tab-content').html(response);
                }else{
                    var type = $('.lib-tab-headers.active').attr('data-val');
                     var tabClass = $('.lib-tab-headers.active').attr('data-cl');
                    getLibrarySections(type, tabClass);
                }
                $("#libe-preloader").addClass('d-none')
                $('.gallery_data').removeClass('d-none')
            }
        });
    }


        function moveLib(_this,data_move){
            $('.move-lib').css('display','block');
            var lastId=_this.attr('last_id')
            var typeId=_this.attr('type_id')
            $.ajax({
                type: "GET",
                url: `{{url('/move-lib')}}`,
                data: { data_move: data_move,lastId:lastId,typeId:typeId },
                success: function (data) {
                    if (data.path != '0') {
                        $('#lib-modal-pic').attr('src',data.path)
                        $('#lib-modal-desc').text(data.description)
                        $('#next').attr('last_id',data.last_id);
                        $('#prev').attr('last_id',data.last_id);
                    } else {
                        $("#"+data_move).css('display','none');
                    }
                },

            });
        }

        function openpartialAlbum(albumid){
            $.ajax({
                type: "GET",
                url: "{{ url('album')}}",
                data: {id:albumid},
                    success: function (response) {
                        var response = JSON.parse(response);
                        $('#lib-tab-content').html(response);
                    }

                });
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
    function getlibApiPreviwer(_this){
        if(!_this){
            return false;
        }
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
    </script>

@endpush
