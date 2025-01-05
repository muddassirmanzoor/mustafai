<div class="{{Auth::check() ? 'user-album-lib': 'csm-pages-wraper home-lib-wraper'}}  test-page" id="main_div_partial">


    <ol class="breadcrumb text-big container-p-x py-3 m-0" id="">
        @if(!Auth::check() )
            <li class="breadcrumb-item">
                <a href="{{url('/')}}">{{__('app.home')}}</a>
            </li>
        @else
            <li class="breadcrumb-item">
                <a href="{{url('/user/user-library')}}">{{__('app.home')}}</a>
            </li>
        @endif
        @foreach($breadCrums as $key=>$val)
        <li class="breadcrumb-item"> <a href="javascript:void(0)" onclick="openpartialAlbum('<?php echo hashEncode($key) ?>')">{{$val}}</a> </li>
        @endforeach
    </ol>

    <div class="row dynamic-album-row justify-content-center">
        @if(count($childAlbums) || count($albumDetails))
        @if (count($childAlbums))
        <div class=" mb-4">
            <h2>{{__('app.albums')}}</h2>
        </div>

        @php
        $albumCounter = 0 ;
        @endphp

        @forelse($childAlbums as $key=>$result)
        @if($loop->iteration == 1)
        @php $albumCounter=$result->getCounter() @endphp
        @endif
        @php
            $class='';
            if(auth()->user()){
                $class='lib-data-dynamically col-xl-4 col-sm-6  mb-3 folder-div';
            }
            else{
                $class='col-xxl-2 col-xl-3 col-md-4 col-sm-6 mb-3';
            }
        @endphp
        <div class="{{ $class }}" data-last-id="{{$result->id }}" data-parent-id={{$result->parent_id}} data-type="{{$result->type_id}}" id="librayview_{{$loop->iteration}}">
            <a href="javascript:void(0)" onclick="openpartialAlbum('<?php echo hashEncode($result->id) ?>')">
                <div class="folder">
                    <div class="folder-inside" style="background: url({{empty($result->img_thumb_nail)?getS3File($result->libraryType->icon):getS3File($result->img_thumb_nail)}}) ;">
                    </div>
                </div>
                <p class="text-center mt-2 text-green"> {{$result->title}}</p>
            </a>
            <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
                <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
            </div>
        </div>
        @php
        $displayNone = "";

        @endphp
        @empty
        <p class="text-center">{{__('app.no-data-available')}}</p>
        @php
        $displayNone = "d-none";
        @endphp
        @endforelse
        @if($albumCounter > 6 && !isset($searchBit))
        <div class="d-flex justify-content-center align-items-center mt-3">
            <button type="button" id="load-lib_folder-div" class="theme-btn-borderd-btn theme-btn text-inherit {{$displayNone}}" onclick="viewMore('folder-div')">{{ __('app.view-more') }}</button>
        </div>
        @endif
        <hr class="m-5">
        @endif

        {{-- files --}}
        @if (count($albumDetails))
        <div class="gallery-headng mb-4">
            <h2>{{__('app.files')}}</h2>
        </div>
        @forelse($albumDetails as $key=>$result)
        @if($loop->iteration == 1)
        @php $albumCounter=$result->libraryAlbum->libraryAlbumDetails->count() @endphp
        @endif
        <div class="lib-data-dynamically @guest col-xxl-2 col-xl-3 col-md-4 col-sm-6 col-sm-4 image-setter-lib @endguest @auth col-xl-4 col-sm-6 @endauth mb-3 files-div" data-parent-id="{{$result->libraryAlbum->id}}" data-last-id="{{$result->id}}" data-type="{{$result->libraryAlbum->type_id}}" id="librayviewfiles_{{$loop->iteration}}">
            <div class="image-galary gallerimage-wraper " data-src="{{getS3File($result->file)}}" data-attr="{{$result->id}}">
                @php
                $extension = pathinfo($result->file, PATHINFO_EXTENSION);
                @endphp
                @if($result->libraryAlbum->type_id == 1)
                @if(empty($result->img_thumb_nail))
                <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                    <img src="{{getS3File($result->file)}}" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                </div>
                @else
                <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                    <img src="{{getS3File($result->img_thumb_nail)}}" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                </div>
                @endif

                @else
                @if(empty($result->img_thumb_nail))
                <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                    <img src="{{ getS3File($result->libraryAlbum->libraryType->icon) }}" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                </div>
                @else
                <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                    <img src="{{ getS3File($result->img_thumb_nail) }}" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                </div>
                @endif
                @endif
                <p style="display: none"></p>
                {{-- maria design code  --}}
                <div class="share-file-btn" onclick="shareLibLink($(this))" data-link="{{ url("share-post?id=".encodeDecode($result->id)." ") }}">
                    <span class="share-file"><i class="fa fa-share-alt" aria-hidden="true"></i></span>
                </div>
                {{-- maria design code  --}}

                    <div class="seacrh-btn" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($result->file)}}" data-attr="{{$result->id}}">
                        <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                            <span class="seach-hover"> <i class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                    </div>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-1">
                <p class="text-center"> {{$result->title}}</p>

            </div>
            <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
                <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
            </div>
        </div>
        @php
        $displayNone = "";

        @endphp
        @empty
        <p class="text-center">{{__('app.no-data-available')}}</p>
        @php
        $displayNone = "d-none";
        @endphp
        @endforelse
        @if($albumCounter > 6 && !isset($searchBit))

        <div class="d-flex justify-content-center align-items-center mt-3">
            <button type="button" id="load-lib_files-div" class="theme-btn-borderd-btn theme-btn text-inherit {{$displayNone}}" onclick="viewMore('files-div')">{{ __('app.view-more') }}</button>
        </div>
        @endif
        @endif
        @if($libraryType->id == 1)
        <ul id="images" class="images d-none"><input type="hidden" id="fileType" value="image">
            @forelse($allchildfiles as $key=>$result)
            <li><img src="{{getS3File($result->file)}}" class="images2" id="dynamic_{{$result->id}}" loading="lazy" alt="Picture 1"></li>
            @empty
            @endforelse
        </ul>
        @elseif($libraryType->id == 2)
        <input type="hidden" id="fileType" value="video">
        @elseif($libraryType->id == 3)
        <input type="hidden" id="fileType" value="audio">
        @else
        <input type="hidden" id="fileType" value="document">
        @endif
        @else
        <p class="text-center">{{__('app.no-data-available')}}</p>
        @endif
    </div>
</div>

<div class="modal fade " id="detail-dir-modal-video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg yutube-modal-lg">
        <div class="modal-content videomodel-content common-model-style">
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<div class="modal fade common-model-style" id="detail-dir-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class=" ">
                <div class=" d-flex justify-content-end libray-modal-btn-close @auth dashboard-pdf-model-btn pdfs-file-model-btn @endauth  @guest pdfs-file-model-btn @endguest">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">

                </div>
            </div>
        </div>
    </div>
</div>
