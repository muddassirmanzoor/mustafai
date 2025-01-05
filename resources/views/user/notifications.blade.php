@extends('user.layouts.layout')

@section('content')
<section class="all-notifications">
    <h3 class="text-green mb-lg-5 mb-3 text-center">{{__('app.notification')}}</h3>
    <div id="notifications-data">
        @forelse($notifications as $notification)
            <a href="{{ $notification->link }}">
                <div class="notifications-item"> <img src="{{ (!empty($notification->profile)) ? getS3File($notification->profile) : asset('images/avatar.png') }}" alt="img">
                    <div class="text d-flex flex-column flex-grow-1 mr2">
                        <h4>{{ $notification->title }}</h4>
                        {{-- <p>+20 vista badge earned</p>--}}
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
        @empty
        <h3 class="w-100 text-center">{{ __('app.no-notification') }}</h3>
        @endforelse
    </div>


    <!-- shows loader after page reaches at bottom -->
    <div class="ajax-load text-center" style="display:none">
        <h5>{{ __('app.load-more-post') }}</h5>
    </div>

</section>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    var page = 1;
    $(window).scroll(function() {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 1) {
            page++;
            loadMoreData(page);
        }
    });


    function loadMoreData(page) {
        $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
            .done(function(data) {
                if (data.html == "") {
                    $('.ajax-load').html("{{ __('app.no-data') }}");
                    return;
                }
                $('.ajax-load').hide();
                $("#notifications-data").append(data.html);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                Swal.fire(`{{__('app.server-issue')}}`, '', 'error');
                // alert('server not responding...');
            });
    }
</script>
@endpush
