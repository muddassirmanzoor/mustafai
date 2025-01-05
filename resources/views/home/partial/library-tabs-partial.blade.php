@if(count($results))
<div class="col-12 mb-2">
    <div class="row">
        <div class="col-12">
            <p class="m-2">
                {!! $libratype->content !!}
            </p>
        </div>
    </div>
</div>
@foreach($results as $key => $result)

<div class="lib-data-dynamically l--4 col-xxl-2 col-xl-3 col-md-4 col-sm-6 mb-xl-0 mb-3 mt-3">
    @if(isset($empId))
    <a href="{{url('album?empId='.$empId.'&id='.hashEncode($result->id))}}">
        @else
        <a href="{{url('album?id='.hashEncode($result->id).'&type_id='.encodeDecode($result->type_id).'')}}">
            @endif
            <div class="image-galary" data-last-id="{{$result->id }}" data-type="{{$type}}" id="librayview_{{$loop->iteration}}">
                @if(empty($result->img_thumb_nail))
                    <img src="{{ getS3File($result->libraryType->icon) }}" alt="image not found" class="img-fluid" />
                @else
                    <img src="{{ getS3File($result->img_thumb_nail) }}" alt="image not found" class="img-fluid" />
                @endif
                <p style="display: none">{{$result->description}}</p>

                <div class="seacrh-btn" data-val='img' data-id="{{$result->id}}" data-src="{{ getS3File($result->file) }}">
                    <span class="seach-hover"> <i class="fa fa-search" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center mt-1 naming-title">
                <div class="text-center">
                    <p class="py-2 text-green">{{$result->title}}</p>
                    <div class="show-content-lib mt-2 d-flex justify-content-center align-items-center">
                        <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
                    </div>
                </div>
            </div>

        </a>
</div>
@endforeach

@else
<div class="col-lg-12  mb-lg-0 mb-3 mt-lg-3 d-flex justify-content-center align-items-center no-data-lib">
    <h6>{{__('app.no-data-available')}} </h6>
</div>
@endif
