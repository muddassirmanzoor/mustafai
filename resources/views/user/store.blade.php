@extends('user.layouts.layout')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
@endpush
@section('content')
@php $cartCount = new App\Models\Store\Cart(); $cartCount = $cartCount->getCartCounter(); @endphp
<input type="hidden" id="selected_category_filter" value="">
<input type="hidden" id="selected_sort_filter" value="id asc">
<input type="hidden" id="selected_search_filter" value="">
<input type="hidden" id="type_filter" value="">
<div class="userlists-tab">
    <div class="store-list">
        <div class="table-form store-form">
            <form action="" class="store-post-form">
                <input id="queryString" type="hidden" value="{{ request()->has('q') ? request()->get('q') : '' }}">
                <div class="d-flex align-items-center">
                    <div class="table-search me-lg-5 me-3 w-100">
                        <input type="text" class="form-control border-0 shadow-none w-100 search-prod-input" placeholder="{{__('app.search')}}">
                        <span class="search-icon fa fa-search search-now"></span>
                    </div>
                    <div class="cart" onclick="getCart($(this))">
                        <span>
                            <figure class="mb-0 cart-img"><img src="./images/cart-icon.png" alt="" class="img-fluid"></figure>
                        </span>
                        <span class="cart-count" id="cart-count" style="visibility:hidden">{{$cartCount}}</span>
                    </div>
                </div>
            </form>
            <form class="d-flex align-items-center justify-content-between">
                <div class="row flex-sm-row flex-column store-rows store-0">
                    <div class="col-sm-4 col-12 mb-sm-0 mb-3">
                        <div class="form-group select-wrap d-flex align-items-center">
                            <label class="sort-form-select me-2">{{__('app.category')}}:</label>
                            <select class="js-example-basic-single form-control category-filter" name="category_filter">
                                <option value="">{{__('app.category')}}</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 mb-sm-0 mb-3">
                        <div class="form-group select-wrap d-flex align-items-center">
                            <label class="sort-form-select me-2">{{__('app.sort-by')}}:</label>
                            <select class="js-example-basic-single form-control sorting-filter" name="sorting_filter">
                                <option value="id DESC">{{__('app.newest-first')}}</option>
                                <option value="id ASC">{{__('app.oldest-first')}}</option>
                                <option value="price ASC">{{__('app.cheapest-first')}}</option>
                                <option value="price DESC">{{__('app.expensive-first')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 mb-sm-0 mb-3">
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

<div class="modal fade profile-modlal library-detail common-model-style" tabindex="-1" role="dialog" id="add-to-cart">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{__('app.add-to-cart')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-5 d-flex justify-content-center align-items-center">
                        <div class="form-group" id="reciept-img-sec">
                            <img id="add-cart-img-box" src="{{ asset('/images/products-images/default.png') }}" alt="" class="img-fluid" id="default-img-reciept">
                        </div>
                    </div>
                    <div class="col-lg-7 mb-4">
                        <div class="product-detail">
                            <h5 id="prod-name-mod"></h5>
                            <p id="desc"></p>
                            <p id="les-desc"></p>
                            {{-- <a href="javascript:void(0)" id="read_more">{{__('app.read-more')}}</a> --}}
                            <p class="mt-1 text-green" id="prod-price-mod"></p>
                            <p class="mt-1 text-green" id="prod-price-base-mod" style="visibility: hidden;"></p>
                        </div>
                        <hr>
                        <form id="cart-form">
                            <input type="hidden" name="product_id" id="product_id">
                            <div class="quantity buttons_added">
                                <span type="button" class="minus" onclick="changeQunaity(0)">-</span>
                                <input type="text" class="input-text qty text" value="1" id="quanity-input" maxlength="4">
                                <span type="button" class="plus" onclick="changeQunaity(1)">+</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="green-hover-bg theme-btn" onclick="addToCart($(this))">{{__('app.add-to-cart')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade  profile-modlal library-detail common-model-style" tabindex="-1" role="dialog" id="cart-deatails">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{__('app.cart-items')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12" id="cart-items-detail">

                    </div>
                </div>
            </div>
            @if(have_permission('Order-Now-Items'))
            <div class="modal-footer">
                <button type="button" class="green-hover-bg theme-btn" onclick="orderNow($(this))">{{__('app.order-now')}}</button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
@include('user.scripts.store-script')
<script src="{{ asset('assets/home/js/donation.js') }}"></script>
<script>
    function checkAddress(_this) {
        if (_this.checked) {
            $('.ship-address').css('display', 'none');
            return;
        }
        $('.ship-address').css('display', 'block')
    }

    function getShipmentCharges() {
        // alert("ok");
        var totalPrice = 0;
        var count = 0;
        var totalQuantity = 0
        $(".all-check-product").each(function() {
            if ($(this).is(":checked")) {
                totalPrice = parseInt(totalPrice) + parseInt($(this).attr('data-total-price'));
                if ($(this).attr('data-attr') == 'true') {
                    // alert("true");
                    count = parseInt(count + 1);
                }
                totalQuantity = totalQuantity + parseInt($(this).attr('data-quantity'));
            }

        })
        if (count > 0) {
            var gradTotal = parseInt(totalPrice) + parseInt($("#shipment_rate").val());
            var shipment_charges = parseInt($("#shipment_rate").val());
        } else {
            var gradTotal = totalPrice;
            var shipment_charges = 0;
        }
        if (gradTotal > 0) {
            $('#payment-section').css('display', 'block');
        } else {
            $('#payment-section').css('display', 'none');
        }
        $(".cart_count_td").html(totalQuantity);
        $(".total_td").html(totalPrice);
        $(".shipment_td").html(shipment_charges);
        $(".grand_total_td").html(gradTotal);

        $(".cart_count_td").val(totalQuantity);
        $(".total_td").val(totalPrice);
        $(".shipment_td").val(shipment_charges);
        $(".grand_total_td").val(gradTotal);

    }

</script>
@endpush
