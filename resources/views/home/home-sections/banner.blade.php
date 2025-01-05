 <!-- baner -->
 @if(!empty($video))
 <input type="hidden" loading="lazy" id="slider_video" value="{{getS3File($video->option_value)}}">
 @endif
 @if(!empty($sliders))
    <div id="banner-slider" class="owl-carousel owl-theme">
        @foreach ($sliders as $slider)
            <div class="main-banner test"style="background-image: url('{{getS3File($slider->image)}}')">
                <div class="container-fluid container-width">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 banner-content">
                            {!! $slider->content !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
