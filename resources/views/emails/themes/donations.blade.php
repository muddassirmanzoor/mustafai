@extends('user.layouts.layout')

@section('content')
<input type="hidden" id="selected_category_filter" value="">
<form class="d-flex align-items-center justify-content-between donations-user-detail">
    <div class="row flex-sm-row flex-column store-rows w-100 donate-type-filter">
        <div class="col-12 mb-sm-0 mb-3">
            <div class="form-group select-wrap d-flex align-items-center">
                <label class="sort-form-select me-2">{{__('app.category')}}:</label>
                <select class="js-example-basic-single form-control category-filter" name="category_filter">
                    <option value="">{{__('app.all')}}</option>
                    @foreach($donation_categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select> 
            </div>
        </div>
    </div>
</form>
<div class="donations">
    <div class="row pb-lg-5 pb-3 text-center" id="donation-section">
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center load-more-sections" id="load-more-sec" style="visibility:hidden;">
        <a class="unloaded d-flex flex-column justify-content-center align-items-center" onclick="loadMore()">
            {{__('app.load-more')}}
            <span class="load-down">
                <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </span>
        </a>
    </div>
</div>

@include('home.modals.donate-now')

@endsection

@push('scripts')
@include('user.scripts.donation-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/home/js/donation.js') }}"></script>
@endpush

 