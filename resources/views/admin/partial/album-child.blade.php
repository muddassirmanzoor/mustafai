<ul style="list-style: none;">
    @foreach($childs as $child)
        <li>
            <input type="checkbox" name="albums[][{{ $type_id }}]" value="{{$child->id}}" {{ (in_array($child->id,$assigned_albums)) ? 'checked':'' }}>
            {{ $child->title_english }}
            @if(count($child->childrens->toArray()))
                @include('admin.partial.album-child',['childs' => $child->childrens])
            @endif
        </li>
    @endforeach
</ul>