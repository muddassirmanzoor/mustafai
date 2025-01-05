<form action="{{ route('admin.product-post') }}" method="post" enctype="multipart/form-data" id="productForm">
    @csrf
    <div class="form-group">
        <label class="control-label">Product Name</label>
        <div>
            {{$names['english']}}
            <input type="hidden" name="title_english" value="{{ $names['english'] }}">
            <input type="hidden" name="title_urdu" value="{{ $names['urdu'] }}">
            <input type="hidden" name="title_arabic" value="{{ $names['arabic'] }}">
            <input type="hidden" name="product_id" value="{{ $id }}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Product Description</label>
        <div>
            {{$descriptions['english']}}
            <input type="hidden" name="description_english" value="{{ $descriptions['english'] }}">
            <input type="hidden" name="description_urdu" value="{{ $descriptions['urdu'] }}">
            <input type="hidden" name="description_arabic" value="{{ $descriptions['arabic'] }}">
            <input type="hidden" name="price" value="{{ $price }}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Images</label>
        <div class="row">
            @forelse($images as $image)
                <input type="hidden" name="images[]" value="{{ $image }}">
                <div class="col-sm-4">
                    <!-- <span>X</span> -->
                    <img src="{{ getS3File($image) }}" alt="" width="150" height="150">
                    <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 150 x 150 </span> pixels</small>
                </div>
            @empty
                <p>
                    <h3>No images</h3>
                    <img src="https://www.freeiconspng.com/uploads/no-image-icon-6.png" alt="" width="150" height="150">
                </p>
            @endforelse
        </div>
    </div>
</form>
