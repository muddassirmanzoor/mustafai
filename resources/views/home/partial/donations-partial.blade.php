@foreach($donations as $key=>$val)
@php
$recievedDonation = $val->donationReciepts->where('status',1)->sum('amount');
$requiredDonation = $val->price;
$percent = ($recievedDonation/$requiredDonation)*100;
$percent = number_format((float)$percent, 2, '.', '');
@endphp
<div class="row mt-3" id="donation-{{$val->id}}">
    <input type="hidden" name="donation_ids[]" value="{{ $val->id }}">
    <div class="col-lg-12">
        <h3 class="@if(!$key==0) d-none @endif text-center mb-lg-5 mb-3">{{__('app.donation-for-the-nobel-causes')}}</h3>
        <div class="row mt-4 justify-content-center align-items-center">
            <!-- <div class="col-lg-4">
                <div class="charity-box-lottie">
                    <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_le5ncsdc.json" speed="1" loop autoplay></lottie-player>
                </div>
            </div> -->
            <div class="col-lg-8">
                <div class="charity-don-wraper charity-content " id="feature-magzine-sections">
                    <div class="charity-head d-flex justify-content-center align-items-center">
                        <h4 class="text-red text-center">
                            {{$val->title}}
                        </h4>
                    </div>
                    <div class="donars-pg-description text-center">
                        {!!$val->description!!}
                    </div>
                    <div class="avg-amounts">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$percent}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            {{-- <div class="d-flex flex-column">
                                <p class="graish-color">{{ __('app.amount-funded')}}</p>
                                <h5 class="text-blue">PKR {{$recievedDonation}}</h5>
                            </div> --}}
                            <div class="d-flex flex-column  required-donation">
                                <p class="graish-color">{{ __('app.total-funded-required')}}</p>
                                <h5 class="text-blue text-center">PKR {{$requiredDonation}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-3 mt-2">
                        <button class="blue-hover-bg theme-btn" onclick="donateNow({{$val->id}})">{{__('app.donate-now')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endforeach
