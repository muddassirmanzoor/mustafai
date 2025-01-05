@extends('user.layouts.layout')

@push('styles')
    <style>
        .bussiness-collapse-row.d-flex.justify-content-between.align-items-center.mt-3.field-error {
            color: red;
        }

        .fondation-pdf-form label.error {
            color: transparent;
            display: none;
        }

        .fondation-pdf-form label.error:after {
            content: '*';
            color: red;
            display: none;
        }

        .invalid-field {
            border-bottom: 1px solid red !important;
            background-color: #e8c1c1;
        }
    </style>
@endpush
@section('content')
  <div>
    <h3 class="text-center mb-4">{{ !empty($businessHeading->{'title_'.App::getLocale()})?$businessHeading->{'title_'.App::getLocale()} : ''  }}</h3>
    <p class="mb-4 ">{!! !empty($businessHeading->{'content_'.App::getLocale()})? $businessHeading->{'content_'.App::getLocale()} : ''   !!}</p>
  </div>

    <div class="plantypes-icons d-flex flex-sm-row flex-column mb-4 test mt-3">
        @if(have_permission('View-Upcoming-Business-Plans'))
        <a href="{{ URL('user/busines_plans?type=1') }}"
            class="btn add-more-btn mt-sm-0 mt-2 {{ $type == 1 ? 'active' : '' }}">{{ __('app.upcoming-business-plans') }}</a>
        @endif
        @if(have_permission('View-Applied-Plans'))
        <a href="{{ URL('user/busines_plans?type=2') }}"
            class="btn add-more-btn ms-sm-3 mt-sm-0 mt-2 {{ $type == 2 ? 'active' : '' }}">{{ __('app.applied-plans') }}</a>
        @endif
        @if(have_permission('View-My-Activated-Plans'))
        <a href="{{ URL('user/busines_plans?type=3') }}"
            class="btn add-more-btn ms-sm-3 mt-sm-0 mt-2 {{ $type == 3 ? 'active' : '' }}">{{ __('app.my-activated-plans') }}</a>
        @endif
    </div>
    <div class="wrapper-charity">
        @forelse($plans as $plan)
            <div class="charity-content request-pg dash-common-card dashboard-donatec accent-blue business-plan mb-3"
                id="feature-magzine-section">
                <div class="charity-head d-flex">
                    <h4 class="text-red">
                        {{ $plan->name }}
                    </h4>
                </div>

                <div class="plan-info">
                    {{-- <div class="d-flex justify-content-between mb-1">
			<p class="plan-info-det"><strong>Type :</strong></p>
			<p class="plan-info-det">
				@if ($plan->type == 1)
					Monthly
				@elseif($plan->type == 2)
					Weekly
				@else
					Daily
				@endif
			</p>
		</div> --}}
                    <div class="d-flex justify-content-between mb-1">
                        <p class="plan-info-det">
                            <strong>{{ __('app.invoice-amount-title') }}</strong>
                        </p>
                        <p class="plan-info-det">{{ $plan->invoice_amount }}</p>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <p class="plan-info-det">
                            <strong>{{ __('app.total-invoice-title') }}</strong>
                        </p>
                        <p class="plan-info-det">{{ $plan->total_invoices }}</p>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <p class="plan-info-det">
                            <strong>{{ __('app.total-users-title') }} </strong>
                        </p>
                        <p class="plan-info-det">
                            {{ $plan->total_users }}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <p class="plan-info-det">
                            <strong>{{ __('app.start-date') }} </strong>
                        </p>
                        <p class="plan-info-det">
                            {{ date('d-m-Y', $plan->start_date) }}
                        </p>
                    </div>
                </div>
                <div class="total-application d-flex justify-content-between mb-1">
                    <p class="plan-application-det">
                        <strong>{{ __('app.total-applications-title') }}</strong>
                    </p>
                    <p class="plan-application-det">{{ $plan->applications()->count() }}</p>
                </div>
                <hr>
                <p class="text-donars-1 graish-color">{!! $plan->description !!}</p>
                <div
                    class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-4 mt-2 appled---plan-0">
                    @if ($type == 1)
                        @if (have_permission('Apply-Upcoming-Business-Plans'))
                            <button class="blue-hover-bg theme-btn apply-now-modal-{{ $plan->id }}" data_link="{{trans("app.terms-&-condition-business", ["link" => "".url('user/terms-condition?plan_id='.hashEncode($plan->id).'')."" ]) }}"
                                onclick="applyNowModal($(this),'{{ hashEncode($plan->id) }}',0)">{{ __('app.apply-now') }}</button>
                        @endif
                    @elseif($type == 2)
                        @if (have_permission('View-Applied-Plans-Application'))
                            <button class="blue-hover-bg theme-btn recipt-btn" data_link="{{trans("app.terms-&-condition-business", ["link" => "".url('user/terms-condition?plan_id='.hashEncode($plan->id).'')."" ]) }}"
                                onclick="applyNowModal($(this),'{{ hashEncode($plan->id) }}','{{ hashEncode($plan->applications()->where('applicant_id', Auth::user()->id)->first()->id) }}')" >{{ __('app.application') }}</button>
                            <a target="_blank"
                                href="{{ url('user/busines_plans/download?id=') .hashEncode($plan->applications()->where('applicant_id', Auth::user()->id)->first()->id) }}"
                                class="blue-hover-bg theme-btn text-center ms-sm-3 mt-sm-0 mt-2 text-white">{{ __('app.download-form') }}</a>
                        @endif
                    @else
                        <div class="d-flex flex-sm-row flex-column">
                            @if (have_permission('My-Activated-Plans-Invoices'))
                                <button class="blue-hover-bg theme-btn invoice--btn"
                                    onclick="goInvoices('{{ hashEncode($plan->applications()->where('applicant_id', Auth::user()->id)->first()->id) }}')">{{ __('app.invioces') }}</button>
                            @endif
                            @if(have_permission('Request-For-Date-Change'))
                                <button class="blue-hover-bg theme-btn ms-sm-3 mt-sm-0 mt-2" start-date="{{date("Y-m-d", $plan->start_date)}}" end-date="{{date("Y-m-d", $plan->end_date)}}"
                                    onclick="dateChangeRequest('{{ hashEncode($plan->applications()->where('applicant_id', Auth::user()->id)->first()->id) }}',this)">{{ __('app.request-for-date-change') }}</button>
                            @endif
                            <a target="_blank"
                                href="{{ url('user/busines_plans/download?id=') .hashEncode($plan->applications()->where('applicant_id', Auth::user()->id)->first()->id) }}"
                                class="blue-hover-bg theme-btn text-center ms-sm-3 mt-sm-0 mt-2 text-white">{{ __('app.download-form') }}</a>
                        </div>
                    @endif
                </div>
            </div>
            <input type="hidden" id="terms-condition-{{hashEncode($plan->id)}}" value="{{ $plan->term_conditions }}" >

        @empty
            <div class="dash-common-card mt-3">
                <p class="text-center">{{ __('app.no-data-available') }}</p>
            </div>
        @endforelse
    </div>

    <div class="modal fade common-model-style" id="apply-now" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.apply-now') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-details">

                </div>
                <div class="modal-footer">
                    <button type="button" class="green-hover-bg theme-btn"
                        onclick="submitForm()">{{ __('app.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade common-model-style" id="application-date-change" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.change-date-req') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-details">
                    <form id="req-date-form">
                        <input type="hidden" name="req_change_application_id" id="req_change_application_id"
                            value="0">
                        <label for="">{{ __('app.select-date') }}</label>
                        <input type="date" class="form-control" name="date_request" required id="date-change-req" min="" max="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="green-hover-bg theme-btn"
                        onclick="dateChangeRequest()">{{ __('app.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            $(document).on('change', '.bp-input', function() {
                var errorElem = $(this).parents('.collapse-rows').find('label.error:visible');
                if (!errorElem.length || errorElem.text() == '') {
                    $(this).parents('.collapse-rows').prev('div').removeClass("field-error");
                }
            })
        })
        function applyNowModal(_this='',planId, applicationId = 0)
        {
            var urlterms=_this.attr('data_link');
            // alert('{!! trans('app.terms-&-condition-business,:link',['link' => '$planId']) !!}')
            var content = $('#terms-condition-'+planId).val();
            if(applicationId==0) {
                Swal.fire({
                    title: '{{ __('app.terms-&-condition') }}',
                    html: urlterms,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('app.agree') }}',
                    cancelButtonText: '{{ __('app.not-agree') }}',
                    }).then((result) => {
                    if (result.isConfirmed) {
                    window.open("{{ url('user/get-plan-details') }}"+'?planId='+planId+'&applicationId='+applicationId,'_blank');
                    }
                });
            }
            else{
                window.open("{{ url('user/get-plan-details') }}"+'?planId='+planId+'&applicationId='+applicationId,'_blank');
            }
        }
        function applyNowModalBack(planId, applicationId = 0) {

            $.ajax({
                url: "{{ route('user.get-busines-plan-details') }}",
                data: {
                    planId: planId,
                    applicationId: applicationId
                },
                type: 'get',
                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.status) {
                        $('#plan-details').html(data.html);
                        $('#apply-now').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: data.message,
                            type: 'error'
                        })
                    }
                }
            });
        }

        function paymentMethods(_class) {
            $("." + _class).each(function(index) {
                if ($(this).is(':checked')) {
                    $('.' + _class + '-' + $(this).val()).css('display', 'block');
                } else {
                    $('.' + _class + '-' + $(this).val()).css('display', 'none');
                }
            });
        }

        function btnText(_this) {
            if ($(_this).attr('data-txt') == "+") {
                $(_this).attr('data-txt', '-');
            } else {
                $(_this).attr('data-txt', '+');
            }

            $(_this).text($(_this).attr('data-txt'));
        }

        function goInvoices(applicationID) {
            window.location = "{{ URL('user/invoices') }}/" + applicationID;
        }

        function submitForm() {
            $('#business-palns-form').validate({
                ignore: ':hidden:not(.do-not-ignore)',
                rules: {
                    form_pronote_ammount: {
                        min: 100
                    },
                    form_pronote_ammount_half: {
                        min: 100
                    },
                    form_pronote_charge: {
                        min: 100
                    },
                    form_raseed_pronote_amount: {
                        min: 100
                    },
                    form_raseed_pronote_amount_half: {
                        min: 100
                    },
                },
                highlight: function(element) {
                    $(element).addClass('invalid-field');
                    $(element).parents('.collapse-rows').prev('div').addClass("field-error");
                },
                unhighlight: function(element) {
                    $(element).removeClass('invalid-field');
                    // if($(element).parents('.collapse-rows').find('label.error:visible').length == "")
                    // {
                    //     $(element).parents('.collapse-rows').prev('div').removeClass("field-error");
                    // }
                }
            })

            if ($('#business-palns-form').valid()) {
                var formData = new FormData($('#business-palns-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.submit.plan-application') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.preloader').show();
                    },
                    success: function(data) {
                        if (data.status) {
                            var planId = data.plan_id;
                            $('#apply-now').modal('hide');
                            $('.apply-now-modal-' + planId).addClass('applied');
                            $('.apply-now-modal-' + planId).text('{{ __('app.applied') }}');
                            $('.apply-now-modal-' + planId).attr('onclick', '');

                            Swal.fire({
                                icon: 'success',
                                text: data.message,
                                type: 'success'
                            })

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: data.message,
                                type: 'error'
                            })
                        }
                        $('.preloader').hide();
                    }
                });
            }
        }

        function selectPlanImages(input) {
            var file = $(input).get(0).files[0];

            if (file) {
                var reader = new FileReader();
                console.log(reader);
                reader.onload = function() {
                    $(input).next('img').attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            } else {
                $(input).next('img').attr('src', "{{ asset('images/avatar.png') }}");
            }
        }

        function dateChangeRequest(applicationID = '',_this='') {

            if (applicationID) {
                if (_this) {
                    var start_date =_this.getAttribute('start-date');
                    var end_date =_this.getAttribute('end-date');
                }
                $('#req_change_application_id').val(applicationID);
                $("#date-change-req").attr('min',start_date)
                $("#date-change-req").attr('max',end_date)
                $('#application-date-change').modal('show');
            } else {
                if ($('#req-date-form').valid()) {
                    var formData = new FormData($('#req-date-form')[0]);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.submit.plan-application-date-request') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        beforeSend: function() {
                            $('.preloader').show();
                        },
                        success: function(data) {

                            $('#application-date-change').modal('hide');
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    text: data.message,
                                    type: 'success'
                                })

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: data.message,
                                    type: 'error'
                                })
                            }
                            $('.preloader').hide();

                        }
                    });
                }
            }
        }
    </script>
@endpush
