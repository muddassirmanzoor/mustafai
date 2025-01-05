@extends('home.layout.app')
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper ">
    <section class="container">
        <div class="d-flex justify-content-center align-items-center contact-heading">
            <h2>{{ __('app.testimonials') }}</h2>
        </div>
        <div class="row ll-testimonials">
            @forelse ($testimonials as $key=>$val)
            <div class="list-testimonials d-flex flex-md-row flex-column">
                <div class="col-md-2">
                    <img src="{{getS3File($val->image)}}" >
                </div>
                <div class="col-md-10">
                    <div class="testi-datetext">
                        <h6>{{$val->name}}</h6>
                        <div class="testimonail-datelist">{{ !empty($val->date_time)? date("d-m-Y",strtotime($val->date_time)) : '' }}</div>

                    </div>
                    <span class="f-size-14 testti-tile">{{ $val->title }}</span>
                        <p>{!!$val->message!!}</p>
                        
                        @if(!empty($val->link))
                            <div class="col-md-12 mt-2 testiomnial-link-details-page">
                                <a  href="{{$val->link}}" target="_blank" rel="noopener noreferrer">
                                    <Button class="btn btn-primary">{{__('app.testimonial-link-details')}}</Button>
                                </a>
                      
                            </div>
                        @endif
                </div>
            
            </div>
            @empty
            <p> {{ __('app.no-data') }} </p>
            @endforelse
        </div>
    </section>
</div>
@include('home.home-sections.get-in-touch')
@endsection
