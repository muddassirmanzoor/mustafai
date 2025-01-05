 <!-- baner -->
 @if(!empty($video))
 <input type="hidden" id="slider_video" value="{{asset($video->option_value)}}">
 @endif
 @if(!empty($sliders))
 <section class="main-banner" style="background-image: url('{{asset($sliders->image)}}')">
    <div class="container-fluid container-width">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                {!! $sliders->content !!}
            </div>
        </div>
    </div>
</section>
@endif
