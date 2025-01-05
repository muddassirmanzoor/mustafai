<div class="user-panel-post-slider {{ ($post->images()->count() >1 )? 'owl-carousel dynamic_owl':'' }} owl-theme">
    @foreach($post->images as $image)
        @php
            $image_extensions = ["jpg","jpeg","png","bmp","svg","gif","webp"];
            $video_extensions = ["flv","mp4","m3u8","ts","3gp","mov","avi","wmv"];
            $get_mimes=\File::extension($image->file)
        @endphp
        @if (in_array($get_mimes , $image_extensions))
            <div class="item">
                <img src="{{ getS3File($image->file) }}" class="img-fluid" alt="image">
            </div>
        @endif
        @if (in_array($get_mimes , $video_extensions))
            <div class="item">
                <video id="myvideo" preload="none" width="100%" height="350" type="video/{{ $get_mimes }}" muted controls>
                    <source src="{{ getS3File($image->file) }}">
                </video>
            </div>
        @endif
    @endforeach
</div>
