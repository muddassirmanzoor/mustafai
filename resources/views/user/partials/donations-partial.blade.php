@forelse($donations as $donation)
@php
$recievedDonation = $donation->donationReciepts->where('status',1)->sum('amount');
$requiredDonation = $donation->price;

$percent = ($recievedDonation/$requiredDonation)*100;
$percent = number_format((float)$percent, 2, '.', '');
@endphp
<input type="hidden" name="donation_ids[]" value="{{ $donation->id }}">
<div class="charity-content request-pg dash-common-card dashboard-donate user-donation mb-3" id="feature-magzine-section">
    <div class="charity-head d-flex">
        <h4 class="text-red">
            {{ $donation->title }}
        </h4>
    </div>
    {{-- <p class=" ">{!! $donation->description !!}</p> --}}
    <p class="text-donars-1 graish-color public-project-paragraph" style="font-weight: normal;">
        {!! \Str::limit($donation->description, 300, '') !!}
        @if (strlen($donation->description) > 300)
            <span id="dots_{{ $donation->id }}">...</span>
            <span id="more_{{ $donation->id }}" style="display: none;">{!! substr($donation->description, 300, strlen($donation->description)) !!}</span>
        @endif
    </p>
    @if (strlen($donation->description) > 300)
    <div class="d-flex justify-content-end align-items-end">
        <a type="button" class="btn btn-primary btn-sm mt-1" onclick="readMore(<?php echo $donation->id ?>)" id="myBtn_{{ $donation->id }}">{{ __('app.read-more') }}</a>
    </div>
        @endif
    <div class="avg-amounts mt-2">
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{$percent}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-column">
            </div>
            <div class="d-flex flex-column">
                <p class="graish-color">{{__('app.total-funded-required')}}</p>
                <h5 class="text-blue text-center">PKR {{ $donation->price }}</h5>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-4 mt-2">
        @if($recievedDonation > $requiredDonation)
        @else
        @if(have_permission('Make-A-Donation'))
                <button class="blue-hover-bg theme-btn" onclick="donateNow({{$donation->id}})">{{__('app.donate-now')}}</button>
        @endif
        @endif
    </div>
</div>
@empty
<p>{{ __('app.no-data') }}</p>
@endforelse
