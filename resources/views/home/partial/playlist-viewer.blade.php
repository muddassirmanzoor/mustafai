<div class="{{ ($libAlbumData->libraryAlbum->type_id == 2)? 'video-wrapper':'video-wrapper audio-wrapper'}}">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <table class="canvas-video">
        <tr>

            <td class="videoframe" id="iframe_id" rowspan="2">
                @if($libAlbumData->libraryAlbum->type_id == 2)
                    @if($libAlbumData->type_video == 1)
                        <iframe  width="800" type-data= "iframe" height="510" src="{{asset($libAlbumData->file)}}"  allowfullscreen></iframe>
                    @else
                        <video width="800"  type-data="video"  height="610" controls src="{{getS3File($libAlbumData->file)}}">

                        </video>
                    @endif
                @else
                    <div class="audio-backimgh"> <img src="{{ getS3File(empty($libAlbumData->img_thumb_nail)? $libAlbumData->libraryAlbum->libraryType->icon:$libAlbumData->img_thumb_nail) }}"/></div>
                    <audio controls  width="500" height="310" class="audio-frame">
                    <source src="{{getS3File($libAlbumData->file)}}" >
                    </audio>
                @endif
            </td>
            {{-- <td class="videoframe" rowspan="2">
                @if($libAlbumData->libraryAlbum->type_id == 2)
                   @if($libAlbumData->type_video == 1)
                    <iframe id="iframe_id" width="800" height="510" src="{{asset($libAlbumData->file)}}"  allowfullscreen></iframe>
                    @else
                    <video width="800" id="iframe_id"  height="610" controls src="{{asset($libAlbumData->file)}}">

                      </video>
            </td>
            <td class="videoframe" rowspan="2">
                    @endif
                @else
                <div class="audio-backimgh"> <img width="100" height="100" src="{{ asset(empty($libAlbumData->img_thumb_nail)? $libAlbumData->libraryAlbum->libraryType->icon:$libAlbumData->img_thumb_nail) }}"/></div>

                 <audio controls id="iframe_id" width="500" height="310" class="audio-frame">
                    <source src="{{asset($libAlbumData->file)}}" >


                  </audio>
                @endif

            </td> --}}
            <td class="titlevideo-name"> <span id="album_title">Play List Name : {{$libAlbumData->libraryAlbum->title_english}}</span>
                <p><h5 class="range mt-2" id="file_created">  {{$libAlbumData->created_at}}</h5></p>
            </td>
        </tr>
        <tr>
            <td class="videolisting" valign="middle">
                <section class="list" >
                @foreach($libAlbumDetails as $key=>$val)
                @php
                    $file_playlist='';
                    if (\Str::startsWith($val->file, 'https://www.youtube.com/embed/')) {
                        $file_playlist= $val->file;
                    } else {
                        $file_playlist=getS3File($val->file);
                    }
                @endphp
                    <p class="videoimages-wrap">. <a href="#" id="list_{{$val->id}}" data-id={{$val->id}} data-title="{{$val->title_english}}" data-src="{{$file_playlist}}" data-createdat="{{$val->created_at}}" type-data="{{($val->libraryAlbum->type_id == 3)?'audio':$val->type_video}}" data-image="{{ getS3File(empty($val->img_thumb_nail)? $val->libraryAlbum->libraryType->icon:$val->img_thumb_nail) }}" onclick="updateIframe($(this))"><img class="list_img" src="{{getS3File(empty($val->img_thumb_nail)?$val->libraryAlbum->libraryType->icon:$val->img_thumb_nail)}}" width="50"> {{$val->title_english}}</a></p><hr color="grey">

                    {{-- <p class="videoimages-wrap">. <a href="#" id="list_{{$val->id}}" data-id={{$val->id}} data-title="{{$val->title_english}}" data-src="{{asset($val->file)}}" data-createdat="{{$val->created_at}}" onclick="updateIframe($(this))"><img class="list_img" src="{{(empty($val->img_thumb_nail)?$val->libraryAlbum->libraryType->icon:$val->img_thumb_nail)}}" width="50"> {{$val->title_english}}</a></p><hr color="grey"> --}}
                @endforeach
             </section>
            </td>
        </tr>
    </table>
    <a href="" class="video-title file_tile">{{$libAlbumData->title_english}} </a>
</div>
<script>
function activeimg(obj){
   obj.addClass="active";
}
</script>
