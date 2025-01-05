<form action="{{ route('order.product') }}" method="post" id="shopProductForm" enctype="multipart/form-data">
    @csrf
    <div class="shop-now table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('app.name')}}</th>
                    <th>{{__('app.image')}}</th>
                    <th>{{__('app.price')}}</th>
                    <th>{{__('app.quantity')}}</th>
                    <th>{{__('app.total')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $product->name_english }}</td>
                    <td><img style="width: 75px;height: 60px" src="{{ getS3File($product->productImages->first()->file_name) }}" alt="image"></td>
                    <td>
                        {{ $product->price }}
                        <input type="hidden" name="price" value="{{ $product->price }}" class="product_actual_price">
                        <input type="hidden" name="product_id" value="{{ $product->id }}" class="product_id">
                    </td>
                    <td>
                        <div class="quantity buttons_added">
                            <span type="button" class="minus" onclick="changeQuantity(0)">-</span>
                            <input type="number" class="input-text text qty product_quantity" value="1" id="quantity-input" name="quantity" readonly>
                            <span type="button" class="plus" onclick="changeQuantity(1)">+</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" value="{{ $product->price }}" name="total" class="product_total_price_val " readonly>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column mt-3">
        <label>{{__('app.payment-receipt')}}</label>
        <input type="file" name="receipt" id="receiptFile" accept="image/*">
    </div>

</form>
