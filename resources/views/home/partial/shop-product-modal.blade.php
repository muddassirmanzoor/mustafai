<!-- shop now modal -->
<div class="modal fade library-detail common-model-style" id="shopNowModal" tabindex="-1" role="dialog"
     aria-labelledby="shopNowModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{__('app.shop_now')}}</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal"></button>

            </div>
            <div class="modal-body product_information">

            </div>
            <!-- miscellaneous data -->
            <h6 class="loading_product text-center w-100" style="display: none;color: red; margin-bottom: 50px">Loading Product...</h6>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="button" class="green-hover-bg theme-btn order_product" onclick="openOrderModal(2)">{{__('app.order-now')}}</button>
            </div>
        </div>
    </div>
</div>
