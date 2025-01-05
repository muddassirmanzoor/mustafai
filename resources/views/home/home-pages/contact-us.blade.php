@extends('home.layout.app')
@php
    $cols =  array_merge(getQuery(App::getLocale(), ['address']), ['address_link','featured','status']);
    $office_addresses= \App\Models\Admin\OfficeAddress::select( $cols)->where('status',1)->get();
@endphp

@section('content')
<div class="csm-pages-wraper">
    <section class="contact--pg">
        <div class="container-fluid container-width">
            <div class="d-flex justify-content-center align-items-center contact-heading">
                <h3>{{ __('app.get-in-touch') }}</h3>
            </div>
            <div class="row justify-content-center relative">
                <!-- <div class="col-lg-4 relative">

                </div> -->
                <div class="col-lg-11 d-flex flex-md-row flex-column justify-content-center align-items-center relative">
                    <div class="location-conatct dynamic-address">
                        <h1>{{ __('app.contact-us-name') }}</h1>
                        <ul>
                            <li>
                                <span> <img src="assets/home/images/envelop.png" /></span>
                                <div class="info-holder">
                                    <p class="pb-1">{{getSettingDataHelper('email')}}</p>
                                    {{-- <p>info@mustafai.pk,</p> --}}
                                </div>
                            </li>
                            @if (!empty($office_addresses))
                            @foreach ($office_addresses as $office_address)
                            <a href="{{ $office_address->address_link }}" target="_blank" type="tel">
                                <li>
                                    <span><img src="assets/home/images/map.png" /></span>
                                    <div class="info-holder">
                                        <p>
                                            {{-- 107, Zahoor Plaza,<br />
                                            Darbar Market, Lahore. --}}
                                            {{$office_address->address}}
                                        </p>
                                    </div>
                                </li>
                            </a>
                            @endforeach
                            @endif
                            <li>
                                <span><img src="assets/home/images/phone.png" /></span>
                                <div class="info-holder">
                                    <p class="pb-1">Tel:{{getSettingDataHelper('phone')}}</p>
                                    <p>Mbl:{{getSettingDataHelper('whatsapp')}}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="contact-us-form-wraper">
                        <form class="contact-us-form" id="contact-formm">
                            {{-- <h3 class="pb-3">{{ __('app.get-in-touch') }}</h3> --}}
                            <div class="mb-3">
                                <label>{{ __('app.your-name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="{{ __('app.your-name') }}" value="{{ optional(auth()->user())->user_name}}" required/>
                            </div>
                            <div class="mb-3">
                                <label>{{__ ('app.enter-email')}} <span class="text-danger">*</span></label>
                                <input name="email" type="email" class="form-control" placeholder="{{ __('app.enter') }}{{ __('app.your-email') }}" required  value="{{ optional(auth()->user())->email}}"/>
                            </div>
                            <div class="mb-3">
                                <label>{{__('app.Phone-no')}} <span class="text-danger">*</span></label>
                                <input type="text"  inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="phone" placeholder="{{ __('app.enter') }}{{ __('app.Phone-no') }}" required value="{{optional(auth()->user())->country_code_id . optional(auth()->user())->phone_number}}" />
                            </div>

                            <div class="mb-3">
                                <label>{{__('app.your-subject')}} <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" placeholder="{{ __('app.enter') }}{{ __('app.your-subject') }}" required />
                            </div>
                            <div class="mb-3">
                                <label>{{ __('app.your-message')}} <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" style="height: 100px" placeholder="{{ __('app.enter') }}{{ __('app.your-message') }}" required></textarea>
                            </div>
                            <div class="d-flex mt-md-5 mt-3">
                                <button class="green-hover-bg theme-btn" id="contact_form_btn">{{__('app.send-message')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                   {{-- <div class="mt-5 ">
                      <img src="{{ asset('assets/home/images/get-in-touch-bgs.jpg') }}" class="w-100"/>
                    </div> --}}
            </div>
        </div>
    </section>
</div>
 @include('home.home-sections.get-in-touch-contact')
@endsection


@push('footer-scripts')
<script>
    // var URLS = {
    //     contactUsUrl: "{{ url('/contact-form') }}"
    // }
</script>
@endpush
