
<select class="select2 user-inputs form-control" name="role_id" required>
    <option value="">{{__('app.select-role')}}</option>
        @foreach($roles as $role)
            @php
            
            @endphp
            <option value="{{$role->id}}" >{{$role->name}}</option>
        @endforeach
</select>


