<section class="testimonials">
    <div class="d-absolute-top-img">
        <img src="{{ asset('assets/home/images/donaters-dome.png') }}" alt="image not found" class="img-fluid" />
    </div>
    <div class="container">
        <div class="row">
            <!-- <div class="col-12">
                <div class="d-flex justify-content-center align-items-center">
                    <h3 class="text-center">DONATORS SAY</h3>
                </div>
                <div class="testimonials-slider">
                    @php
                    $uperHtml = '';
                    $lowerHtml = '';

                    foreach($testimonials as $testimonial)
                    {
                    $uperHtml .= '<div class="item">
                        <div class="image-wraper"><img src="'.asset($testimonial->image).'" alt="image not found" class="img-fluid" /></div>
                        <h4 class="py-4 text-center">'.$testimonial->name.'</h4>
                        <p class="text-center graish-color">'.$testimonial->message.'</p>
                    </div>';

                    $lowerHtml .= '<div class="item"> <img src="'.asset($testimonial->image).'" alt="image not found" class="img-fluid" /> </div>';

                    }
                    @endphp
                    <div id="sync2" class="owl-carousel owl-theme thumbanails-s">
                        {!! $lowerHtml !!}
                    </div>
                    <div id="sync1" class="owl-carousel owl-theme">
                        {!! $uperHtml !!}
                    </div>


                </div>
            </div> -->
            <div id="testimonial-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="d-flex justify-content-center align-items-center">
                                <h3 class="text-center">{{__('app.donator-says')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="testi-wrap">
                        @foreach($testimonials as $key => $testimonial)
                        <div class="client-single {{ ($key == 0) ? 'active':'inactive' }} position-{{$key+1}}" data-position="position-{{$key+1}}">
                            <div class="client-img">
                                <img src="{{getS3File($testimonial->image)}}" alt="">
                            </div>
                            <div class="content-testimonial d-none">
    
                                <div class="client-comment">
                                    <h4 class="py-4 text-center">{{$testimonial->name}}</h4>
        
                                </div>
                                <div class="client-info">
                                    <p class="text-center graish-color">{!!$testimonial->message!!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="read-more-btn text-center show-testimonial-btn">
                        <a class="green-hover-bg theme-btn ms-sm-5" href="{{ URL('all-testimonials') }}">{{ __('app.read-more') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-absolute-bottom-img">
        <img src="{{ asset('assets/home/images/d-bottom-img.png') }}" alt="image not found" class="img-fluid" />
    </div>
</section>
