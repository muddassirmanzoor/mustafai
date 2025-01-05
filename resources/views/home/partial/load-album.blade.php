@if($typDir=='folder-div')
    @forelse($childAlbums as $key=>$result)
    <div class="lib-data-dynamically col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-xl-3 mb-2 folder-div"  data-parent-id={{$result->parent_id}} data-last-id="{{$result->id }}" data-type="{{$result->type_id}}" id="librayview_{{$loop->iteration}}">
        <a href="{{url('album?id='.hashEncode($result->id).'&type_id='.encodeDecode($result->type_id).'')}}">
                <div class="folder">
                    <div class="folder-inside" style="background: url({{empty($result->img_thumb_nail)?getS3File($result->libraryType->icon):getS3File($result->img_thumb_nail)}}) ;">
                    </div>
                </div>
                <p class="text-center mt-5"> {{$result->title}}</p>
        </a>
        <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
            <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
        </div>
    </div>
    @empty
    @endforelse

@else

    @forelse($albumDetails as $key=>$result)
    <div class="lib-data-dynamically l--3 @guest col-xxl-2 col-xl-3 col-md-4 col-sm-6 col-sm-4  image-setter-lib @endguest  @auth col-xl-4 col-sm-6 @endauth mb-xl-3 mb-2 files-div" data-parent-id="{{$result->libraryAlbum->id}}" data-last-id="{{$result->id}}" data-type="{{$result->libraryAlbum->type_id}}" id="librayviewfiles_{{$loop->iteration}}">
        {{-- <a href="{{url('album?id='.hashEncode($result->id))}}"> --}}

            <div class="image-galary gallerimage-wraper"  data-src="{{getS3File($result->file)}}" data-attr="{{$result->id}}">
                @php
                $extension = pathinfo($result->file, PATHINFO_EXTENSION);
                @endphp
                @if($result->libraryAlbum->type_id == 1)
                            @if(empty($result->img_thumb_nail))
                            <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" href="#" backgroundcolor="teal" source="{{getS3File($result->file)}}">
                                <img src="{{getS3File($result->file)}}"   data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                            </div>
                            @else
                            <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" href="#" backgroundcolor="teal" source="{{getS3File($result->file)}}">
                                <img src="{{getS3File($result->img_thumb_nail)}}"  data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                            </div>
                            @endif
                @else
                    @if(empty($result->img_thumb_nail))
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" href="#" backgroundcolor="teal" source="{{getS3File($result->file)}}">
                        <img src="{{ getS3File($result->libraryAlbum->libraryType->icon) }}"   data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @else
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" href="#" backgroundcolor="teal" source="{{getS3File($result->file)}}">
                        <img src="{{ getS3File($result->img_thumb_nail) }}"  data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @endif
                @endif
                    {{-- @if(empty($result->img_thumb_nail))
                        <img src="{{ getS3File($result->libraryAlbum->libraryType->icon) }}" onclick="getlibApiPreviwer($(this))" data-src="https://flowpaper.com/flipbook/{{asset($result->file)}}" data-attr="{{$result->id}}" alt="image not found" class="img-fluid" />
                    @else
                        <img src="{{ asset($result->img_thumb_nail) }}" onclick="getlibApiPreviwer($(this))" data-attr="{{$result->id}}" data-src="https://flowpaper.com/flipbook/{{asset($result->file)}}"  alt="image not found" class="img-fluid" />

                    @endif --}}
                    <p style="display: none"></p>
                    {{-- maria design code  --}}
                    <div class="share-file-btn" onclick="shareLibLink($(this))" data-link="{{ url("share-post?id=".encodeDecode($result->id)." ") }}" >
                        <span class="share-file"><i class="fa fa-share-alt" aria-hidden="true"></i></span>
                    </div>
                    {{-- maria design code  --}}

                        <div class="seacrh-btn" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($result->file)}}" data-attr="{{$result->id}}">
                            <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                                <span class="seach-hover"> <i class="fa fa-search" aria-hidden="true"></i></span>
                            </div>
                        </div>
               </div>
            {{-- </a> --}}
            <div class="d-flex justify-content-center align-items-center mt-1">
                <p class="text-center"> {{$result->title}}</p>

            </div>
            <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
                <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
            </div>

    </div>
    @empty
    @endforelse


@endif
