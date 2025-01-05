<!-- mustafai libarary -->
<section class="mustafai-libarary">
    <div class="container-fluid container-width">
        
        <div class="row">
            <div class="col-12">
                <div class="d-flex libaray-head">
                    <h3>{{ __('app.library-title') }}</h3>
                    <a class="green-hover-bg theme-btn ms-sm-5" href="javascript:void(0)" onclick="goToURL()">{{ __('app.view-library-title') }}</a>
                </div>
                <div class="carousel-mustafai-content">
                             <div id="carouselExampleControls" class="carousel slide"  data-interval="false">
                                @php
                                     $libArray=array_chunk($libraryTypes->toArray(), 5, true);
                                @endphp
                    <div class="carousel-inner">
                        @foreach($libArray as $key1 => $type)

                        <div class="carousel-item {{$loop->iteration ==1? 'active':''}}">
                             <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" class="justify-content-md-start justify-content-center">
                                    @foreach($type as $key=>$val)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{  ($key == 0) ? 'active':'' }} lib-tab-headers lib-{{$val['id']}}" data-cl="lib-{{$val['id']}}" data-val="{{$val['id']}}" aria-selected="true" onclick="getLibrarySections('{{$val['id']}}','lib-{{$val['id']}}')">{{ ucfirst($val['title']) }}</button>
                                        </li>
                                    @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button> --}}
                    </div>
                </div>
                <div class="tab-content mustafai-library-details-tabs position-relative" id="pills-tabContent">
                    <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="pills-Image">
                        <div class="tab-info">
                            {{-- <p>{{ __('app.view-library-content') }}</p> --}}

                            <div class="fa-3x small-loader d-none" id="libe-preloader">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                            <div class="row home-lib-row" id="lib-tab-content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

@push('footer-scripts')
<script>

   

    $(function() {
        var type = $('.lib-tab-headers.active').attr('data-val');
        var tabClass = $('.lib-tab-headers.active').attr('data-cl');
        getLibrarySections(type,tabClass);
    });

    function getLibrarySections(type='',tabClass='')
    {
        $('.lib-tab-headers').removeClass('active');
        $('.lib-tab-headers.'+tabClass).addClass('active');

        $.ajax({
            type: 'get',
            url: '{{ URL("library-tabs") }}',
            data: { type: type },
            beforeSend: function() {
                $('#lib-tab-content').addClass('d-none')
              $("#libe-preloader").removeClass('d-none')
           },
            dataType: 'JSON',
            success: function (data) {
                $("#libe-preloader").addClass('d-none')
                $('#lib-tab-content').removeClass('d-none')
                $('#lib-tab-content').html(data.html);
                if ($('.no-data-lib').length) {
                    $('.mustafai-libarary').addClass('mustafai-library-section');
                }else{
                    $('.mustafai-libarary').removeClass('mustafai-library-section');
                }
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

    var URLLib ="{{URL('view-library')}}";
    function goToURL(){
        let type = $('.lib-tab-headers.active').attr('data-val');
        // alert(URLLib +"/"+ type); return 0;
        location.href =URLLib +"/"+ type;
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

</script>
@endpush
