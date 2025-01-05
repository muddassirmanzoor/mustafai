
<div class="modal fade   common-model-style" tabindex="-1" role="dialog" id="become-donar">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form method="POST" id="donor_form_guest" action="{{ route('guest.donor') }}" enctype="multipart/form-data">
                @CSRF
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-green">{{ __('app.add-blood-donners') }}</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="become-donar-body">
                        <div class="row">
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label>{{ __('app.name') }}</label>
                                            <input class="form-control" type="text" name="full_name"  placeholder="{{ __('app.name') }}" required>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label>{{ __('app.email') }}</label>
                                            <input class="form-control" type="text" name="email"  placeholder="{{ __('app.email') }}" required>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label>{{ __('app.phone-number') }} </label>
                                            <input class="form-control" type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="phone_number" placeholder="{{ __('app.phone-number') }}"  required>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="form-label">City <span class="text-red">*</span></label>
                                            <select name="city_id"  id="city_id"  class="form-control" placeholder="Enter City" required>
                                                <option value="">{{ __('app.select-city') }}</option>
                                                @foreach($cities as $city)
                                                    <option  value="{{ $city->id }}" > {{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('app.blood-group') }}</label>
                                            <select name="blood_group"  id="blood_group"  class="form-control" placeholder="Enter Blood Group" required>
                                                <option value="">{{ __('app.select-blood-group') }}</option>
                                                <option value="A+" {{ 'echo' == 'A+' ? 'selected ' : '' }}>A+</option>
                                                <option value="O+" {{ 'echo' == 'O+' ? 'selected ' : '' }}>O+</option>
                                                <option value="B+" {{ 'echo' == 'B+' ? 'selected ' : '' }}>B+</option>
                                                <option value="AB+" {{ 'echo' == 'AB+' ? 'selected ' : '' }} >AB+</option>
                                                <option value="A-" {{ 'echo' == 'A-' ? 'selected ' : '' }}>A-</option>
                                                <option value="O-" {{ 'echo' == 'O-' ? 'selected ' : '' }} >O-</option>
                                                <option value="B-" {{ 'echo' == 'B-' ? 'selected ' : '' }}>B-</option>
                                                <option value="AB-" {{ 'echo' == 'AB-' ? 'selected ' : '' }}>AB-</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3 form-group">
                                        <label class="form-label">{{ __('app.dob') }}</label>
                                        <input type="date"  class="form-control" placeholder="Enter DOB" max="9999-12-31" name="dob" value="" required="">
                                    </div>
                                    <div class="col-md-6 mt-3 form-group">
                                        <label class="form-label">{{ __('app.image') }}</label>
                                        <input type="file"  class="form-control"  name="image" value="" >
                                    </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"  class="theme-btn" href="javascript:void(0)"  id="become-donar">{{__('app.add-blood-donners')}}</button>
                    </div>
                </div>
        </form>

    </div>
</div>
