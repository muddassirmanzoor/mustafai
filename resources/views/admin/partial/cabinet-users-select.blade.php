<div class="row">
    <div class="col-sm-4">
        <div class="form-group user-sec">
            <select class="select2 user-inputs user-only form-control js-example-basic-single" name="users[]" id=""
                required
                data-dropdown-css-class="select2-purple"
                style="width: 100%;">
                <option value="">Select User</option>
                @foreach ($users as $user)
                    @php
                        $name = $user->user_name;
                        if (!empty($user->role)) {
                            $name .= ' (' . $user->role->name_english . ')';
                        }
                    @endphp
                    <option value="{{ $user->id }}"> {{ $name }} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <select class="select2 user-inputs form-control" name="designations[]" required>
                <option value="">Select Designation</option>
                @foreach($designations as $rol)
                    <option value="{{$rol->id}}">{{$rol->name_english}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-danger" type="button" onclick="removeRow($(this))">Remove</button>
    </div>
</div>
