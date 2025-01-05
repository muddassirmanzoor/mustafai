@foreach($products as $product)
    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
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
                <img src="{{ getS3File($image) }}" alt="Mustafai Item" class="img-fluid lazyrate" id="product-img-{{$product->id}}">
            </figure>
        </div>
        <div class="wrap-item-info">
            <p class="store-item-title text-center name-info" id="product-name-{{$product->id}}">{{ $product->name }}</p>
            <div class="d-flex flex-column justify-content-center align-items-center">
                @if (app()->getLocale()=='urdu')
                <span class="item-price green-color price-info" id="product-price-{{$product->id}}" style="direction: rtl;text-align: left !imporatnt;">{{ $product->price }} {{__('app.store-currency')}} </span>
                @else
                <span class="item-price green-color price-info" id="product-price-{{$product->id}}">{{ $product->price }} {{__('app.store-currency')}} </span>
                @endif
                <div class="d-flex justify-content-center justify-content-center">
                    <button class="theme-btn green-hover-bg" href="javascript:void(0)" data-desc="{{$product->description}}" onclick="openOrderModal({{ $product->id }},$(this))">{{__('app.add-to-cart')}}</button>
                    {{-- <button class="theme-btn" href="javascript:void(0)" onclick="addCartSession({{ $product->id }})">{{__('app.add-to-cart')}}</button> --}}
                </div>
            </div>
        </div>
    </div>
@endforeach
