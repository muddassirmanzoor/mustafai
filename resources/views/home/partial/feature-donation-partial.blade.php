@php
$recievedDonation = $donation->donationReciepts->where('status', 1)->sum('amount');
$requiredDonation = $donation->price;

$percent = ($recievedDonation / $requiredDonation) * 100;
$percent = number_format((float) $percent, 2, '.', '');
@endphp

<div class="charity-head d-flex">
    <h4 class="text-red">
        "{{ __('app.feature-donation-hadees') }}"
        <span class="graish-color">( {{ __('app.feature-donation-hadees-hawala') }} )</span>
    </h4>
</div>

    @if(strlen($donation->description) < 300)
     <div class="same-class-detail">
        <p class="public-project-paragraph " style="font-weight: normal;" id="">
            {!!  $donation->description; !!}

        </p>
    </div>
     @else
     <div class="same-class-detail">
        <p class="public-project-paragraph" style="font-weight: normal;" >
            {!!  mb_strimwidth($donation->description, 0, 300, "......."); !!}
            <a href="Javacript:void(0)" onclick="showDonationDesc('allDescDonaiton','Read More')">{{__('app.read-more')}}</a>
        </p>
    </div>
    @endif
    <div class="same-class-detail d-none"  id="allDescDonaiton">
        <p class=" "   >
            {!!  $donation->description;  !!}
            <a href="Javascript:void(0)" onclick="showDonationDesc('shortDetails','Read More')">{{__('app.read-less')}}</a>

        </p>
    </div>
    <div class="same-class-detail d-none"  id="shortDetails">
        <p class=" same-class-detail" >
            {!!  mb_strimwidth($donation->description, 0, 300, "......."); !!}
            <a href="Javascript:void(0)" onclick="showDonationDesc('allDescDonaiton','Read More')">{{__('app.read-more')}}</a>
        </p>
    </div>




<div class="avg-amounts mt-3">
    <div class="progress">
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"
            aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between">
        {{-- <div class="d-flex flex-column">
            <p class="graish-color">{{ __('app.amount-funded') }}</p>
            <h5 class="text-blue">PKR {{ $recievedDonation }}</h5>
        </div> --}}
        <div class="d-flex flex-column">
            <p class="graish-color">{{ __('app.total-funded-required') }}</p>
            <h5 class="text-blue text-center">PKR {{ $requiredDonation }}</h5>
        </div>
    </div>
</div>
<div
    class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-4 mt-2">
    <button class="theme-btn green-hover-bg " href="javascript:void(0)"
        onclick="donateNow({{ $donation->id }})">{{ __('app.donate-now') }}</button>
</div>
