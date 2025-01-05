<ul>
    @foreach($results as $result)
        <li><a href="{{ $result['link'] }}" onclick="goToPage(this)">{{ $result['title'] }}</a></li>
    @endforeach
</ul>
