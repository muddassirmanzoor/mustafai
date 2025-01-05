@extends('home.layout.app')

@push('header-scripts')
<!--select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper mustafai-store-section mustafai-store-page-csm">
    <input type="hidden" id="auth" @auth value="1" @endauth @guest value="0" @endguest>
    <div class="container-fluid container-width">
        <input type="hidden" id="selected_category_filter" value="">
        <input type="hidden" id="selected_sort_filter" value="id asc">
        <input type="hidden" id="selected_search_filter" value="">
        <input type="hidden" id="type_filter" value="">
        <div class="store-list-tab">
            <div class="store-list">
                <div class="table-form store-form">
                    <form class="w-100 d-flex align-items-xl-center justify-content-between mb-3">
                        <div class="row flex-sm-row flex-column store-rows w-100">
                            {{-- <div class="col-sm-6">

                            </div> --}}
                            <div class="col-lg-4 col-sm-6 col-12 mb-sm-0 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{__('app.category')}}:</label>
                                    <select class="js-example-basic-single form-control category-filter" name="category_filter">
                                        <option value="">{{__('app.select') . __('app.category')}} </option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12 mb-xl-0 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{__('app.sort-by')}}:</label>
                                    <select class="js-example-basic-single form-control sorting-filter" name="sorting_filter">
                                        <option value="id ASC">{{__('app.newest-first')}}</option>
                                        <option value="id DESC">{{__('app.oldest-first')}}</option>
                                        <option value="price ASC"> {{__('app.cheapest-first')}}</option>
                                        <option value="price DESC"> {{__('app.expensive-first')}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12 mb-sm-0 mb-3 ">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{ __('app.product-type') }}:</label>
                                    <select class="js-example-basic-single form-control type_filter" name="type_filter">
                                        <option value="">{{ __('app.product-type') }}</option>
                                        <option value="0">{{ __('app.physical') }}</option>
                                        <option value="1">{{ __('app.virtual') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="row">
                        <div class="seach-items homestore-search col-lg-10 col-sm-6 col-6 mb-sm-0 mb-3">
                            <div class="form-group  d-flex align-items-center">
                                <label class="sort-form-select me-2">{{__('app.search')}}:</label>
                                <input type="text" name="search-prod-input" placeholder="{{ __('app.search') }}" id="search-prod-input" class="js-example-basic-single form-control search-prod-input">
                                <span class=" fa fa-search search-now home-store-search"></span>
                            </div>
                        </div>
                        <div class="seach-items col-lg-2 col-sm-6 col-6 mb-sm-0 mb-3">
                            <div class="cart producet-cart second-product-card" id="home-cart" onclick="getHomeCart()">
                                <span>
                                    <figure class="mb-0 cart-img"><img src="{{asset('user/images/cart-icon.png')}}" alt="" class="img-fluid"></figure>
                                </span>
                                <span class="cart-count" id="cart-count-home" style="visibility: visible;">
                                    @php $cartCollection = Cart::getContent();
                                    echo $cartCollection->count();
                                    @endphp
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mustafai-store">
                    <div class="row pb-lg-5 pb-3" id="store-section">
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
            </div>
        </div>
        @include('home.modals.order-product')
        @include('home.modals.home-cart-items')
    </div>
</div>
@include('home.home-sections.get-in-touch')
@endsection
@push('footer-scripts')
@include('home.scripts.store-script')
<script src="{{ asset('assets/home/js/donation.js') }}"></script>
<script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

</script>
@endpush
