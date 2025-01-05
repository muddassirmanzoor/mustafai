@extends('home.layout.app')
@section('content')
@include('home.home-sections.banner')

<div class="csm-pages-wraper">
    <!-- our team-->
    <section class="mustafai-managment-pg">
        <div class="container-fluid container-width">

            @php
            $sizzling = ['assets/home/images/blue-zig.svg','assets/home/images/green-zig.svg','assets/home/images/red-zig.svg'];
            $sliderName = ['blue-slider','green-slider','red-slider'];
            $textColor = ['text-blue','text-green','text-red'];
            $ourTeam = ['first','second','third'];
            @endphp
            @foreach ($sections as $key=>$val)
            @php

            $query = [];
            $query = getQuery(App::getLocale(), ['name','designation','short_description']);
            $query[] = 'image';
            $empSection = App\Models\Admin\EmployeeSection::select($query)->where('status', 1)->where('section_id',$val->id)->get();
            @endphp
            <div class="central-body-managment">
                <div class="d-flex flex-column justify-content-center align-items-center team-title">
                    <h3 class="@if(!$key ==0) d-none @endif">{{ __('app.mustafai-management') }}</h3>
                    <h4 class="sub-hedding text-red">{{$val->name}}</h4>
                </div>
                <div class="row red-slider manage-gap">
                    @foreach($empSection as $empKey=>$empVal)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="slider-item">
                            <div class="slider-zigzag">
                                <img class="simple-image" src="{{asset('assets/home/images/Image-bg.svg')}}" />
                                <img class="blue-hover-image" src="{{asset('assets/home/images/red-zig.svg')}}" />
                                <div class="staric-image">
                                    <img src="{{getS3File($empVal->image)}}" alt="image not found" class="img-fluid" />
                                </div>
                            </div>
                            <div class="naming-tag d-flex  justify-content-center align-items-center">
                                <p class="d-flex">{{$empVal->designation}}</p>
                            </div>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <h5>{{$empVal->name}}</h5>
                                <p class="d-flex text-center">{{$empVal->short_description}}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- <div class="mg-social-links d-flex">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" class="facebook">
                                    <span class="fa fa-facebook"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="twitter">
                                    <span class="fa fa-twitter"></span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>

            </div>
            @endforeach

            <div class="bottom-img-copy d-absolute-bottom-img">
                <img src="./images/d-bottom-img.png" alt="image not found" class="img-fluid" />
            </div>
        </div>
    </section>
</div>
@include('home.home-sections.get-in-touch')
@endsection
