@extends('user.layouts.layout')
@section('content')
    <section class="request-pg dash-common-card">
        <ul class="requset-list">
            @if(count( $requests))
                @foreach($requests as $request)
                    <li>
                        <div class="d-flex flex-sm-row flex-column justify-content-sm-between justify-content-center align-items-center">
                            <div class="d-flex align-items-center">
                                <figure class="mb-sm-0 mb-3 me-2 user-img">
                                    <img src="{{ getS3File($request->profile_image) }}" alt="" class="start-a-post-profile img-fluid">
                                </figure>
                                <h6 class="size-medium">{{$request->user_name}}</h6>
                            </div>
                            <div class="d-flex common-badge-size">
                                <span class="confirm badge bg-primary me-3" data-type="1" onclick="responsRequest({{$request->conId}},$(this))">{{ __('app.confirm') }} <span><i class="fa fa-check" aria-hidden="true"></i></span></span>
                                <span class="reject badge bg-danger" data-type="0" onclick="responsRequest({{$request->conId}},$(this))">{{ __('app.reject') }} <span><i class="fa fa-close" aria-hidden="true"></i></span></span>
                            </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            @else
                <li><h3 class="text-center">{{ __('app.no-data-available') }}</h3></li>
            @endif
        </ul>
    </section>
@endsection
    @push('scripts')
    @include('user.scripts.chat-script')
@endpush
