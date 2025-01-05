@extends('home.layout.app')
@push('header-scripts')

   <link rel="stylesheet" href="{{asset('assets/home/css/file-manager.css')}}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
@endpush
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper gallary-bg">

    @php

    //   $libStatus=\App\Models\Admin\LibraryType::where(['id' => $libtypee])->pluck('status')->first();
    //   $libalbumCount=\App\Models\Admin\LibraryType::where('id', $libtypee)->first()->libraryAlbum()->whereNull('parent_id')->count();
    @endphp
    <!-- press gallary-->
    @if( $libStatus == 1)
    <section class="press-gallary container-fluid container-width">
        <input type="hidden" id="type_idlib" value="{{encodeDecode($libtypee)}}" >
        <input type="hidden" value="" id="album_id">
        <div class=" d-flex justify-content-right">
            <div class="w-100">
                <div class="row serch-album1 mb-3">
                    <div class="col-md-6 d-flex">
                        <input type="text" id="search-libray"  class="form-control" placeholder="{{ __('app.search-library') }}"> <button class="btn btn-primary" onclick="getLibSearchResult($(this))">{{__('app.search')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="list-tab ">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach($libraryTypes as $key => $type)
                        <li class="nav-item {{isset($_GET['tab'])? '':'d-none'}}" role="presentation">
                            <button class="nav-link {{  ($libtypee == $type->id) ? 'active':'' }} lib-tab-headers lib-{{$type->id}}" data-cl="lib-{{$type->id}}" data-val="{{$type->id}}" data-title="{{ ucfirst($type->title) }}" aria-selected="true" onclick="getLibrarySections('{{$type->id}}','lib-{{$type->id}}')">{{ ucfirst($type->title) }}</button>
                        </li>
                    @endforeach
                </div>
            </nav>
        </div>



        <div class="">
            <div class="d-flex flex-column justify-content-center align-items-center gallery_data">
                {{-- <h3 class="cm-head text-capitalize">{{ optional($libData)->title }}</h3> --}}
                {{-- <p class="text-center cm-p-text">{{ optional($libData)->description}}</p> --}}
            </div>
            <div class="fa-3x small-loader d-none" id="libe-preloader">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
            <div class="row multi-galry-images gallery_data parent-library-cms" id="gallery_data">

            </div>
            @if($libalbumCount > 8)
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <button type="button" id="load-lib" class="theme-btn-borderd-btn theme-btn text-inherit test-main-library" onclick="viewMore(8)">{{ __('app.view-more') }}</button>
                </div>
            @endif
        </div>
    </section>

@auth
<!-- Wo Jo Hamara Fakhar Hain -->
{{-- <div class="gallary-team-wraper view-library-pg">
    <section class="our-team">
        <div class="ccontainer-fluid container-width">
            <div class="d-flex flex-column justify-content-center align-items-center team-title">
                <div class="d-flex justify-content-center align-items-center">
                    <h2 class="our-team-h">{{__('app.our-team')}}</h2>
                </div>
                <div class="moving-star">
                    <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_8ijhsyoi.json" background="transparent" speed="0.8" loop autoplay></lottie-player>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <h4 class="text-blue managment-heading"></h4>
                </div>
            </div>
        </div>
    </section>
    @php
    $sizzling = ['assets/home/images/blue-zig.svg','assets/home/images/green-zig.svg','assets/home/images/red-zig.svg'];
    $sliderName = ['blue-slider','green-slider','red-slider'];
    $textColor = ['text-blue','text-green','text-red'];
    $ourTeam = ['first','second','third'];
    @endphp
    @foreach($sections as $key=>$val)

    <section class="our-team">
        <div class="ccontainer-fluid container-width">
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="text-green">
                    {{$val->name}}
                </h4>
            </div>
        </div>
        <div class="our-team-slider {{$sliderName[$key]}}">
            <div id="owl-two" class="owl-carousel owl-theme">

                @foreach($val->employee_sections(1)->get() as $empKey=>$empVal)
                <a href="{{url('employee-details?id='.hashEncode($empVal->id).'')}}">

                    <div class="slider-item">
                        <div class="slider-zigzag">
                            <img class="simple-image" src="{{asset('assets/home/images/Image-bg.svg')}}" />
                            <img class="blue-hover-image" src="{{asset($sizzling[$key])}}" />
                            <div class="staric-image">
                                <img src="{{ asset($empVal->image) }}" alt="image not found" class="img-fluid" />
                            </div>
                        </div>
                        <div class="short-desription">
                            <div class="naming-tag">
                                <p>{{ $empVal->{'designation_'.APP::getLocale()} }} </p>
                            </div>
                            <h5>{{ $empVal->{'name_'.APP::getLocale()} }}</h5>
                        </div>
                    </div>
                </a>
                @endforeach

            </div>
        </div>
    </section>
    @endforeach

</div>
<!-- latest-event -->
<section class="latest-event">
    <div class="ccontainer-fluid container-width">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <h3 class="cm-head">{{__('app.latest-events')}}</h3>
        </div>
        <div class="row event-detail mt-5">
            @foreach($events as $key=>$val)
            <div class="col-lg-4">
                <div class="event-box">
                    <img src="{{asset($val->image)}}" class="img-fluid" />
                    <div class="event-ifo">
                        <h5>{{$val->title}}</h5>
                        <p class="public-project-paragraph" style="font-weight: normal;">
                            {!! \Str::limit($val->content, 300, '') !!}
                            @if (strlen($val->content) > 300)
                                <span id="dots_{{ $val->id }}">...</span>
                                <span id="more_{{ $val->id }}" style="display: none;">{!! substr($val->content, 300, strlen($val->content)) !!}</span>
                            @endif
                        </p>
                        @if (strlen($val->content) > 300)
                       <div class="d-flex justify-content-end align-items-end">
                            <a type="button" class="btn btn-primary btn-sm" onclick="readMore(<?php echo $val->id ?>)" id="myBtn_{{ $val->id }}">{{__('app.read-more')}}</a>
                       </div>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section> --}}
@endauth
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
@include('home.home-sections.get-in-touch')
@include('home.modals.image-modal')
</div>
@else
 <div class=" d-flex justify-content-center">

     <h6>Not Found</h6>
 </div>
@endif

@endsection

@push('footer-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

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
    $("#search-libray").on('keypress',function(e) {
            if(e.which == 13) {
                getLibSearchResult('');
            }
        });
    function shareLibLink(_this){
       $("#copy-text-modal").modal('show');
       $("#copy-text-input").val(_this.attr('data-link'));
    }
    function getLibSearchResult(_this){
        // if($('#search-libray').val() === '')
        // {
        //     Swal.fire(AlertMessage.search_empty, '', 'Empty');
        // return '';
        // }
         var albumid = $('#album_id').val();
         var searchData = $("#search-libray").val();
         var type_idd =   $("#type_idlib").val();
         if (searchData == "") {
                Swal.fire(AlertMessage.enter_somethong , '', 'error');
                return false;
            }
        $.ajax({
            type: 'get',
            url: "{{url('/search-library')}}",
            data: { searchData: searchData ,type_id:type_idd },
            beforeSend: function() {
                $('.gallery_data').addClass('d-none')
              $("#libe-preloader").removeClass('d-none')
           },
            success: function (response) {
                var response = JSON.parse(response);
                if(response != ''){

                    $('#gallery_data').html(response);
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
    $(function() {
        var type = $('.lib-tab-headers.active').attr('data-val');
        var tabClass = $('.lib-tab-headers.active').attr('data-cl');

            getLibrarySections(type, tabClass);
    });


    function getLibrarySections(type = '', tabClass = '') {
        $('.lib-tab-headers').removeClass('active');
        $('.lib-tab-headers.' + tabClass).addClass('active');

        $.ajax({
            type: 'get',
            url: '{{ URL("library-tabs") }}',
            data: {
                type: type,

            },
            dataType: 'JSON',
            beforeSend: function() {
                $('.gallery_data').addClass('d-none')
              $("#libe-preloader").removeClass('d-none')
           },
            success: function(data) {
                $("#libe-preloader").addClass('d-none')
                $('.gallery_data').removeClass('d-none')
                $('#gallery_data').html(data.html);
            }
        });
    }

    function getImageModel(_this)
    {

        if(_this.attr('data-val') == 'img'){
            // alert('img');
            var last_id=$(_this).parent('.image-galary').attr('data-last-id');
            var data_type=$(_this).parent('.image-galary').attr('data-type');

            $('.move-lib').css('display','block');
            $(".desc-model-div").show()
            $("#next").attr('last_id',last_id);
            $("#next").attr('type_id',data_type);
            $("#prev").attr('last_id',last_id);
            $("#prev").attr('type_id',data_type);
            var img = $(_this).prev().prev('img').attr('src');
            var desc = $(_this).prev('p').text();
            $("#lib-modal-pic").attr('src',img)
            $("#lib-modal-desc").text(desc)
            $('#libImageModal').modal('show');
            $("#img-model-div").css('display','block ')
            $('#video_1').hide();
            $('#audio_1').hide();
        }
        if(_this.attr('data-val') == 'video'){
            var desc = $(_this).prev('p').text();
            $("#lib-modal-desc").text(desc)
            $('#video_1').show();
            $('#audio_1').hide();
             $('#img-model-div').attr("style", "display: none !important");
            $("#video_tag").attr('src',_this.attr('data-src'))
            $('#libImageModal').modal('show');
        }
        if(_this.attr('data-val') == 'audio'){
            var desc = $(_this).prev('p').text();
            $("#lib-modal-desc").text(desc)
            $('#audio_1').show();
            $('#video_1').hide();
            $('#img-model-div').attr("style", "display: none !important");
            $("#audio_tag").attr('src',_this.attr('data-src'))
            $('#libImageModal').modal('show');
        }
    }
    var URLLib = "{{URL('view-library')}}";

    function goToURL() {
        let type = $('.lib-tab-headers.active').attr('data-val');
        // alert(URLLib +"/"+ type); return 0;
        location.href = URLLib + "/" + type;
        // alert(type);

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

    //__________for more data____________//
    function viewMore(limit){
        var lastId=$('.image-galary:last').attr('data-last-id');
        var libType=$('.image-galary:last').attr('data-type');
        $.ajax({
            type: 'get',
            url: '{{ URL("library-load") }}',
            data: { type: libType,lastId:lastId,limit:limit,guestPage:true },
            success: function (data) {
                if( data.length > 0){
                    $('.image-galary:last').parent().parent().after(data);
                }else{
                    $("#load-lib").css('display',"none");
                }
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
    function openpartialAlbum(albumid){
        $.ajax({
            type: "GET",
            url: "{{ url('album')}}",
            data: {id:albumid},
                success: function (response) {
                    var response = JSON.parse(response);
                    $('#gallery_data').html(response);
                    $("#album_id").val(albumid);
                }

            });
    }
</script>
@endpush
