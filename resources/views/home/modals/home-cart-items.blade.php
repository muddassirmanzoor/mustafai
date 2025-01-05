
<div class="modal fade  library-detail common-model-style" tabindex="-1" role="dialog" id="home-cart-items">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{(__('app.cart-items'))}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="home-cart-partial">
             
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="green-hover-bg theme-btn" onclick="orderNow($(this))">Order Now</button> --}}
                {{-- <button class="theme-btn" href="javascript:void(0)" id="add-cart-session" onclick="addCartSession($('#product_id').val())">{{__('app.add-to-cart')}}</button> --}}
                <button type="button" class="green-hover-bg theme-btn" onclick="orderNowHome($(this))">{{__('app.order-now')}}</button>
            </div>
        </div>
    </div>
</div>