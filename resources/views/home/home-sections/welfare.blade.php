<!--wellfare -->
<section class="wellfare">
    <div class="container-fluid container-width">
        <div class="absolute-dome-image">
            <img loading="lazy" src="{{asset('assets/home/images/welfare-sec.png')}}" class="img-fluid" />
        </div>
        <div class="row well-fare-content">
            {{-- <div class="col-lg-4">
                <div class="well-text-content">
                    <h2>{{ __('app.welfare-title') }}</h2>
                    <div class="d-flex">
                        <a class="green-hover-bg theme-btn mt-5" href="{{ URL('about-us') }}">{{__('app.support-us')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="foundations">
                    <div class="row justify-content-lg-start justify-content-center align-items-lg-start align-items-center">
                        @php $colors = ['green','blue','red']; @endphp
                        @foreach($pages as $key => $page)
                        <div class="col-lg-4 col-md-6 mt-lg-0 mt-5">
                            <div class="{{$colors[$key]}}-box-bg fondation-common-bg al-mustafa-fondation m-{{$colors[$key]}}-box">
                                <div class="f-logo {{$colors[$key]}}-bg">
                                    <img loading="lazy" src="{{ (isset($page->image) && !empty($page->image)) ? asset($page->image) : asset('assets/home/images/al-mustafa-logo.png') }}" class="img-fluid" />
                                </div>
                                <div class="f-content">
                                    <h4 class="text-{{$colors[$key]}}">{{ $page->title }}</h4>
                                    <p>{{ $page->short_description }}</p>
                                    <div class="d-flex justify-content-center align-items-center r-more">
                                        <a href="{{URL($page->url)}}" class="mt-3 text-center text-white">{{__('app.read-more')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
           
            {{-- <div class="col-lg-12">
                <div id="foundations-carousel" class="owl-carousel owl-theme">
                    <div class="foundations w-100">
                       
                    </div>
                </div>
            </div> --}}
            <div  id="foundations-carousel"  class="owl-carousel">
                    @php $colors = ['green','blue','red','blue','red']; @endphp
                    @foreach($pages as $key => $page)
                    <div class="foundations-slides">
                        <div class="{{$colors[$key]}}-box-bg fondation-common-bg al-mustafa-fondation m-{{$colors[$key]}}-box">
                            <div class="f-logo {{$colors[$key]}}-bg">
                                <img loading="lazy" src="{{ (isset($page->image) && !empty($page->image)) ? getS3File($page->image) : asset('assets/home/images/al-mustafa-logo.png') }}" class="img-fluid" />
                            </div>
                            <div class="f-content">
                                <h4 class="text-{{$colors[$key]}}">{{ $page->title }}</h4>
                                <p>{{ $page->short_description }}</p>
                                <div class="d-flex justify-content-center align-items-center r-more">
                                    <a href="{{URL($page->url)}}" class="mt-3 text-center text-white">{{__('app.read-more')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                {{-- <div class="card"> Your Content </div>
                <div class="card"> Your Content </div>
                <div class="card"> Your Content </div>
                <div class="card"> Your Content </div>
                <div class="card"> Your Content </div>
                <div class="card"> Your Content </div> --}}
              </div>
        </div>
    </div>
</section>
