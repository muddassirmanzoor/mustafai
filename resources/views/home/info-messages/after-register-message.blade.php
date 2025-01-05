@extends('home.layout.app')
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper mb-5">
    <div class="container-fluid container-width">
        <section>
            {{-- @guest --}}
            <div class="d-flex justify-content-center welcome-text">
                <h6 class="text-danger text-capitalize text-center">{{__('app.after-sign-up')}}</h6>
            </div>
            {{-- @endguest --}}

            {{-- @auth
            <div class=" d-flex justify-content-center">

                <h6 class="text-capitalize text-success">Your Login Status Is Approved You Can Login </h6>
            </div>
            @endauth --}}

        </section>
    </div>
</div>
@include('home.home-sections.get-in-touch')
@endsection

@push('footer-scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/home/js/donation.js') }}"></script>
@endpush
