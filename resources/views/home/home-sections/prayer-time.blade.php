<div class="col-xl-3">
    <div class="d-flex justify-content-xl-start justify-content-center align-items-xl-start align-items-center mb-lg-5 mb-3">
        <h4 class="text-xl-start text-center">{{__('app.prayer-times')}}</h4>
    </div>
    <div class="prayer-timer-card" id="namaz-time-section">
        <i class="fa fa-spinner" aria-hidden="true"></i>
    </div>
</div>
@include('home.modals.prayer-month')
@push('footer-scripts')

@endpush
