@extends('home.layout.app')

@push('styles')

@endpush

@section('content')
<section class="search finding-search csm-pages-wraper">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-12">

                <div class="card text-center">
                    <div class="card-header text_center">
                        <h4 class="card-title">{{ __('app.search-results') }}</h4>
                    </div>

                    <div class="card-body" id="search-details">
                        <p class='loading-txt'>{{ __('app.loading') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@include('home.home-sections.get-in-touch')
@endsection


@push('footer-scripts')
<script>
    var searchTypes = ['donations', 'products', 'events', 'pages'];
    var query = "{{ $_GET['s'] }}";

    var recordFound = 0;

    $(function() {
        load();

    })

    function load() {
        var type = '';
        if (searchTypes.length) {
            type = searchTypes[0];
            searchTypes.shift()
        }

        if (type) {
            $.ajax({
                type: "post",
                url: "{{route('search')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    query: query,
                    type: type
                },
                success: function(result) {
                    result = JSON.parse(result);
                    recordFound += result.total;
                    $('#search-details').prepend(result.html);
                    if (result.isLast) {
                        if (!recordFound) {
                            $('.loading-txt').html('No Record Found');
                        } else {
                            $('.loading-txt').remove();
                        }
                    }
                    if (searchTypes.length) {
                        load();
                    }
                }
            });
        }
    }
</script>
@endpush
