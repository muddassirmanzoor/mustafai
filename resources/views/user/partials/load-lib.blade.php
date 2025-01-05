
@if(count($libdetails))
    @foreach($libdetails as $key => $result)

        <div class="lib-data-dynamically l--1 {{isset($_GET['guestPage'])? 'col-xxl-2 col-xl-3 col-md-4 col-sm-6 mb-xl-0 mb-3 mt-3' : 'col-xxl-3  col-lg-4 col-md-6 col-sm-6 mb-xl-0 mb-3 mb-3 mt-lg-3' }}  ">
            <a href="{{url('album?id='.hashEncode($result->id).'&type_id='.encodeDecode($result->type_id).'')}}">
                <div class="image-galary" data-last-id="{{$result->id }}" data-type="{{$result->type_id}}" id="librayview_{{$loop->iteration}}">
                    @if(empty($result->img_thumb_nail))
                        <img src="{{ getS3File($result->libraryType->icon) }}"  alt="image not found" class="img-fluid" />
                    @else
                        <img src="{{ getS3File($result->img_thumb_nail) }}"  alt="image not found" class="img-fluid" />

                    @endif
                        <p style="display: none">{{$result->description}}</p>
                        <div class="seacrh-btn"    data-val='img' data-id="{{$result->id}}" data-src="{{ getS3File($result->file) }}">
                            <span class="seach-hover"> <i class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                </div>
            </a>
            <div class="d-flex justify-content-center align-items-center mt-1">
                <p class="py-2 text-center m-0 text-green">{{$result->{'title_'.App::getLocale()} }}</p>
            </div>
            <div class="show-content-lib d-flex justify-content-center align-items-center">
                <a href="javascript:void(0)" class="green-hover-bg theme-btn" data-description="{{$result->content}}" onclick="showDesciption($(this))">{{__('app.details')}}</a>
            </div>
        </div>

    @endforeach
@endif
