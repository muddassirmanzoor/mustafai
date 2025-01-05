

<form action="{{ route('cabinet.users.update') }}" method="post" id="updateUsersForm">
        @csrf
        <ul>
            @foreach($cabinet as $user)
                <li>{{ $user->user->user_name }}
                    <input type="hidden" name="user_id[]" value="{{ $user->user->id}}">
                    <select name="designation_id[]" class="ml-2 mt-2">
                        <option value="">--Select Designation--</option>
                        @foreach($userDesignations as $key=>$designation)                      
                         <option value="{{$designation->id}}" {{($designation->id == $user->user->designation_id)?'selected':''}}>{{$designation->name_english}}</option>
                        @endforeach
                    </select>
                </li> 
            @endforeach
        </ul>
        <input type="hidden" name="cabinet_id" value="{{ $cabinetId }}">
        <label>Add Users</label>
        <div class="form-group">
            <select id="caninet-user-list" class="select2" name="users[]" required multiple data-dropdown-css-class="select2-purple" style="width: 100%;">
                <option value="">Select User</option>
                @foreach($users as $user)
                    @php
                        $name = $user->user_name;
                        if(!empty($user->role))
                        {
                            $name .= ' ('.$user->role->name_english.')';
                        }
                    @endphp
                    @if(in_array($user->id, $cabinet_users_ids))
                        <option value="{{ $user->id }}" selected="true">{{ $name }}</option>
                    @else
                        <option value="{{$user->id}}"> {{ $name }} </option>
                    @endif
                @endforeach
            </select>
        </div>
    </form>
