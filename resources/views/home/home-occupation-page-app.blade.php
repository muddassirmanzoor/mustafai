@extends('home.layout.app')
@section('content')
    @if(!empty($occupation))
        <div class="mt-4 mb-4" >
            <div class="container">
                <div class="d-flex flex-column justify-content-center align-items-center green-box">
                    <div class="cms-page-title">
                        <h3 class="about-h-1 text-center text-red">{{$occupation->title}}</h3>

                    </div>
                    <div class="cms-page-content mt-3 text-center occupation-content">
                        {!! str_replace('src="http://127.0.0.1:8000','src="'.url('').'',$occupation->content)  !!}
                    </div>
                    <br>
                    @if(count($occupation->subProfession))
                        <div class="child-occupations">
                            <ul>
                                @foreach($occupation->subProfession  as $profession)
                                    <li><a href="{{ url('api/home/professions/'.$profession->slug) }}">{{ $profession->{'title_'.app()->getLocale()} }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <hr>
                <div class="related-user-sec ocupation-user users-table green-box">
                    <a href="javascript:void(0);" id="login_link"><h3 class="about-h-1 text-center text-red " >{{__('app.login-to-see-more-details')}}</h3></a>
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
@endsection
@push('footer-scripts')
<script>
    $( "#login_link" ).click(function() {
        if(window.ReactNativeWebView) {
            window.ReactNativeWebView.postMessage('401')
        }
    });
</script>
@endpush
