<div class="csm-pages-wraper ">
    @php
        $albumCounter = 0 ;
    @endphp
    <ol class="breadcrumb text-big container-p-x py-3 m-0" id="">
        <li class="breadcrumb-item"> <a href="{{url('/employee-details?id='.$empId.'')}}">{{__('app.home')}}</a> </li>
        @foreach($breadCrums as $key=>$val)
            <li class="breadcrumb-item"> <a href="javascript:void(0)" >{{$val}}</a> </li>
        @endforeach
    </ol>
    <div class="row dynamic-album-row justify-content-center">
        <div class="gallery-headng mb-4">
            <h2>{{__('app.files')}}</h2>
        </div>
        @forelse($albumDetails as $key=>$result)
        @if($loop->iteration == 1)
            @php  $albumCounter=$result->libraryAlbum->libraryAlbumDetails->count() @endphp
        @endif
        <div class="lib-data-dynamically col-xl-4 col-sm-6 mb-xl-0 mb-3 files-div" data-parent-id="{{$result->libraryAlbum->id}}" data-last-id="{{$result->id}}" data-type="{{$result->libraryAlbum->type_id}}" id="librayviewfiles_{{$loop->iteration}}">
            <div class="image-galary gallerimage-wraper" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($result->file)}}" data-attr="{{$result->id}}">
                @php
                    $extension = pathinfo($result->file, PATHINFO_EXTENSION);
                @endphp
                @if($result->libraryAlbum->type_id == 1)
                    @if(empty($result->img_thumb_nail))
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                        <img src="{{getS3File($result->file)}}"  onclick="getlibApiPreviwer($(this))" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @else
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                        <img src="{{getS3File($result->img_thumb_nail)}}" onclick="getlibApiPreviwer($(this))" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @endif
                @else
                    @if(empty($result->img_thumb_nail))
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                        <img src="{{ getS3File($result->libraryAlbum->libraryType->icon) }}"  onclick="getlibApiPreviwer($(this))" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @else
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($result->file)}}">
                        <img src="{{ getS3File($result->img_thumb_nail) }}" onclick="getlibApiPreviwer($(this))" data-attr="{{$result->id}}" data-src="{{getS3File($result->file)}}" alt="image not found" class="img-fluid" />
                    </div>
                    @endif
                @endif
                <p style="display: none"></p>
                <div class="seacrh-btn">
                    <span class="seach-hover"> <i class="fa fa-search" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-1">
                <p class="text-center"> {{$result->title}}</p>
            </div>
            <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
                <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
            </div>
        </div>
        @empty
         <p class="text-center">{{__('app.no-data-available')}}</p>
        @endforelse
        @if($albumCounter > 6)
        <div class="d-flex justify-content-center align-items-center mt-3">
            <button type="button" id="load-lib" class="theme-btn-borderd-btn theme-btn text-inherit" onclick="viewMore('files-div')">{{ __('app.view-more') }}</button>
        </div>
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
<div class="modal fade" id="detail-dir-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="qt_wrap d-flex flex-column" style="" id="link_embed">
                        <label>Paste Link</label>
                        <input type="text"  class="form-control"  id="link_upload" placeholder="Please Paste Youtube Link Here" autocomplete="off">
                        <button class="btn btn-small btn-primary mt-2" onclick="uploadLink()">Upload Link</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
