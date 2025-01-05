@extends('home.layout.app')

@section('content')
    <input type="hidden" id="bg-img-blue" value="{{asset('images/blue-bg.png')}}">
    @if(!empty($page))
        <div class="csm-pages-wraper">
            <div class="container-fluid container-width">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="cms-page-title">
                        <h3 class="about-h-1 text-center">{{$page->title}}</h3>
                    </div>
                    <div class="cms-page-content">
                        {!! str_replace('src="http://127.0.0.1:8000','src="'.url('').'',$page->description)  !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="csm-pages-wraper">
            <div class="container-fluid container-width">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h3 class="about-h-1 text-center">{{ __('app.sorry') }}</h3>
                    <p class="about-t-1 text-center">
                        {{ __('app.no-page-found') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @include('home.home-sections.get-in-touch')
@endsection
