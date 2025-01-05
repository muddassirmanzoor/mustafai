<ul>
    @foreach($childs as $child)
        <li>
            {{ $child->name_english }}
            <input type="radio" name="parent_id" value="{{$child->id}}">
            @if(count($child->childs))
                @include('admin.cabinet.manageChild',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>