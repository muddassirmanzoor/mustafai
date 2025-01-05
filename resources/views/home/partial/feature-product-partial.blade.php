@if (!empty($product))
    <h4 class="text-captilize mb-4" id="product-name-{{ $product->id }}">{{ $product->name }}</h4>
    <span class="item-price green-color price-info d-none"
        id="product-price-{{ $product->id }}">RS:{{ $product->price }}</span>

    <div class="download-book">
        @if(isset($product->productImages[0]->file_name))
        @if (\Storage::disk('s3')->exists($product->productImages[0]->file_name))
 
        <div class="image-contain">
            <img id="product-img-{{ $product->id }}" src="{{ isset($product->productImages[0]->file_name) ? getS3File($product->productImages[0]->file_name) : '' }}"
                alt="image not found" class="img-fluid" />
        </div>
        @else
        <div class="image-contain">
            <img id="product-img-{{ $product->id }}" src="{{ getS3File('images/product-placeholder-image.png') }}"
                alt="image not found" class="img-fluid w-100" />
        </div>
        @endif
        @else
        <div class="image-contain">
            <img id="product-img-{{ $product->id }}" src="{{ getS3File('images/product-placeholder-image.png') }}"
                alt="image not found" class="img-fluid w-100" />
        </div>
        @endif
        <div class="team-info d-flex justify-content-center align-items-center">
            @if ($product->file_type == 1)
                <a href="{{ asset($product->file_name) }}" download=""><button
                        class="theme-btn">{{__('app.download')}}</button></a>
            @else
                <a href="{{route('guest.store')}}"><button class="theme-btn">{{__('app.buy')}}</button></a>
            @endif
        </div>
    </div>
@endif
