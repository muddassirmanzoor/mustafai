@foreach ($occupations as $occupation)

@if(in_array( $occupation->id, $userOccupationIds) )
    <div class="parent-checkbox">
        <div class="iagree_radio form-check-new chck-boxe" id="parent_{{ $occupation->id }}">
            <input type="checkbox" name="ids[]" id="parent-id_{{ $occupation->id }}" onclick="parentFunction($(this))" class="req_q parent-check" value="{{ $occupation->id }}" checked disabled>
            <label for="parent-id_{{ $occupation->id }}">{{ $occupation->title_english }}</label>
        </div>
        <div class="child-checkbox ml-2">
            @if ($occupation->subProfession()->count())
                @foreach ( $occupation->subProfession as $child )
                    @if(in_array($child->id, $userOccupationIds))
                        <div class="iagree_radio form-check-new chck-boxe parent-id_{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'style=display:block' : 'style=display:none'}} >
                            <input type="checkbox" name="ids[]" id="news-regarding_{{ $child->id }}" class="req_q" value="{{ $child->id }}" checked disabled>
                            <label for="news-regarding_{{ $child->id }}">{{ $child->title_english }}</label>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endif
@endforeach

