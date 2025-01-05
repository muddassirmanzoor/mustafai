@forelse ($searches as $search)
@if($type == 'donations')
<div search-events>
    <div class="search-info mb-2">
        <a color="graish-color" href="{{URL('our-donations#donation-'.$search->id)}}">
            <h5 class="mb-2">{{ $search->title }}</h5>
            <p>{!! $search->description !!}</p>
        </a>
    </div>
    <hr />
    @elseif($type == 'products')
    <div class="search-info mb-2">
        <a color="graish-color" href="{{URL('mustafai-store#product-'.$search->id)}}">
            <h5 class="mb-2">{{ $search->name }}</h5>
            <p>{!! $search->description !!}</p>
        </a>
    </div>
    <hr />

    @elseif($type == 'events')
    <div class="search-info mb-2">
        <a color="graish-color" href="{{URL('events#event-'.$search->id)}}">
            <h5 class="mb-2">{{ $search->title }}</h5>
            <p>{!! $search->content !!}</p>
        </a>
    </div>
    <hr />

    @elseif($type == 'pages')
    <div class="search-info mb-2">
        <a color="graish-color" href="{{URL($search->url)}}">
            <h5 class="mb-2">{{ $search->title }}</h5>
            <p>{!! $search->short_description !!}</p>
        </a>
    </div>
    <hr />
</div>

@endif
@empty
@endforelse