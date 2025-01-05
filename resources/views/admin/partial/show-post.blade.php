<div>
    <div class="d-flex justify-content-between">
        <h3>
            <u>
                @switch($post->post_type)
                    @case(1) <span>Simple Post</span> @break
                    @case(2) <span>Job Post - {{ $post->job_type == 2 ? 'Seeking' : 'Hiring' }}</span> @break
                    @case(3) <span>Announcement Post</span> @break
                    @case(4) <span>Product Post</span> @break
                    @case(5) <span>Blood Post</span> @break
                @endswitch
            </u>
        </h3>
        <span>
            <form action="{{ route('admin.post.status', $post->id) }}" method="post">
                @csrf
                <button class="btn btn-sm btn-success" name="status" value="1">Approve</button>
                <button class="btn btn-sm btn-danger" name="status" value="2">Decline</button>
            </form>
        </span>
    </div>

    <br>

    <h4><b>Title: </b></h4>
    <span>{{ $post->title_english }}</span>

    <h4>Files: </h4>
    @if($post->post_type == '2' && $post->job_type == 2)
        @if($post->resume != null || $post->resume != '')
            <a href="{{ getS3File($post->resume) }}">{{ __('app.download-resume') }}</a>
        @endif
    @else
        @forelse($post->images as $image)
                @php
                    $image_extensions = ["jpg","jpeg","png","bmp","svg","gif","webp"];
                    $video_extensions = ["flv","mp4","m3u8","ts","3gp","mov","avi","wmv"];
                    $get_mimes=\File::extension($image->file)
                @endphp
                @if (in_array($get_mimes , $image_extensions))
                    <div class="item">
                        <img style="width: 100px; height: 100px" src="{{ getS3File($image->file) }}" class="img-fluid" alt="image">
                    </div>
                @endif
                @if (in_array($get_mimes , $video_extensions))
                    <div class="item">
                        <video id="myvideo" preload="none" width="100" height="100" type="video/{{ $get_mimes }}" muted
                               controls>
                            <source src="{{ getS3File($image->file) }}">
                        </video>
                    </div>
                @endif
        @empty
            <span>No Files!</span>
        @endforelse
    @endif

</div>
