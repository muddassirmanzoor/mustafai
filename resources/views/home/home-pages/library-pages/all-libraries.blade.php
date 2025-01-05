    @extends('home.layout.app')

@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper image-library--bg">
    <!-- image library-->
    @if(!empty($imageLibrary))
    <section class="image-libaray">
        <div class="container-fluid container-width">
            <div class="d-flex flex-column justify-content-center align-items-center im-libtext">
                <h3 class="cm-head text-capitalize">{{ optional($imageLibrary)->title}}</h3>
                <p class="text-center cm-p-text">{!!optional($imageLibrary)->content!!}</p>
            </div>
            <div class="image-library-wraper d-flex justify-content-between align-items-center">

                @forelse(optional($imageLibrary)->libraries as $key=>$val)
                <div class="library-image-box member-{{$loop->iteration}}">
                    <img src="{{asset($val->file)}}" class="img-fluid" />
                </div>
                    @if($loop->iteration == 5)
                        @php
                        break;
                        @endphp
                    @endif
                @empty
                    <p>Empty Image Library</p>
                @endforelse
                
                
                
            </div>
            <div class="d-flex justify-content-center mt-2">
                <a class="green-hover-bg theme-btn" href="{{url('/view-library')}}/{{$imageLibrary->id}}">{{__('app.view-library-title')}}</a>
            </div>
        </div>
    </section>
@endif
@if(!empty($audioLibrary))
    <section class="audio-libaray all-audios">
        <div class="container-fluid container-width">
            <div class="d-flex flex-column justify-content-center align-items-center im-libtext">
                <h3 class="cm-head text-green text-capitalize">{{{optional($audioLibrary)->title}}}</h3>
                <p class="text-center cm-p-text">{!!optional($audioLibrary)->content!!}</p>
            </div>
            <div class="row">
                    @forelse(optional($audioLibrary)->libraries as $key=>$val)

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="video-container">
                            <video id="videoLink" poster="{{asset('images/library_images/library-img-1.png')}}" width="100%" height="100%">
                                <!-- <source src="./video/video-1.mp4" type="video/mp4" /> -->
                            </video>

                            <div class="overlay"></div>
                            <span class="play-btn" id="playBtn" data-val="audio" onclick="showVideo('{{asset($val->file)}}','audio')"></span>
                            <span class="pause-btn" style="display: none" id="pauseBtn"  onclick="pauseVideo()"><i class="fa fa-pause video-pause"></i></span>
                            <div class="audio-start-lottie">
                                {{-- <img src="{{asset('/images/library_images/lottie-pause.png')}}" data-val="audio" onclick="showVideo('{{asset($val->file)}}')" class="img-fluid" alt="no image" />
                                <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_sztmvfus.json" speed="1" loop autoplay></lottie-player> --}}
                            </div>
                        </div>
                        <div class="video-speaker-msg pt-lg-3 pt-2">
                            <h6>{{$val->file_title}}</h6>
                        </div>
                    </div>
                    @if($loop->iteration == 4)
                    @php
                    break;
                    @endphp
                    @endif
                    @empty
                    @endforelse
                    <div class="d-flex justify-content-center mt-2">
                        <a class="green-hover-bg theme-btn " href="{{url('/view-library')}}/{{$audioLibrary->id}}">{{__('app.view-library-title')}}</a>          
                    </div>
                </div>
            </div>
        </section>
@endif
@if(!empty($videoLibrary))
    <section class="video-libaray all-videos">
        <div class="container-fluid container-width">
            <div class="d-flex flex-column">
                <h3 class="cm-head text-red text-capitalize">{{optional($videoLibrary)->title}}</h3>
                <p class="cm-p-text vd-p-text ">{!!optional($videoLibrary)->content!!}</p>
            </div>
            <div class="row ">

                    @forelse(optional($videoLibrary)->libraries as $key=>$val)
                    <div class="col-xl-3 col-md-4 col-sm-6 common-img-lib-wrap">
                        <div class="video-container">
                            <video width="100%">
                                <source src="{{asset($val->file)}}" type="video/mp4" id="video">
                                Your browser does not support the video tag.
                            </video>
                            <!-- Button trigger modal -->

                            <a type="button" data-val="video" data-src="{{$val->file}}" onclick="showVideo('{{asset($val->file)}}','video')">
                                <span class="pause-2">
                                    <img src="{{asset('images/library_images/pause-img.png')}}" />
                                </span>
                            </a>
                        </div>
                        <div class="video-speaker-msg pt-lg-3 pt-2">
                            <h6>{{$val->file_title}} </h6>
                        </div>
                        
                    </div>
                

                    @if($loop->iteration == 4)
                        @php
                        break;
                        @endphp
                    @endif

                    @empty
                    @endforelse

 
                </div>
                <div class="d-flex justify-content-center mt-2">
                        <a class="green-hover-bg theme-btn " href="{{url('/view-library')}}/{{$videoLibrary->id}}">{{__('app.view-library-title')}}</a>
                </div>

            </div>
        </section>
@endif
@if(!empty($bookLibrary))
    <section class="audio-libaray all-documents">
        <div class="container-fluid container-width">
            <div class="d-flex flex-column justify-content-center align-items-center im-libtext">
                <h3 class="cm-head text-green">{{__('app.documents-and-magzine-library')}}</h3>
                <p class="text-center cm-p-text">{!!optional($bookLibrary)->content!!}</p>
            </div>
            <div class="row justify-content-sm-start justify-content-center">

                    @forelse(optional($bookLibrary)->libraries as $key=>$val)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-sm-0 mb-3">
                        <div class="common-img-lib-wrap dcm-img-wrp">
                            <span class="pdf-img"><img src="{{asset('/images/library_images/pdf.png')}}" /></span>
                            <a href="{{asset($val->file)}}" target="_blank"><img src="{{asset('/images/library_images/document-0.png')}} " class="img-fluid img-magz" /></a>
                        </div>
                        <div class="dcm-speaker pt-lg-3 pt-2">
                            <h6>{{$val->file_title}}</h6>
                        </div>
                    </div>
                    @if($loop->iteration == 4)
                        @php
                        break;
                        @endphp
                    @endif
                    @empty
                    @endforelse
                    <div class="d-flex justify-content-center mt-2">
                        <a class="green-hover-bg theme-btn " href="{{url('/view-library')}}/{{$bookLibrary->id}}">{{__('app.view-library-title')}}</a>
                    </div>
                </div>
            </div>
        </section>
@endif
    <!-- Modal -->
    <div class="modal fade library-detail common-model-style video-modal-design" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body" id="model_content_video">
                    <video width="100%" controls src="" id="video_tag" >

                    </video>
                </div>

            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade  common-model-style audio-modal " id="audioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body" id="model_content_audio">
                <audio width="100%" controls  src="" id="audio_tag" >

                </audio>
            </div>

        </div>
    </div>
</div>


    @include('home.home-sections.get-in-touch')

@endsection

@push('footer-scripts')
    <script >

        function showVideo(src,dataType){
            var data_val=dataType;
            if(data_val == 'audio'){
                $("#model_content_audio").find("#audio_tag").attr('src',src)
                $("#audioModal").modal('show')
            }else{
                $("#model_content_video").find("#video_tag").attr('src',src)
                $("#videoModal").modal('show')
            }
        }
    </script>
@endpush
