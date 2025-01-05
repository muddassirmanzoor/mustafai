@extends('home.layout.app')
@section('content')
@include('home.home-sections.banner')

<div class="csm-pages-wraper gallary-bg">

    <section class="container">
        <div class="row mb-5 justify-content-center align-items-center charity-don-wraper charity-content team-member-detail mt-md-5 mt-3 mb-md-5 mb-3">
            <div class="col-md-12">
                <img class="img-fluid short-description-img" src="{{getS3File($employeeSection->image)}}" alt="no image found" height="50px" width="100%">
            </div>
            <div class="col-md-12">
                <h4 class="mb-2 text-center">{{__('app.short-description')}}</h3>
                <p class="text-center">{{$employeeSection->short_description}}</p>
                <hr />
                <h5 class="mb-2 text-center">{{__('app.content')}}</h3>
                <span style="word-break: break-word text-center"> {!!$employeeSection->content!!} </span>
            </div>
        </div>
    </section>
    {{-- $id = employe_id  --}}
    {{-- library div  --}}
    <section class="mustafai-libarary">
        <input type="hidden" name="id" id="emp_id" value="{{$id}}" />
        <div class="container-fluid container-width">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex libaray-head">
                        <h3>{{ __('app.library-title') }}</h3>
                        {{-- <a class="green-hover-bg theme-btn ms-sm-5" href="javascript:void(0)" onclick="goToURL()">{{ __('app.view-library-title') }}</a> --}}
                    </div>
                    <div class="carousel-mustafai-content">
                        <div id="carouselExampleControls" class="carousel slide" data-interval="false">
                            @php
                            $libArray=array_chunk($libraryTypes->toArray(), 5, true);
                            @endphp
                            <div class="carousel-inner">
                                @foreach($libArray as $key1 => $type)
                                <div class="carousel-item {{$loop->iteration ==1? 'active':''}}">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" class="justify-content-md-start justify-content-center">
                                        @foreach($type as $key=>$val)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{  ($key == 0) ? 'active':'' }} lib-tab-headers lib-{{$val['id']}}" data-cl="lib-{{$val['id']}}" data-val="{{$val['id']}}" aria-selected="true" onclick="getLibrarySections('{{$val['id']}}','lib-{{$val['id']}}')">{{ ucfirst($val['title']) }}</button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                            {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button> --}}
                        </div>
                    </div>
                    <div class="tab-content mustafai-library-details-tabs position-relative" id="pills-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="pills-Image">
                            <div class="tab-info">
                                {{-- <p>{{ __('app.view-library-content') }}</p> --}}
                                <div class="fa-3x small-loader d-none" id="libe-preloader">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <div class="row home-lib-row" id="lib-tab-content">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('home.home-sections.get-in-touch')

@endsection

@push('footer-scripts')
<script>
    $(function() {
        var type = $('.lib-tab-headers.active').attr('data-val');
        var tabClass = $('.lib-tab-headers.active').attr('data-cl');
        getLibrarySections(type, tabClass);
    });

    function getLibrarySections(type = '', tabClass = '') {
        $('.lib-tab-headers').removeClass('active');
        $('.lib-tab-headers.' + tabClass).addClass('active');
        var id = $('#emp_id').val();
        $.ajax({
            type: 'get',
            url: '{{ URL("library-tabs") }}',
            beforeSend: function() {
                $('#lib-tab-content').addClass('d-none')
                $("#libe-preloader").removeClass('d-none')
            },
            data: {
                type: type,
                empId: id
            },
            dataType: 'JSON',
            success: function(data) {
                $("#libe-preloader").addClass('d-none')
                $('#lib-tab-content').removeClass('d-none')
                $('#lib-tab-content').html(data.html);
                if ($('.no-data-lib').length) {
                    $('.mustafai-libarary').addClass('mustafai-library-section');
                }else{
                    $('.mustafai-libarary').removeClass('mustafai-library-section');
                }
            }
        });
    }
</script>

@endpush