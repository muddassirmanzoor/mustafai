
<div class="file-item directories" id="box_{{$row->id}}" data_last_id={{$row->id}} >

    <div class="file-item-select-bg bg-primary"></div>
    <label class="file-item-checkbox custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input check-boxes" data_dir_type="folder" data-id="remove_{{$row->id}}" />
        <span class="custom-control-label"></span>
    </label>
    <div data_libType="{{$row->type_id}}" data_parent_id={{$row->id}}  onclick="openDirectory($(this))">

        <div class="file-item-icon far fa-folder text-secondary"></div>
        <a href="javascript:void(0)" class="file-item-name">
            {{$row->title_english}}
        </a>
        <div class="file-item-changed">{{$row->created_at}}</div>
    </div>
    <div class="file-item-actions btn-group">
        <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="javascript:void(0)" data_libType="{{$row->type_id}}" data_parent_id={{$row->id}} onclick="openDirectory($(this))">Open Directory</a>
            <a class="dropdown-item" href="javascript:void(0)" data_dir_type="folder" id="remove_{{$row->id}}"  onclick="removeDir($(this))">Remove</a>
            <a class="dropdown-item" href="javascript:void(0)" data_libid="{{$row->id}}" data_typedir='{{$row->directory_type}}' onclick="getDirDetails($(this))">Advance</a>
        </div>
    </div>
</div>