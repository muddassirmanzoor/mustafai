
<div class="row">
        <div class="col-md-4 mt-3">
            <div class="form-group">
                <label>{{ __('app.name') }}</label>
                <input class="form-control" type="text" name="full_name" value="{{$user->user_name}}" readonly>

            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="form-group">
                <label>{{ __('app.email') }}</label>
                <input class="form-control" type="text" name="email" value="{{$user->email}}" readonly>

            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="form-group">
                <label>{{ __('app.phone-number') }} </label>
                <input class="form-control" type="text" name="phone_number" value="{{$user->phone_number}}" readonly>

            </div>
        </div>
        <div class="col-md-4 mt-3 ">
            <div class="form-group">
                <label class="form-label">{{ __('app.blood-group') }}</label>
                <select name="blood_group"  id="blood_group"  class="form-control" placeholder="Enter Blood Group" required>
                    <option value="">{{ __('app.select-blood-group') }}</option>
                    <option value="A+" {{ auth()->user()->blood_group_english == 'A+' ? 'selected ' : '' }}>A+</option>
                    <option value="O+" {{ auth()->user()->blood_group_english == 'O+' ? 'selected ' : '' }}>O+</option>
                    <option value="B+" {{ auth()->user()->blood_group_english == 'B+' ? 'selected ' : '' }}>B+</option>
                    <option value="AB+" {{ auth()->user()->blood_group_english == 'AB+' ? 'selected ' : '' }} >AB+</option>
                    <option value="A-" {{ auth()->user()->blood_group_english == 'A-' ? 'selected ' : '' }}>A-</option>
                    <option value="O-" {{ auth()->user()->blood_group_english == 'O-' ? 'selected ' : '' }} >O-</option>
                    <option value="B-" {{ auth()->user()->blood_group_english == 'B-' ? 'selected ' : '' }}>B-</option>
                    <option value="AB-" {{ auth()->user()->blood_group_english == 'AB-' ? 'selected ' : '' }}>AB-</option>

                </select>
            </div>
        </div>
        {{-- <div class="col-md-4 mt-3 ">
            <div class="form-group">
                <label class="form-label">{{ __('app.city') }}</label>
                <select name="city_id"  id="district_id"  class="form-control" required>
                    <option value="">{{ __('app.select-city') }}</option>
                @foreach($cities as $city)
                    <option  value="{{ $city->id }}"> {{ $city->name_english }}</option>
                @endforeach

                </select>
            </div>
        </div> --}}
        <input type="hidden" name="city_id" value="{{ auth()->user()->city_id ? auth()->user()->city_id : '' }}">
        <div class="col-md-4 mt-3 form-group">
            <label class="form-label">{{ __('app.dob') }}</label>
            <input type="date"  class="form-control" placeholder="Enter DOB" max="9999-12-31" name="dob" value="" required="">
        </div>

</div>
