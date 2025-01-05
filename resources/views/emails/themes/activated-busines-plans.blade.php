@extends('user.layouts.layout')

@push('styles')
<style>
    .bussiness-collapse-row.d-flex.justify-content-between.align-items-center.mt-3.field-error {
        color: red;
    }
    .fondation-pdf-form label.error{
        color: transparent;
        display: none;
    }
    .fondation-pdf-form label.error:after{
        content:'*'; 
        color: red;
        display: none;
    }
    input.invalid-field{
        border-bottom: 1px solid red !important;
        background-color: #e8c1c1;
    }
</style>
@endpush
@section('content')

<div class="plantypes-icons d-flex flex-sm-row flex-column"> 
    <a href="{{URL('user/busines_plans?type=1')}}" class="btn add-more-btn mt-sm-0 mt-2">Upcomming Business Plans</a>
    <a href="{{URL('user/busines_plans?type=2')}}" class="btn add-more-btn ms-sm-2 mt-sm-0 mt-2">My Activated Plans</a>
</div>


@forelse($plans as $plan)
<div class="charity-content request-pg dash-common-card dashboard-donatec accent-blue business-plan mb-3" id="feature-magzine-section">
    <div class="charity-head d-flex">
        <h4 class="text-red">
            {{ $plan->name_english }}
        </h4>
    </div>

    <div class="plan-info">
        <div class="d-flex justify-content-between mb-1">
            <p class="plan-info-det"><strong>Type :</strong></p>
            <p class="plan-info-det">
                @if($plan->type == 1)   
                    Monthly
                @elseif($plan->type == 2)
                    Weekly
                @else
                    Daily
                @endif
            </p>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <p class="plan-info-det">
                <strong>Invoice Amount:</strong>
            </p>
            <p class="plan-info-det">{{ $plan->invoice_amount }}</p>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <p class="plan-info-det">
                <strong>Total Invoice :</strong>
            </p>
            <p class="plan-info-det">{{ $plan->total_invoices }}</p>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <p class="plan-info-det">
                <strong>Total Users :</strong>
            </p>
            <p class="plan-info-det">
                {{ $plan->total_users }}
            </p>
        </div>
    </div>
    <div class="total-application d-flex justify-content-between mb-1">
        <p class="plan-application-det">
            <strong>Total application :</strong>
        </p>
        <p class="plan-application-det">{{ $plan->applications()->count() }}</p>
    </div>
    <p class="text-donars-1 graish-color">{!! $plan->description_english !!}</p>
    <div class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-4 mt-2">
        @if(in_array($plan->id,$applied))
            <button class="blue-hover-bg theme-btn apply-now-modal applied">Applied</button>
        @else 
            <button class="blue-hover-bg theme-btn apply-now-modal" onclick="applyNowModal({{$plan->id}})">Apply Now</button>
        @endif
    </div>
</div>
@empty
<div class="dash-common-card mt-4">
    <p>No data yet!</p>
</div>

@endforelse

@endsection

@push('scripts')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush