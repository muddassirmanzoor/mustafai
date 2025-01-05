@foreach($products as $product)
    <input type="hidden" name="product_ids[]"  value="{{ $product->id }}">
    <input type="hidden" id="product_type_{{$product->id}}" value="{{$product->product_type}}">

    @php
        if((isset($product->productImages[0]))){
        if(\Storage::disk('s3')->exists($product->productImages[0]->file_name)){
            $image = (isset($product->productImages[0])) ? $product->productImages[0]->file_name : 'images/products-images/default.png';
        }
        else {
            $image ='images/products-images/default.png';
        }
    }
    else{
        $image ='images/products-images/default.png';
    }
    @endphp
    <div class="store-card">
        <div class="mustafai-item">
            <figure class="mb-0">
                <img src="{{ getS3File($image) }}" alt="Mustafai Item" class="img-fluid lazyrate">
            </figure>
        </div>
        <div class="wrap-item-info">
            <p class="store-item-title text-center product-name-info">{{ $product->name }}</p>
            <div class="d-flex flex-column justify-content-center align-items-center">
                @if (app()->getLocale()=='urdu')
                <span class="item-price green-color product-price-info" style="direction: rtl;text-align: left !imporatnt;">{{ $product->price }} {{__('app.store-currency')}}</span>
                @else
                <span class="item-price green-color product-price-info">{{ $product->price }} {{__('app.store-currency')}}</span>
                @endif
                <div class="d-flex justify-content-center justify-content-center">
                    @if(have_permission('Add-To-Cart-Products'))
                        <button class="theme-btn-borderd-btn outline-bg theme-btn" data-desc="{{$product->description}}" data-less-desc={{mb_strimwidth($product->description, 0, 300, ".......")}} href="javascript:void(0)" onclick="addCartModal($(this),{{ $product->id }})">{{__('app.add-to-cart')}}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
