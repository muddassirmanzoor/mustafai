
<div class="modal fade  library-detail common-model-style" tabindex="-1" role="dialog" id="order-model">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{ __('app.order-product') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="add-to-cart_body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-5 d-flex justify-content-center align-items-center">
                        <div class="form-group" id="reciept-img-sec">
                            <img loading = "lazy" id="add-cart-img-box" src="{{ asset('/images/products-images/default.png') }}" alt="" class="img-fluid" id="default-img-reciept">
                        </div>
                    </div>
                    <div class="col-lg-7 mb-4">
                        <div class="product-detail">
                            <h5 id="prod-mod-name"></h5>
                            <p id="desc"></p>
                            <p class="mt-1 text-green" id="prod-mod-price"></p>
                            <p class="mt-1 text-green" id="prod-mod-price-base" style="visibility: hidden;"></p>
                        </div>
                        <hr>
                        <form id="order-form">
                            <input type="hidden" name="product_id" id="product_id" required>
                            <div class="quantity buttons_added">
                                <span type="button" class="minus" onclick="changeQunaity(0)">-</span>
                                <input type="text" class="input-text qty text" value="1" name="quantity" id="quanity-input" maxlength="4">
                                <span type="button" class="plus" onclick="changeQunaity(1)">+</span>
                            </div>
                            <hr>
                            {{-- <input type="file" name="order_reciept" required> --}}
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="green-hover-bg theme-btn" onclick="orderNow($(this))">Order Now</button> --}}
                <button class="theme-btn" href="javascript:void(0)"  id="add-cart-session" onclick="addCartSession($('#product_id').val())">{{__('app.add-to-cart')}}</button>

            </div>
        </div>
    </div>
</div>
