@extends('home.layout.app')
@section('content')
    @push('header-scripts')
        <style>
            .disable-event {
                opacity: 0.3;
            }
        </style>
    @endpush
    @include('home.home-sections.banner')
    <div class="csm-pages-wraper mb-5">
        <section class="detail-doners-list donations">
            <div class="container-fluid container-width">
                <input type="hidden" value="{{$id}}" id="scroll_id">
                @foreach ($events as $key => $val)
                    <div class="row mt-3" id="event-{{ $val->id }}" >
                        <div class="col-lg-12">
                            <h3 class="@if (!$key == 0) d-none @endif text-center mb-lg-5 mb-3">{{ __('app.events') }}</h3>
                            <div class="row mt-4 justify-content-center align-items-center">
                                <div class="col-lg-8">
                                    <div class="charity-don-wraper charity-content search-event-{{ $val->id }}" id="feature-magzine-sections">
                                        <div class="py-3 d-flex justify-content-center align-items-center">
                                            <h4 class="text-red text-center">
                                                {{ $val->title }}
                                            </h4>
                                        </div>
                                        <div class="donars-pg-description text-center">
                                            {!! $val->content !!}
                                        </div>
                                        <div class="d-flex justify-content-end align-items-end read-desc">
                                            <a href="{{ route('event.detail', hashEncode($val->id)) }}">{{__('app.event-details')}}</a>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center mt-xl-3 mt-2">
                                            <button {{ ($val->end_date_time < date('Y-m-d H:i:s')) ? 'disabled' : '' }} data-event-id="{{ $val->id }}" data-toggle="modal"
                                                data-target="#eventModal" type="button"
                                                class="theme-btn event_button join_event_button  {{ ($val->end_date_time < date('Y-m-d H:i:s')) ? 'disable-event' : '' }}"
                                                onclick=>{{ __('app.join-this-event') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div class="modal library-detail common-model-style fade" id="eventModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.join-this-event') }}</h4>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal"></button>
                </div>
                <div class="modal-body ">
                    <input placeholder="{{ __('app.your-email') }}" class="form-control attende_email" type="email"
                        name="user_email">
                    <input type="hidden" class="attende_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="green-hover-bg theme-btn order_product submit_event">{{ __('app.join-this-event') }}</button>
                </div>
            </div>
        </div>
    </div>
    @include('home.home-sections.get-in-touch')
@endsection
@push('footer-scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $('.join_event_button').click(function() {
                let evendId = $(this).attr('data-event-id');
                $('.attende_id').val(evendId)
                $('.attende_email').val('')
                $('#eventModal').modal('show');
            });
        })

        $('.submit_event').click(function() {

            let eventId = $('.attende_id').val();
            var mailPattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            var userEmail = $('.attende_email').val();
            if (!mailPattern.test(userEmail)) {
                swal.fire(`{{__('app.invalid-email')}}`,``, "error")
                // alert('not a valid e-mail address');
                return 1;
            }

            $('.submit_event').prop('disabled', true);

            $.ajax({
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    eventId: eventId,
                    email: userEmail
                },
                url: "{{ route('event.create') }}",
                success: function(result) {
                    if (result.status === 200 ) {
                        swal.fire(AlertMessage.event,'', "success");
                        // alert(result.message)
                        $('#eventModal').modal('hide')
                    }
                    if (result.status === 201 ) {
                        swal.fire(AlertMessage.emailexist,'', "error");
                        // alert(result.message)
                        $('#eventModal').modal('hide')
                    }
                    $('.submit_event').prop('disabled', false);
                }
            });
        });

$(document).ready(function () {
    // Handler for .ready() called.
    var s_id=$("#scroll_id").val();
    $('html, body').animate({
        scrollTop: $("#event-"+s_id).offset().top
    }, 'slow');
    $(".search-event-"+s_id).css('background','#e8ede7');
});
    </script>
@endpush
