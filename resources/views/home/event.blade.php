@extends('home.layout.app')
<style>
    .custom-image-carousel img {
        width: 100% !important;
        margin: 0 auto;
        height: 340px;
        object-fit: contain;
    }
    .event-carousel-wrapper .carousel-control-next, .carousel-control-prev {
        top: 134px !important;
        width: 31px !important;;
        height: 31px !important;;
        border-radius: 50% !important;
    }
    .event-carousel-wrapper .carousel-control-next-icon, .carousel-control-prev-icon {
        width: 1rem;
        height: 1rem;
    }
    .event-carousel-wrapper .carousel-control-next{
        background: #28882c !important;
    }
    .event-carousel-wrapper .carousel-control-prev{
        background: #28882c !important;

    }



</style>
@section('content')

    <div class="csm-pages-wraper">
        <div class="row mt-3">
            <div class="col-lg-12">
                <h3 class="text-center mb-lg-5 mb-3">{{ __('app.events') }}</h3>
                <div class="row mt-4 justify-content-center align-items-center">
                    <div class="col-lg-8">
                        <div class="charity-don-wraper charity-content">
                            <div class="py-3 d-flex justify-content-center align-items-center">
                                <h4 class="text-red text-center">
                                    {{ $event->title }}
                                </h4>
                            </div>
                            <div class="donars-pg-description text-center">
                                {!! $event->content !!}
                            </div>
                            <!--event sessions-->
                            @if($event->sessions()->count() > 0)
                                <div class="py-3 d-flex justify-content-center align-items-center flex-column">
                                    <h4 class="text-red text-center mb-3">
                                        {{__('app.sessions')}}
                                    </h4>
                                    <div class="table-responsive w-100">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                {{-- <td>{{ $session->session }}</td>
                                                <td data-event-description-id="{{ $session->id }}" data-description-detail="{{ $session->description }}" data-splitted-description-detail="{{ Str::length($session->description) >=100 ? Str::limit($session->description, 100, '') : '' }}">
                                                    {!! Str::length($session->description) >=100 ? Str::limit($session->description, 100, ' ...<a href="javascript:void(0)" onclick="toggleDescription(this)" id="'.$session->id.'">'.__('app.read-more').'</a>') : $session->description !!}
                                                </td>

                                                <td>{{ \Illuminate\Support\Carbon::make($session->session_start_date_time)->format('g:i A') }}</td>
                                                <td>{{  \Illuminate\Support\Carbon::make($session->session_end_date_time)->format('g:i A') }}</td> --}}
                                                <th style="white-space:nowrap">{{__('app.title')}}</th>
                                                <th style="white-space:nowrap">{{__('app.description')}}</th>
                                                <th style="white-space:nowrap">{{__('app.start-date-and-time')}}</th>
                                                <th style="white-space:nowrap">{{__('app.end-date-and-time')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($event->sessions as $session)
                                                <tr>
                                                    <td>{{ $session->session }}</td>
                                                    <td data-event-description-id="{{ $session->id }}" data-description-detail="{{ $session->description }}" data-splitted-description-detail="{{ Str::length($session->description) >=100 ? Str::limit($session->description, 100, '') : '' }}">
                                                        {!! Str::length($session->description) >=100 ? Str::limit($session->description, 100, ' ...<a href="javascript:void(0)" onclick="toggleDescription(this)" id="'.$session->id.'">'.__('app.read-more').'</a>') : $session->description !!}
                                                    </td>
                                                    <td>{{ !empty($session->session_start_date_time)?  \Illuminate\Support\Carbon::make($session->session_start_date_time)->format('Y-m-d g:i A') : '' }}</td>
                                                    <td>{{ !empty($session->session_end_date_time)? \Illuminate\Support\Carbon::make($session->session_end_date_time)->format('Y-m-d g:i A') : ''  }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($event->images()->count() > 0)
                                <div class="donars-pg-description text-center event-carousel-wrapper">
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($event->images as $image)
                                                <div class="carousel-item custom-image-carousel {{ $loop->first ? 'active' : '' }}">
                                                    <img src="{{ getS3File($image->image) }}" class="d-block w-100" alt="event-image">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex justify-content-center align-items-center mt-xl-3 mt-2">
                                <button {{ ($event->end_date_time < date('Y-m-d H:i:s')) ? 'disabled' : '' }} data-event-id="{{ $event->id }}" data-toggle="modal"
                                        data-target="#eventModal" type="button"
                                        class="theme-btn event_button join_event_button  {{ ($event->end_date_time < date('Y-m-d H:i:s')) ? 'disable-event' : '' }}"
                                        onclick=>{{ __('app.join-this-event') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

        // function of read-more/less of post title
        function toggleDescription(_this, hide = false) {
            let id = $(_this).attr('id')
            let titleElement = $('[data-event-description-id="' + id + '"]')

            if (hide) {
                titleElement
                    .text(titleElement.attr('data-splitted-description-detail')).
                append(`<a href='javascript:void(0)' onclick='toggleDescription(this, false)' id=${id}>{{ __('app.read-more')}}</a>`)
                return 1
            }

            titleElement.
            text(titleElement.attr('data-description-detail')).
            append(`<a href='javascript:void(0)' onclick='toggleDescription(this, true)' id=${id}>{{ __('app.read-less') }}</a>`)
            return 1
        }

    </script>
@endpush
