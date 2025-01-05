@php
$dynamiclist ='';
@endphp
@foreach ($magazines as $magazine)
<input type="hidden" name="magazine_ids[]" value="{{ $magazine->id }}">
@php


if (isset($magazine->thumbnail_image)) {
if (\Storage::disk('s3')->exists($magazine->thumbnail_image)) {
$image = isset($magazine->thumbnail_image) ? $magazine->thumbnail_image : 'images/thumbnails/documents.png';
} else {
$image = 'images/thumbnails/documents.png';
}
} else {
$image = 'images/thumbnails/documents.png';
}
@endphp
<div class="lib-data-dynamically col-xxl-2 col-xl-3 col-md-4 col-sm-6 mb-lg-4 mb-3">
    <div class="_df_custom" backgroundcolor="teal" href="#" source="{{getS3File($magazine->file)}}">
        <a href="#" onclick="getlibApiPreviwer($(this))" data-src="{{getS3File($magazine->file)}}" data-attr="{{$magazine->id}}">
            <div class="image-galary">
                <img src="{{ getS3File($image) }}" alt="image not found" class="img-fluid" />
                <p style="display: none">{{ $magazine->description }}</p>
                <div class="seacrh-btn" data-val='img' data-id="{{ $magazine->id }}" data-src="{{ getS3File($magazine->file) }}"></div>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <p class="text-center">{{ $magazine->title }}</p>
            </div>
        </a>
    </div>
</div>

@endforeach
