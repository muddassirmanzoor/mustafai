@extends('user.layouts.layout')

@section('content')
    <input id="queryString" type="hidden" value="{{ request()->get('q') }}">
    <div class="common-search dash-common-card">
        <h3 class="text-center">{{ __('app.search-results') }}</h3>
        <div id="search-results">
            <p class='loading-txt text-center'>{{ __('app.loading') }}</p>
            <ul>
                @foreach($results as $result)
                    <li><a href="{{ $result['link'] }}">{{ $result['title'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var searchTypes = ['posts', 'users', 'libraries', 'products'];
        var query = "{{ $_GET['q'] }}";

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
                    url: "{{route('user.search')}}",
                    data: {
                        query: query,
                        type: type,
                        '_token': "{{csrf_token()}}"
                    },
                    success: function(result) {
                        // recordFound += result.total;
                        $('#search-results').prepend(result.html);

                        if(result.total === 0)
                        {
                            var not_found='{{ __('app.no-data') }}';
                            $('.loading-txt').html(not_found);
                        }
                        else
                        {
                            $('.loading-txt').remove();
                        }
                        if (searchTypes.length) {
                            load();
                        }
                    }
                });
            }
        }

        function goToPage(_this)
        {
            let link = $(_this).attr('href')
            if (link == '#')
            {
                // window.location.href = 'mustafai-store?q='+encodeURIComponent('foo');
                window.location.href = 'mustafai-store?q='+encodeURIComponent($('#queryString').val());
            }
        }

    </script>
@endpush
