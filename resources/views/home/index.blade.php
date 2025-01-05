@extends('home.layout.app')


@push('header-scripts')

@endpush
@section('content')
    
    @include('home.home-sections.banner')
    @include('home.home-sections.welfare')
    @include('home.home-sections.ceo')
    @include('home.home-sections.employee')
    @include('home.home-sections.library')
    @include('home.home-sections.events')
    @include('home.home-sections.donations')
    @include('home.home-sections.testimonials')
    @include('home.home-sections.get-in-touch')

    @include('home.modals.image-modal')
    @include('home.modals.become-donar-modal')
@endsection

@push('footer-scripts')

    <script>
          
        // $(document).ready(function() {
        //   $('img').each(function() {
        //     debugger;
        //     $(this).attr('loading', 'lazy');
        //   });
        // });
      
        $(function(){
            getPrayerTime('daily');
            // $('.id_select2_header ').select2();
            // $("id_select2_header").select2("destroy").select2();
        })

    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/home/js/donation.js') }}"></script>
@endpush

