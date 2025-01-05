<div class="{{($sections->count() >= 3)? 'our-team-wraper':'our-team-wraper-mini'}}">
    <!-- our team-->
    <div class="container-fluid container-width">
        <div class="moon-stars-lottie">
            <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_y2vu7tdn.json" speed="1" loop autoplay></lottie-player>
        </div>
        <div class="d-flex flex-column justify-content-center align-items-center team-title">
            <div class="d-flex justify-content-center align-items-center">
                <h2 class="our-team-h">{{__('app.our-team')}}</h2>
            </div>
            <div class="moving-star">
                <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_8ijhsyoi.json" background="transparent" speed="0.8" loop autoplay></lottie-player>
            </div>
        </div>
    </div>
    @php
    $sizzling = ['assets/home/images/blue-zig.png','assets/home/images/green-zig-min.png','assets/home/images/red-zig.svg'];
    $sliderName = ['blue-slider','green-slider','red-slider'];
    $textColor = ['text-blue','text-green','text-red'];
    $ourTeam = ['first','second','third'];
    // dd($sections);
    @endphp
    @foreach ($sections as $key=>$val)
    @php

    $query = [];
    $query = getQuery(App::getLocale(), ['name','designation','short_description']);
    $query[] = 'image';
    $query[] = 'id';
    $empSection = App\Models\Admin\EmployeeSection::select($query)->where('status', 1)->where('section_id',$val->id)->get();
    @endphp
    <section class="home-team  {{($sections->count() >= 3)? 'our-team '.$ourTeam[$key].'':'our-team-mini'}}">
        <div class="container-fluid container-width">
            <div class="d-flex justify-content-center align-items-center">
                <h4 class=" managment-heading {{$textColor[$key]}}">
                    {{$val->name}}
                </h4>
            </div>
        </div>
        <div class="our-team-slider {{$sliderName[$key]}}">
            <div id="owl-one" class="owl-carousel owl-theme">
                @foreach($empSection as $empKey=>$empVal)
                <a href="{{url('employee-details?id='.hashEncode($empVal->id).'')}}">
                    <div class="slider-item ">
                        <div class="slider-zigzag">
                            <img loading="lazy" class="simple-image" src="{{asset('assets/home/images/Image-bg.png')}}" />
                            <img loading="lazy" class="blue-hover-image" src="{{asset($sizzling[$key])}}" />
                            <div class="staric-image">
                                <img loading="lazy" src="{{ getS3File($empVal->image) }}" alt="image not found" class="img-fluid" />
                            </div>
                        </div>
                        <div class="short-desription">
                            <div class="naming-tag">
                                <p>{{$empVal->designation}}</p>
                            </div>
                            <h5>{{$empVal->name}}</h5>
                            <p class="text-center mt-1">{{$empVal->short_description}}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endforeach
</div>
