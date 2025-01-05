@extends('home.layout.app')

@push('header-scripts')
<!--select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <!-- Flipbook StyleSheet -->
     <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

     <!-- Icons Stylesheet -->
     <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />
@endpush
@section('content')
@include('home.home-sections.banner')
<div class="csm-pages-wraper mustafai-store-section mustafai-store-page-csm">
    <input type="hidden" id="auth" @auth value="1" @endauth @guest value="0" @endguest>
    <div class="container-fluid container-width">
        <input type="hidden" id="selected_category_filter" value="">
        <input type="hidden" id="selected_sort_filter" value="">
        <input type="hidden" id="selected_search_filter" value="">
        <div class="store-list-tab">
            <h3 class="cm-head text-capitalize text-center">{{ __('app.magazines') }}</h3>
            <div class="store-list w-100">
                <div class="table-form store-form magzine-pg w-100">
                    <form class="w-100">
                        <div class="row flex-sm-row flex-column store-rows w-100 d-flex align-items-xl-center justify-content-between mt-3">
                            {{-- <div class="col-sm-6">

                            </div> --}}
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center  w-100">
                                    <label class="sort-form-select me-2">{{__('app.category')}}:</label>
                                    <select class="js-example-basic-single magzine-select form-control category-filter w-100" name="category_filter">
                                        <option value="">{{__('app.select') . __('app.category')}} </option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- sort by name  --}}
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center w-100">
                                    <label class="sort-form-select me-2">{{__('app.sort-by')}}:</label>
                                    <select class="js-example-basic-single magzine-select form-control sort-filter w-100" name="sortBy" id="sortBy">
                                        <option value="">{{__('app.select') . __('app.sort-by')}} </option>
                                        {{-- <option value="title_{{lang()}} ASC">Name Ascending</option>
                                        <option value="title_{{lang()}} DESC">Name Descending</option> --}}
                                        <option value="created_at ASC">{{__('app.oldest-first')}}</option>
                                        <option value="created_at DESC">{{__('app.newest-first')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="seach-items homestore-search col-lg-7 col-sm-10 mt-2 mb-5">
                                <div class="form-group d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{__('app.search')}}:</label>
                                    <input type="text" name="search-input-input" placeholder="{{ __('app.search') }}" id="search-input-magzine" class="js-example-basic-single form-control search-input-magzine">
                                    <span class="fa fa-search search-now home-store-search"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mustafai-store">
                    <div class="row pb-lg-5 pb-3" id="magazine-section">
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center load-more-sections" id="load-more-sec" style="visibility:hidden;">
                        <button type="button" id="load-lib" class="theme-btn-borderd-btn theme-btn text-inherit" onclick="loadMore()">{{ __('app.view-more') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade common-model-style" id="detail-dir-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-bodyy" id="modal-data">
                    <div class=" d-flex justify-content-end libray-modal-btn-close">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.home-sections.get-in-touch')
@endsection
@push('footer-scripts')
@include('home.scripts.magazine-script')
<!-- Flipbook main Js file -->
<script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
<script src="{{ asset('assets/admin/select2/js/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single, magzine-select').select2();
    });

    function getlibApiPreviwer(_this) {
        // alert('hi');
        return false;
        var src = _this.attr('data-src');
        $('#detail-dir-modal').modal('show')
        $('#detail-dir-modal').find('.modal-body').html('<div class="_df_thumb" id="df_manual_book" source="' + src + '" width="100%" height="500vh" style="border: none;" allowFullScreen> </iframe>')
    }
</script>
@endpush
