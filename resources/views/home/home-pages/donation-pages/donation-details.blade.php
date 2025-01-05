@extends('home.layout.app')
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper mb-5">
    <section class="detail-doners-list donations">
        <div class="container-fluid container-width">
                <input type="hidden" id="selected_category_filter" value="">
                <form class="d-flex align-items-center justify-content-between">
                <div class="row flex-sm-row flex-column store-rows">
                    <div class="col-sm-12 col-12 mb-sm-0 mb-3">
                        <div class="form-group select-wrap d-flex align-items-center">
                            <label class="sort-form-select me-2">{{__('app.select-category')}}:</label>
                            <select class="js-example-basic-single form-control category-filter" name="category_filter">
                                <option value="">{{__('app.select-category')}}</option>
                                @foreach($donation_categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                </form>
                <div class="w-100 pb-lg-5 pb-3" id="donation-section">
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
    </section>
</div>
@include('home.home-sections.get-in-touch')
@include('home.modals.donate-now')
@endsection

@push('footer-scripts')
@include('home.scripts.donation-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/home/js/donation.js') }}"></script>
@endpush
