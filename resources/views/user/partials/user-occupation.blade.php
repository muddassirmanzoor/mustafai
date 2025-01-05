@if(auth()->user()->id == $user_id)
    <form method="post" id="userOcupationForm">
        @csrf
        @forelse($occupations as $occupation)
        <div class="parent-checkbox">
            <div class="iagree_radio form-check-new chck-boxe" id="parent_{{ $occupation->id }}">
                <input type="checkbox" name="ids[]" id="parent-id_{{ $occupation->id }}" onclick="parentFunction($(this))" class="req_q parent-check" value="{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'checked' : '' }} >
                <label for="parent-id_{{ $occupation->id }}">{{ $occupation->title }}</label>
            </div>
            <div class="child-checkbox">
            @if ($occupation->subProfession()->count())
                @foreach ( $occupation->subProfession as $child )
                    <div class="iagree_radio form-check-new chck-boxe parent-id_{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'style=display:block' : 'style=display:none'}} >
                        <input type="checkbox" name="ids[]" id="news-regarding_{{ $child->id }}" class="req_q" value="{{ $child->id }}" {{ in_array($child->id, $userOccupationIds) ? 'checked' : '' }} >
                        <label for="news-regarding_{{ $child->id }}">{{ $child->title }}</label>
                    </div>
                @endforeach
            @endif
            </div>
        </div>
        @empty
        <div class="parent-checkbox">
             {{__('app.no-data-available')}}
        </div>

        @endforelse
        <div class="iagree_radio form-check-new chck-boxe my_25">
            <input type="checkbox" name="check" id="other-profession-id" onclick="otherProfessionFunction($(this))" class="req_q" value="true">
            <label for="other-profession-id">{{ __('app.others') }}</label>
        </div>
        <div class="form-group" id="other-profession" style="display: none">
            <input type="text" name="other_profession" class="form-control" placeholder="{{ __('app.enter-profession') }}">
        </div>
    </form>
@else
    @forelse ($occupations as $occupation)
        @if(in_array( $occupation->id, $userOccupationIds) )
            <div class="parent-checkbox">
                <div class="iagree_radio form-check-new chck-boxe" id="parent_{{ $occupation->id }}">
                    <input type="checkbox" name="ids[]" id="parent-id_{{ $occupation->id }}" onclick="parentFunction($(this))" class="req_q parent-check" value="{{ $occupation->id }}" checked disabled>
                    <label for="parent-id_{{ $occupation->id }}">{{ $occupation->title }}</label>
                </div>
                <div class="child-checkbox">
                    @if ($occupation->subProfession()->count())
                        @foreach ( $occupation->subProfession as $child )
                            @if(in_array($child->id, $userOccupationIds))
                                <div class="iagree_radio form-check-new chck-boxe parent-id_{{ $occupation->id }}" {{ in_array( $occupation->id, $userOccupationIds) ? 'style=display:block' : 'style=display:none'}} >
                                    <input type="checkbox" name="ids[]" id="news-regarding_{{ $child->id }}" class="req_q" value="{{ $child->id }}" checked disabled>
                                    <label for="news-regarding_{{ $child->id }}">{{ $child->title }}</label>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
        @if(empty($userOccupationIds))
        <div class="parent-checkbox">
            {{__('app.no-data-available')}}
       </div>
       @php
         break;
       @endphp
        @endif
    @empty
    <div class="parent-checkbox">
        {{__('app.no-data-available')}}
   </div>

    @endforelse
    @php
        $occupationArray = $occupations->toArray();
        // Extract the occupation IDs from the occupation array
        $occupationIdsArray = array_column($occupationArray, 'id');
        // Find the common elements between the occupation IDs and the extracted IDs
        $commonOccupationIds = array_intersect($userOccupationIds, $occupationIdsArray);
    @endphp
    @if(empty($commonOccupationIds) && !empty($userOccupationIds))
        <div class="parent-checkbox">
            {{__('app.no-data-available')}}
        </div>
    @endif
@endif

