<div class="file-manager-container file-manager-col-view ">
    @forelse($libraryAlbumDetails as $key=>$val)
        <br>
        <div class="file-item mt-2" id="box_{{$val->id}}" >
            <div class="file-item-select-bg bg-primary"></div>
            <label class="file-item-checkbox custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input check-boxes" data_dir_type="files" data-id="remove_{{$val->id}}" />
                <span class="custom-control-label"></span>
            </label>
            <div >
            @php
            $extension = pathinfo($val->file, PATHINFO_EXTENSION);
            @endphp
                {{-- image cse channge only  --}}
                @if($val->libraryAlbum->type_id == 1)
                    @if(empty($val->img_thumb_nail))
                    <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($val->file)}}">
                        <div class="file-item-img" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($val->file)}}" data-attr="{{$val->id}}" style="background-image: url({{getS3File($val->file)}});"></div>
                    </div>
                    @else
                        <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($val->file)}}">
                            <div class="file-item-img" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($val->file)}}" data-attr="{{$val->id}}" style="background-image: url({{getS3File($val->img_thumb_nail)}});"></div>
                        </div>
                    @endif
                @else
                    @if(empty($val->img_thumb_nail))
                        <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($val->file)}}">
                            <div class="file-item-img" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($val->file)}}" data-attr="{{$val->id}}" style="background-image: url({{getS3File($libraryType->icon)}});"></div>
                        </div>
                    @else
                        <div class="{{ $extension=='pdf' ? '_df_custom' : '' }}" backgroundcolor="teal" href="#" source="{{getS3File($val->file)}}">
                            <div class="file-item-img" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($val->file)}}" data-attr="{{$val->id}}" style="background-image: url({{getS3File($val->img_thumb_nail)}});"></div>
                        </div>
                    @endif
                @endif
                <a href="javascript:void(0)" class="file-item-name">
                    {{$val->title_english}}
                </a>
                <div class="file-item-changed">{{$val->created_at}}</div>
            </div>
            <div class="file-item-actions btn-group">
                <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="files" id="remove_{{$val->id}}" onclick="removeDir($(this))"    >Remove</a>
                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='files' onclick="getDirDetails($(this))" >Advance</a>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>