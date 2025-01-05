
@php
    $image=getSettingDataHelper('logo')=='' ? asset('assets/home/images/site-logo.png') : getSettingDataHelper('logo');
    $cols =  array_merge(getQuery(App::getLocale(), ['address']), ['address_link','featured','status']);
    $office_address= \App\Models\Admin\OfficeAddress::select( $cols)->where(['status'=>1,'featured'=>1])->first();

    if (!session()->has('settingsArray')) {
        $settingsdata = \App\Models\Admin\Setting::all()->toArray();

        $sortedArray = array_column($settingsdata, 'option_value', 'option_name');
        session()->put('settingsArray', $sortedArray);
    }
    $url_1 = Request::segment(1);

@endphp
<!-- footer -->
<footer class="{{ (Request::segment(1) == 'contact-us') ? '' : 'footer-home' }} footer">
    <div class="container-fluid container-width">
        <div class="row">
            <div class="col-lg-4">
                <div class="footer-newsletter">
                    <h5>{{__('app.subscribe-our-newsletter')}}</h5>
                    <div class="field-wrapper">
                        <input class="form-control" name="email" id="email_subscription" type="email" placeholder="{{ __('app.enter-email') }}" />
                        <button class="green-hover-bg theme-btn" id="subBtn">{{__('app.subscribe')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-12 mb-lg-0 mb-md-5 mb-3">
                <div class="footer-info">
                    <div class="footer-logo">
                        <img loading="lazy" src="{{getS3File($image)}}" class="img-fluid" alt="" />
                    </div>
                    <p>{{__('app.more-apps')}}.</p>
                    <div class="store-logos">
                        @if(!empty(getSettingDataHelper('play_store')))
                        <div class="image">
                            <a href="{{getSettingDataHelper('play_store')}}" target="_blank">
                                <img loading="lazy" src="{{asset('assets/home/images/google-store.png')}}" class="img-fluid" alt="" />
                            </a>
                        </div>
                        @endif
                        @if(!empty(getSettingDataHelper('app_store')))
                        <div class="image">
                            <a href="{{getSettingDataHelper('app_store')}}" target="_blank">
                                <img loading="lazy" src="{{asset('assets/home/images/app-store.png')}}" class="img-fluid" alt="" />
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="follow-us">
                        <h5 class="pb-4">{{__('app.follow-us')}}</h5>
                        <ul class="list-unstyled">
                            @if(!empty(getSettingDataHelper('facebook')))
                            <li>
                                <a target="_blank" href="{{getSettingDataHelper('facebook')}}" class="facebook">
                                    <span class="fa fa-facebook"></span>
                                </a>
                            </li>
                            @endif
                            @if(!empty(getSettingDataHelper('linkedin')))
                            <li>
                                <a target="_blank" href="{{getSettingDataHelper('linkedin')}}" class="linkedin">
                                    <span class="fa fa-linkedin"></span>
                                </a>
                            </li>
                            @endif
                            @if(!empty(getSettingDataHelper('twitter')))
                            <li>
                                <a target="_blank" href="{{getSettingDataHelper('twitter')}}" class="twitter">
                                    <span class="fa fa-twitter"></span>
                                </a>
                            </li>
                            @endif
                            @if(!empty(getSettingDataHelper('pinterest')))
                            <li>
                                <a target="_blank" href="{{getSettingDataHelper('pinterest')}}" class="pinterest">
                                    <span class="fa fa-pinterest"></span>
                                </a>
                            </li>
                            @endif
                            @if(!empty(getSettingDataHelper('youtube')))
                            <li>
                                <a target="_blank" href="{{getSettingDataHelper('youtube')}}" class="youtube">
                                    <span class="fa fa-youtube"></span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5>{{__('app.useful-links')}}</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ URL('/') }}">{{__('app.home')}}</a>
                        </li>
                        <li>
                            <a href="{{ URL('contact-us') }}"> {{__('app.contact-us-name')}}</a>
                        </li>
                        @if (isActiveFooter('about-us'))
                        <li>
                            <a href="{{ URL('about-us') }}">{{__('app.about-us')}}</a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ URL('/mustafai-store') }}">{{__('app.mustafai-store')}}</a>
                        </li>
                        <li>
                            <a href="{{ URL('/view-library/1?tab='.encodeDecode(1).'') }}">{{__('app.gallery')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-lg-0 mb-md-4">
                <div class="footer-links">
                    <h5 class="opacity d-md-block d-none">{{__('app.useful-links')}}</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ URL('events') }}">{{__('app.events')}}</a>
                        </li>
                        {{--<li>
                            <a href="{{ URL('our-tehreek') }}">{{__('app.our-tehreek')}}</a>
                        </li>--}}
                        <li>
                            <a href="{{ URL('our-donations') }}">{{__('app.our-donations')}}</a>
                        </li>
                        <li>
                            <a href="{{ URL('all-team') }}">{{__('app.our-team')}}</a>
                        </li>
                        <li>
                            <a href="{{ URL('all-testimonials') }}">{{__('app.testimonials')}}</a>
                        </li>
                        @if(!Auth::check() && $url_1=='')
                        {{-- !Auth::check() && --}}
                            <li>
                                <a href="javascrip:void(0)" data-toggle="modal" data-target="#become-donar">{{__('app.add-blood-donners')}}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5 class="opacity d-md-block d-none">{{__('app.useful-links')}}</h5>
                    <ul class="list-unstyled">
                        {{--<li>
                            <a href="{{ URL('volunear') }}">{{__('app.volunteer')}}</a>
                        </li>--}}
                        @if (isActiveFooter('privacypolicy'))
                        <li>
                            <a href="{{ URL('privacypolicy') }}">{{__('app.privacy-policy')}} </a>
                        </li>
                        @endif
                        @if (isActiveFooter('termandcondition'))
                        <li>
                            <a href="{{ URL('termandcondition') }}">{{__('app.terms-&-condition')}}  </a>
                        </li>
                        @endif
                        {{-- <li>
                            <a href="{{ URL('help-center') }}"> {{__('app.help-center')}} </a>
                        </li> --}}
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5>{{__('app.contact-us-name')}}</h5>
                    <ul class="list-unstyled contact-info" style="direction:ltr !important">
                        <li>
                            <a href="tel:{{getSettingDataHelper('phone')}}"><span class="icon fa fa-phone"></span> <span>{{getSettingDataHelper('phone')}}</span></a>
                        </li>
                        <li>
                            <a href="mailto:{{getSettingDataHelper('email')}}"><span class="icon fa fa-envelope"></span><span> {{getSettingDataHelper('email')}}</span></a>
                        </li>
                        @if(!empty($office_address))
                        <li>
                            <a href="{{$office_address->address_link}}" target="_blank" type="tel"><span class="icon fa fa-map-marker"></span><span>{{$office_address->address}}</span></a>
                        </li>
                        @endif
                        <li>
                            <a type="tel">
                                <span class="icon fa fa-clock-o"></span>
                                <span style="direction:ltr !important">
                                    {{ (!empty(getSettingDataHelper('opening_time'))) ? date('H:i A',strtotime(getSettingDataHelper('opening_time'))):'0:00 PM' }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyrights">
            <p>{{trans('app.copyright-mustafai', [ 'date' => date("Y") ])}}</p>
        </div>
    </div>
</footer>
