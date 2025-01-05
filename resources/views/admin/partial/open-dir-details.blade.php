<div class="container-fluid flex-grow-1 light-style container-p-y">
    <div class="container-m-nx container-m-ny bg-lightest mb-3">


    @if(!empty($parentRow))
        @if(session()->has('breadcrumb-item'))
            @php
                if(!empty($dataBack)){
                    $myArrayInit = Session::get('breadcrumb-item'); //<-- Your actual array
                    $offsetKey = $dataBack; //<--- The offset you need to grab
                    //Lets do the code....
                    $n = array_keys($myArrayInit); //<---- Grab all the keys of your actual array and put in another array
                    $count = array_search($offsetKey, $n); //<--- Returns the position of the offset from this array using search
                    $new_arr = array_slice($myArrayInit, 0, $count + 1, true);//<--- Slice it with the 0 index as start and position+1 as the length parameter.
                    Session::put('breadcrumb-item', $new_arr);
                }else{
                    $counter=Session::get('counter') + 1 ;
                    $bCrum=Session::get('breadcrumb-item');
                    $bCrum[$counter] = '<li class="breadcrumb-item"> <a href="javascript:void(0)" data_parent_id='.$parent_id.' data_back="'.$counter.'" data_libType='.$libType.' onclick="openDirectory($(this))" class="text-capitalize">'.$parentRow->title_english.'</a></li>';
                    Session::put('breadcrumb-item', $bCrum);
                    Session::put('counter', $counter);
                }
            @endphp
        @else
            @php
                $breadCrumArray = [];
                $breadCrumArray[0]= '<li class="breadcrumb-item"> <a href="'.url('admin/dashboard').'">Home</a> </li>';
                $breadCrumArray[1]= '<li class="breadcrumb-item"> <a href="'.url('admin/library/' . $libraryType->id . '/edit').'" class="text-capitalize">'.$libraryType->title_english.'</a> </li>';
                $breadCrumArray[2]= '<li class="breadcrumb-item"> <a href="javascript:void(0)" data_parent_id='.$parent_id.' data_back="2" data_libType='.$libType.' onclick="openDirectory($(this))" class="text-capitalize">'.$parentRow->title_english.'</a></li>';
                Session::put('counter', 2);
                Session::put('breadcrumb-item', $breadCrumArray);
            @endphp
        @endif
    @endif
        <ol class="breadcrumb text-big container-p-x py-3 m-0" id="">
            @foreach(Session::get('breadcrumb-item') as $key=>$val)
                {!!$val!!}
            @endforeach

        </ol>

        <hr class="m-0" />
        <input type="hidden" class="form-control" name="type_id" id="library_type" value="{{$libType}}" >
        <input type="hidden" value="{{$parent_id}}" name="parent_id" id="parent_id">
        <input type="hidden" id="libAlbumId" value="{{$parent_id}}" >
        <div  style="display: none" class="progress mt-3" style="height: 25px">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
        </div>
        <div class="file-manager-actions container-p-x py-2">
            <div>
                @if($libraryType->id == 1 && have_right('Create-File-Image-Gallery'))
                        <input type="file" class="upload d-none"  multiple class="form-control"  id="browseFile" onchange="uploadFile()">
                        <button type="button" class="btn btn-primary mr-2" id="upload-file" onclick='(document.getElementById("browseFile").click())'><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload file</button>
                    @elseif($libraryType->id == 2 && have_right('Create-File-Video-Gallery'))
                        <input type="file" class="upload d-none"  multiple class="form-control"  id="browseFile" onchange="uploadFile()">
                        <button type="button" class="btn btn-primary mr-2" id="upload-file" onclick='(document.getElementById("browseFile").click())'><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload file</button>
                    @elseif($libraryType->id == 3 && have_right('Create-File-Audio-Gallery'))
                        <input type="file" class="upload d-none"  multiple class="form-control"  id="browseFile" onchange="uploadFile()">
                        <button type="button" class="btn btn-primary mr-2" id="upload-file" onclick='(document.getElementById("browseFile").click())'><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload file</button>
                    @elseif($libraryType->id == 4 && have_right('Create-File-Book-Gallery'))
                        <input type="file" class="upload d-none"  multiple class="form-control"  id="browseFile" onchange="uploadFile()">
                        <button type="button" class="btn btn-primary mr-2" id="upload-file" onclick='(document.getElementById("browseFile").click())'><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload file</button>
                    @elseif($libraryType->id == 5 && have_right('Create-File-Document-Gallery'))
                        <input type="file" class="upload d-none"  multiple class="form-control"  id="browseFile" onchange="uploadFile()">
                        <button type="button" class="btn btn-primary mr-2" id="upload-file" onclick='(document.getElementById("browseFile").click())'><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload file</button>
                    @else
                @endif

                @if($libraryType->id == 1 && have_right('Create-Album-Image-Gallery'))
                        <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid={{$parent_id}} onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                    @elseif($libraryType->id == 2 && have_right('Create-Album-Video-Gallery'))
                        <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid={{$parent_id}} onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                    @elseif($libraryType->id == 3 && have_right('Create-Album-Audio-Gallery'))
                        <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid={{$parent_id}} onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                    @elseif($libraryType->id == 4 && have_right('Create-Album-Book-Gallery'))
                        <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid={{$parent_id}} onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                    @elseif($libraryType->id == 5 && have_right('Create-Album-Document-Gallery'))
                        <button type="button" class="btn btn-warning mr-2" id="create-album" data_libtype={{$id}} data_dirtype='0' data_parentid={{$parent_id}} onclick="createdirectory($(this))"><i class="ion ion-md-cloud-upload"></i>&nbsp; Create Album</button>
                    @else
                @endif

                @if($id == 2 && have_right('Create-File-Video-Gallery'))
                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#detail-dir-modal-youtube"><i class="ion ion-md-cloud-upload"></i>&nbsp; Upload Link</button>
                @endif{{-- <button type="button" class="btn btn-secondary icon-btn mr-2" disabled=""><i class="ion ion-md-cloud-download"></i></button> --}}
                    <div class="btn-group mr-2">
                        <button type="button" class="btn btn-default md-btn-flat dropdown-toggle px-2" data-toggle="dropdown"><i class="ion ion-ios-settings"></i></button>
                        @if($libraryType->id == 1 && have_right('Remove-Image-Gallery'))
                         <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="removeDir($(this),'all')">Remove Selected</a>
                         </div>
                         @elseif($libraryType->id == 2 && have_right('Remove-Video-Gallery'))
                            <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="removeDir($(this),'all')">Remove Selected</a>
                            </div>
                         @elseif($libraryType->id == 3 && have_right('Remove-Audio-Gallery'))
                            <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="removeDir($(this),'all')">Remove Selected</a>
                            </div>
                         @elseif($libraryType->id == 4 && have_right('Remove-Book-Gallery'))
                            <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="removeDir($(this),'all')">Remove Selected</a>
                            </div>
                         @elseif($libraryType->id == 5 && have_right('Remove-Document-Gallery'))
                            <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="removeDir($(this),'all')">Remove Selected</a>
                            </div>
                         @endif
                    </div>
            </div>
            <div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons" style="display: none;">
                    <label class="btn btn-default icon-btn md-btn-flat active"> <input type="radio" name="file-manager-view" value="file-manager-col-view" checked="" /> <span class="ion ion-md-apps"></span> </label>
                    <label class="btn btn-default icon-btn md-btn-flat"> <input type="radio" name="file-manager-view" value="file-manager-row-view" /> <span class="ion ion-md-menu"></span> </label>
                </div>
            </div>
        </div>
        {{-- @if ($libraryType->id == 4 || $libraryType->id == 5)
            <div>
                <small class="d-block text-muted  mt-1 mb-1">You can only add file size less than <span class="text-danger"> 16MB </span></small>
            </div>
        @endif --}}
        <hr class="m-0" />
    </div>
    {{-- <h5>Albums</h5> --}}
    <div class="file-manager-container file-manager-col-view">
        <div class="file-manager-row-header">
            <div class="file-item-name pb-2">Filename</div>
            <div class="file-item-changed pb-2">Changed</div>
        </div>

        <div class="file-item back_directory d-none" data_parent_id={{$parent_id}} data_libType={{$id}}  onclick="openDirectory($(this),'back')">
            <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
            <a href="javascript:void(0)" class="file-item-name"  data_parent_id={{$parent_id}} data_libType={{$id}}  onclick="openDirectory($(this),'back')">
                ..
            </a>
        </div>
        @forelse($row as $key=>$val)
            <div class="file-item directories"  id="box_{{$val->id}}" >
                <div class="file-item-select-bg bg-primary"></div>
                <label class="file-item-checkbox custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input check-boxes" data_dir_type="folder" data-id="remove_{{$val->id}}" />
                    <span class="custom-control-label"></span>
                </label>
                <div data_libType="{{$val->type_id}}" data_parent_id={{$val->id}} onclick="openDirectory($(this))">

                    <div class="file-item-icon far fa-folder text-secondary"></div>
                    <a href="javascript:void(0)" class="file-item-name">
                        {{$val->title_english}}
                    </a>
                    <div class="file-item-changed">{{$val->created_at}}</div>
                </div>
                <div class="file-item-actions btn-group">
                    <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="javascript:void(0)" data_libType="{{$val->type_id}}" data_parent_id={{$val->id}} onclick="openDirectory($(this))">Open Directory</a>
                            @if($libraryType->id == 1 && have_right('Remove-Image-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                @elseif($libraryType->id == 2 && have_right('Remove-Video-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                @elseif($libraryType->id == 3 && have_right('Remove-Audio-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                @elseif($libraryType->id == 4 && have_right('Remove-Book-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                @elseif($libraryType->id == 5 && have_right('Remove-Document-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$val->id}}"  onclick="removeDir($(this))">Remove</a>
                                @else
                            @endif
                            @if($libraryType->id == 1 && have_right('Update-Image-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                @elseif($libraryType->id == 2 && have_right('Update-Video-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                @elseif($libraryType->id == 3 && have_right('Update-Audio-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                @elseif($libraryType->id == 4 && have_right('Update-Book-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                @elseif($libraryType->id == 5 && have_right('Update-Document-Gallery'))
                                    <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$val->id}}" data_typedir='0' onclick="getDirDetails($(this))">Advance</a>
                                @else
                            @endif
                        </div>

                </div>
            </div>
        @empty
        @endforelse


    </div>
    {{-- for file  --}}
    <hr>
    <div class="file-manager-container file-manager-col-view ">
        @php
            // if($libraryType->id == 1){
            //      $dynamiclist ='<ul id="images"><input type="hidden" id="fileType" value="image">';
            // }elseif($libraryType->id == 2){
            //     $dynamiclist ='<input type="hidden" id="fileType" value="video">';

            // }elseif($libraryType->id == 3){
            //     $dynamiclist ='<input type="hidden" id="fileType" value="audio">';

            // }elseif($libraryType->id == 4 || $libraryType->id== 5){
            //     $dynamiclist ='<input type="hidden" id="fileType" value="document">';
            // }

        @endphp
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
        <div class="dynamic_load_data">

        </div>
        @if ($libraryAlbumDetails->count() >= 21)
            <div class="d-flex flex-column justify-content-center align-items-center load-more-sections w-100 my-3" id="load-more-sec" style="visibility:visible;">
                <button type="button" id="load-more-lib" class="btn btn-danger justify-content-center align-items-center" onclick="loadMore()">View More</button>
            </div>
        @endif
        @if($libraryType->id == 1)
        <ul id="images" class="images d-none"><input type="hidden" id="fileType" value="image">
            @forelse($libraryAlbumDetails as $key=>$result)
                <li><img src="{{getS3File($result->file)}}" class="images2" id="dynamic_{{$result->id}}" alt="Picture 1" loading="lazy"></li>
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

<div class="modal fade " id="detail-dir-modal-youtube" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-body  ">
            <div class="row">
                <div class="col-md-12">
                    <div class="qt_wrap d-flex flex-column" id="link_embed">
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

<div class="modal fade " id="detail-dir-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class=" ">
            {{-- <div class=" d-flex justify-content-end libray-modal-btn-close">
                <button data-target="#detail-dir-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn-close"></button>
            </div> --}}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="qt_wrap d-flex flex-column" id="link_embed">
                            <label>Paste Link</label>
                            <input type="text"  class="form-control"  id="link_upload" placeholder="Please enter valid youtube or goggle drive link" autocomplete="off">
                            <button class="btn btn-small btn-primary mt-2" onclick="uploadLink()">Upload Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade " id="detail-dir-modal-video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg yutube-modal-lg">
      <div class="modal-content videomodel-content common-model-style">
        <div class="modal-body  ">
        </div>
      </div>
    </div>
</div>
