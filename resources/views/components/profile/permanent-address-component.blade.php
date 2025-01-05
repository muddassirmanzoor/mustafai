<!-- permanent address -->
<h3 class="text-center">{{ __('app.permanent-address') }} <span class="text-red">*</span></h3>
@foreach(activelangs() as $key=>$val)

<div class="form-group p-2">
    <input class="form-control current-address" type="text" name="permanent_address_{{strtolower($val->key)}}"
           id="permanent_address_{{strtolower($val->key)}}" placeholder="{{ __('app.address-'.strtolower($val->key).'-permanent') }}"
           required value="{{$permanentAddress->{'permanent_address_'.strtolower($val->key)} ?? '' }}">
</div>
@endforeach


<div class="row mt-3 profile-update-address">
    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.country') }}<span class="text-danger">*</span> :</label>
            <select onchange="updateProvince(this, true)" name="country_id_permanent"
                    class="js-example-basic-single profile-update-address profile-update-addressform-control current-address"
                    style="width: 100%;" id="country_id_permanent" required>
                <option value="">{{ __('app.select-country') }}</option>
                @forelse($countries as $country)
                    <option value="{{ $country->id }}"
                        {{-- {{ $permanentAddress->country_id_permanent ?? 0 == $country->id ? 'selected' : '' }} --}}
                         {{  optional($permanentAddress)->country_id_permanent == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @empty
                    <option value="">{{ __('app.no-country') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="country_id_permanent-error " class="error" for="country_id_permanent"></label>

        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12  mb-3 province_select_div_permanent">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.province') }}<span class="text-danger">*</span> :</label>
            <select onchange="updateDivisions(this, true)" name="province_id_permanent" id="province_select_permanent"
                    class="js-example-basic-single  profile-update-addressform-control current-address"
                    style="width: 100%;" required>
                <option value="">{{ __('app.select-province') }}</option>
                @forelse($provinces as $province)
                    <option value="{{ $province->id }}"
                        {{-- {{ $permanentAddress->province_id_permanent ?? 0 == $province_id_permanent->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->province_id_permanent == $province->id ? 'selected' : '' }}>
                        {{ $province->name }}</option>
                @empty
                    <option value="">{{ __('app.no-province') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="province_select_permanent-error " class="error" for="province_select_permanent"></label>

        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12 mb-3 division_select_div_permanent" style="display: {{ $permanentAddress ? $permanentAddress->country_id_permanent == 1 ? '' : 'none' : '' }}">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.division') }}<span class="text-danger">*</span> :</label>
            <select name="division_id_permanent" id="division_select_permanent" onchange="updateDistricts(this, true)"
                    class="js-example-basic-single  profile-update-addressform-control current-address"
                    style="width: 100%;" required>
                <option value="">{{ __('app.select-division') }}</option>
                @forelse($divisions as $division)
                    <option value="{{ $division->id }}"
                        {{-- {{ $permanentAddress->division_id_permanent ?? 0 == $division->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->division_id_permanent == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}</option>
                @empty
                    <option value="">{{ __('app.no-division') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="division_select_permanent-error " class="error" for="division_select_permanent"></label>

        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.city') }} :</label>
            <select name="city_id_permanent" id="city_select_permanent"
                    class="js-example-basic-single  profile-update-addressform-control current-address"
                    style="width: 100%;">
                <option value="">{{ __('app.select-city') }}</option>
                @forelse($cities as $city)
                    <option value="{{ $city->id }}"
                        {{-- {{ $permanentAddress->city_id_permanent ?? 0 == $city->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->city_id_permanent == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}</option>
                @empty
                    <option value="">{{ __('app.no-city') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="city_id_permanent-error " class="error" for="city_id_permanent"></label>

        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12  mb-3 district_select_div_permanent" style="display: {{ $permanentAddress ? optional($permanentAddress)->country_id_permanent == 1 ? '' : 'none' : '' }}">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.district') }} <span class="text-danger">*</span>:</label>
            <select name="district_id_permanent" id="district_select_permanent" onchange="updateTehsil(this, true)"
                    class="js-example-basic-single  profile-update-addressform-control current-address"
                    style="width: 100%;" required>
                <option value="">{{ __('app.select-district') }}</option>
                @forelse($districts as $district)
                    <option value="{{ $district->id }}"
                        {{-- {{ $permanentAddress->district_id_permanent ?? 0 == $district->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->district_id_permanent == $district->id ? 'selected' : '' }}>
                        {{ $district->name }}</option>
                @empty
                    <option value="">{{ __('app.no-district') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="district_select_permanent-error " class="error" for="district_select_permanent"></label>

        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12 mb-3 tehsil_select_div_permanent" style="display: {{ $permanentAddress ? optional($permanentAddress)->country_id_permanent == 1 ? '' : 'none' : '' }}">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.tehsil') }}<span class="text-danger">*</span> :</label>
            <select onchange="updateZone(this, true)" name="tehsil_id_permanent" id="tehsil_select_permanent"
                    class="js-example-basic-single  profile-update-addressform-control current-address"
                    style="width: 100%;" required>
                <option value="">{{ __('app.select-tehsil') }}</option>
                @forelse($tehsils as $tehsil)
                    <option value="{{ $tehsil->id }}"
                        {{-- {{ $permanentAddress->tehsil_id_permanent ?? 0 == $tehsil->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->tehsil_id_permanent == $tehsil->id ? 'selected' : '' }}>
                        {{ $tehsil->name }}</option>
                @empty
                    <option value="">{{ __('app.no-tehsil') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="tehsil_select_permanent-error " class="error" for="tehsil_select_permanent"></label>

        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12 mb-3 zone_select_div_permanent" style="display: {{ $permanentAddress ? optional($permanentAddress)->country_id_permanent == 1 ? '' : 'none' : '' }}">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.zone') }} <span class="text-danger">*</span>:</label>
            <select name="zone_id_permanent"
                    class="zone_id_permanent profile-update-addressform-control current-address"
                    style="width: 100%;" id="zone_select_permanent" required>
                <option value="">{{ __('app.select-zone') }}</option>
                @forelse($zones as $zone)
                    <option value="{{ $zone->id }}"
                        {{-- {{ $permanentAddress->zone_id_permanent ?? 0 == $zone->id ? 'selected' : '' }} --}}
                        {{  optional($permanentAddress)->zone_id_permanent == $zone->id ? 'selected' : '' }}>
                        {{ $zone->name }}</option>
                @empty
                    <option value="">{{ __('app.no-zone') }}</option>
                @endforelse
            </select>
        </div>
        <div class="error-custom-address">
            <label id="zone_select_permanent-error " class="error" for="zone_select_permanent"></label>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12 mb-3 postcode_select_div">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{__('app.union-council')}}</label>
            <input id="union-council" type="text" class="form-control permanent-address" name="union_council_permanent" value="{{ isset($permanentAddress->union_council_permanent_relation)?optional($permanentAddress->union_council_permanent_relation)->{'name_'.app()->getLocale()} : '' }}">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12 mb-3 postcode_select_div_permanent">
        <div class="form-group select-wrap d-flex align-items-center">
            <label class="sort-form-select me-2">{{ __('app.postcode') }}</label>
            <input id="postcode_permanent" type="text" class="form-control current-address" name="postcode_permanent" value="{{ $permanentAddress ? optional($permanentAddress)->postcode_permanent : '' }}">
        </div>
    </div>
</div>
