<!-- update banner modal   -->
<div class="modal fade library-detail common-model-style" id="updateBannerModal" tabindex="-1" role="dialog"
    aria-labelledby="updateBannerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-banner-image') }}</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"
                    data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.banner') }}" method="post" id="updateBannerForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" class="image_only" name="banner" id="banner_input" required accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateBanner()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- about modal   -->
<div class="modal fade library-detail common-model-style" id="aboutMoadl" tabindex="-1" role="dialog"
    aria-labelledby="aboutMoadl" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.about-your') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form method="post" id="aboutForm">
                    @csrf
                    @foreach(activeLangs() as $lang)
                        <div class="col-12-sm p-2">
                            <label for="about_{{$lang->key}} ">{{ __('app.about-'.$lang->key.'') }}</label>
                            <div class="form-group mt-2">
                                <textarea id="summernote{{$loop->iteration}}" class="about_{{$lang->key}}" name="about_{{$lang->key}}">{{ auth()->user()->{'about_'.$lang->key.''} }}</textarea>
                            </div>
                        </div>
                    @endforeach
                    {{-- <div class="col-12-sm p-2">
                        <label for="about_urdu m-2">{{ __('app.about-urdu') }}</label>
                        <div class="form-group mt-2">
                            <textarea id="summernote1" class="about_urdu" name="about_urdu">{{ auth()->user()->about_urdu }}</textarea>
                        </div>
                    </div>
                    <div class="col-12-sm p-2">
                        <label for="about_arabic m-2">{{ __('app.about-arabic') }}</label>
                        <div class="form-group mt-2">
                            <textarea id="summernote2" class="about_arabic" name="about_arabic">{{ auth()->user()->about_arabic }}</textarea>
                        </div>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  --}}
                <button onclick="updateAbout()" type="button" class="green-hover-bg theme-btn about_close_button">
                    {{ __('app.update') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- upadte profile modal -->
<div class="modal fade library-detail common-model-style" id="updateProfileModal" tabindex="-1" role="dialog"
    aria-labelledby="updateProfileModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-profile-image') }}</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"
                    data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.image') }}" method="post" id="updateProfileImageForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input class="image_only" type="file" name="profile_image" id="profile_image" required accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateProfileImage()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- update user name modal -->
<div class="modal fade library-detail common-model-style" id="updateUserNameModal" tabindex="-1" role="dialog"
    aria-labelledby="updateUserNameModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-username') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.username') }}" method="post" id="updateUserNameForm">
                    @csrf
                    @foreach(activelangs() as $lang)
                        <div class="form-group p-2">
                            <input class="form-control" placeholder="{{ __('app.username-'.$lang->key.'') }}" type="text"
                                name="user_name_{{$lang->key}}" id="user_name_{{$lang->key}}"
                                value="{{ auth()->user()->{'user_name_'.$lang->key} }}" required>
                        </div>
                    @endforeach

                    {{-- <div class="form-group p-2">
                        <input class="form-control" placeholder="{{ __('app.username') }}" type="text"
                            name="user_name" id="user_name"
                            value="{{ auth()->user()->user_name}}" required>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateUserName()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- update tagline modal -->
<div class="modal fade library-detail common-model-style" id="updateTaglineModal" tabindex="-1" role="dialog"
    aria-labelledby="updateTaglineModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-tagline') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.tagline') }}" method="post" id="updateTaglineForm">
                    @csrf
                    @foreach(activeLangs() as $lang)
                    <div class="form-group p-2">
                        <input class="form-control" placeholder="{{ __('app.tagline-'.$lang->key.'') }}" type="text"
                            name="tagline_{{$lang->key}}" id="tagline_{{$lang->key}}"
                            value="{{ auth()->user()->{'tagline_'.$lang->key} }}" required>
                    </div>
                    @endforeach
                    {{-- <div class="form-group p-2">
                        <input class="form-control" placeholder="{{ __('app.tagline-urdu') }}" type="text"
                            name="tagline_urdu" id="tagline_urdu" value="{{ auth()->user()->tagline_urdu }}"
                            required>
                    </div>
                    <div class="form-group p-2">
                        <input class="form-control" placeholder="{{ __('app.tagline-arabic') }}" type="text"
                            name="tagline_arabic" id="tagline_arabic" value="{{ auth()->user()->tagline_arabic }}"
                            required>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateTagline()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- update Occupation modal -->
<div class="modal fade library-detail common-model-style" id="updateOccupationModal" tabindex="-1" role="dialog"
    aria-labelledby="updateOccupationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.occupation') }}</h4>
                <button type="button" class="btn-close close" id="close-occupation-modal" data-dismiss="modal"></button>

            </div>
            <div class="modal-body user_occupation">

            </div>
            @can('update', $user)
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button onclick="profileOccupation()" type="button" class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
                </div>
            @endcan

        </div>
    </div>
</div>


<!-- update address modal -->
<div class="modal fade library-detail common-model-style update-address-modal" id="updateAddressModal" role="dialog"
    aria-labelledby="updateAddressModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-address') }}</h5>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center mb-3">{{__('app.current-address')}} <span class="text-red">*</span> </h3>
                <form action="{{ route('user.address') }}" method="post" id="updateAddressForm">
                    @csrf
                    @foreach(activelangs() as $key=>$val)
                        <div class="form-group p-2">
                            <input class="form-control permanent-address" type="text" name="address_{{strtolower($val->key)}}" id="address_{{strtolower($val->key)}}"
                                placeholder="{{ __('app.address-'.strtolower($val->key).'') }}" required
                                value="{{ auth()->user()->{'address_'.strtolower($val->key)} }}">
                        </div>
                    @endforeach
                    {{-- <div class="form-group p-2">
                        <input class="form-control" type="text" name="address_urdu" id="address_urdu"
                            placeholder="{{ __('app.address-urdu') }}" required
                            value="{{ auth()->user()->address_urdu }}">
                    </div>
                    <div class="form-group p-2">
                        <input class="form-control" type="text" name="address_arabic" id="address_arabic"
                            placeholder="{{ __('app.address-arabic') }}" required
                            value="{{ auth()->user()->address_arabic }}">
                    </div> --}}

                    <div class="row mt-3 profile-update-address country_select_div">
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.country') }} <span class="text-danger">*</span> :</label>
                                <select onchange="updateProvince(this)" name="country_id"
                                    class="js-example-basic-single  profile-update-address profile-update-addressform-control permanent-address"
                                    style="width: 100%;" id="country_id" required>
                                    <option value="">{{ __('app.select-country') }}</option>
                                    @forelse($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ auth()->user()->country_id == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-country') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="country_id-error " class="error" for="country_id"></label>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12  mb-3 province_select_div">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.province') }}<span class="text-danger">*</span>  :</label>
                                <select onchange="updateDivisions(this)" name="province_id" id="province_select"
                                    class="js-example-basic-single  profile-update-addressform-control permanent-address"
                                    style="width: 100%;" required>
                                    <option value="">{{ __('app.select-province') }}</option>
                                    @forelse($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ auth()->user()->province_id == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-province') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="province_select-error " class="error" for="province_select"></label>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 division_select_div" style="display: {{ $authUser->country_id == 1 ? '' : 'none' }}">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.division') }}<span class="text-danger">*</span>  :</label>
                                <select name="division_id" id="division_select" onchange="updateDistricts(this)"
                                    class="js-example-basic-single  profile-update-addressform-control permanent-address"
                                    style="width: 100%;" required>
                                    <option value="">{{ __('app.select-division') }}</option>
                                    @forelse($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ auth()->user()->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-division') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="division_select-error " class="error" for="division_select"></label>

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.city') }}  :</label>
                                <select name="city_id" id="city_select"
                                    class="js-example-basic-single  profile-update-addressform-control permanent-address"
                                    style="width: 100%;">
                                    <option value="">{{ __('app.select-city') }}</option>
                                    @forelse($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ auth()->user()->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-city') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="city_select-error " class="error" for="city_select"></label>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12  mb-3 district_select_div" style="display: {{ $authUser->country_id == 1 ? '' : 'none' }}">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.district') }}<span class="text-danger">*</span>  :</label>
                                <select name="district_id" id="district_select" onchange="updateTehsil(this)"
                                    class="js-example-basic-single  profile-update-addressform-control permanent-address"
                                    style="width: 100%;" required>
                                    <option value="">{{ __('app.select-district') }}</option>
                                    @forelse($districts as $district)
                                        <option value="{{ $district->id }}"
                                            {{ auth()->user()->district_id == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-district') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="district_select-error " class="error" for="district_select"></label>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 tehsil_select_div" style="display: {{ $authUser->country_id == 1 ? '' : 'none' }}">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.tehsil') }}<span class="text-danger">*</span>  :</label>
                                <select onchange="updateZone(this)" name="tehsil_id" id="tehsil_select"
                                    class="js-example-basic-single  profile-update-addressform-control permanent-address"
                                    style="width: 100%;" required>
                                    <option value="">{{ __('app.select-tehsil') }}</option>
                                    @forelse($tehsils as $tehsil)
                                        <option value="{{ $tehsil->id }}"
                                            {{ auth()->user()->tehsil_id == $tehsil->id ? 'selected' : '' }}>
                                            {{ $tehsil->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-tehsil') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="error-custom-address">
                                <label id="tehsil_select-error " class="error" for="tehsil_select"></label>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 zone_select_div" style="display: {{ $authUser->country_id == 1 ? '' : 'none' }}">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.zone') }}<span class="text-danger">*</span> :</label>
                                <select name="zone_id"
                                    class="zone_id profile-update-addressform-control permanent-address"
                                    style="width: 100%;" id="zone_select" required>
                                    <option value="">{{ __('app.select-zone') }}</option>
                                    @forelse($zones as $zone)
                                        <option value="{{ $zone->id }}"
                                            {{ auth()->user()->zone_id == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->name }}</option>
                                    @empty
                                        <option value="">{{ __('app.no-zone') }}</option>
                                    @endforelse
                                </select>

                            </div>
                            <div class="error-custom-address">
                                <label id="zone_select-error " class="error" for="zone_select"></label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 postcode_select_div">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{__('app.union-council')}}</label>
                                <input id="union-council" type="text" class="form-control permanent-address"placeholder="{{__('app.union-council')}}" name="union_council" value="{{ optional($authUser->union_council)->{'name_'.app()->getLocale()} }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 postcode_select_div">
                            <div class="form-group select-wrap d-flex align-items-center">
                                <label class="sort-form-select me-2">{{ __('app.postcode') }}</label>
                                <input id="postcode" type="text" class="form-control permanent-address" placeholder="{{ __('app.postcode') }}" name="postcode" value="{{ $authUser->postcode }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="checkbox" name="same_address" id="same-address" onchange="sameAddress()" value="1" {{ $authUser->same_address==1 ? 'checked' : ''  }}> {{ __('app.same_address') }}
                        </div>
                    </div>
                    <div class="permanent_address_div">
                        <x-profile.permanent-address-component :permanentAddress="$permanentAddress" :countries="$countries" :cities="$cities" :districts="$districts" :divisions="$divisions" :provinces="$provinces" :tehsils="$tehsils" :zones="$zones" />
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateAddress()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.save') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- update CNIC modal   -->
<div class="modal fade library-detail common-model-style" id="updateCnicModal" tabindex="-1" role="dialog"
    aria-labelledby="updateBannerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.update-cnic') }}</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"
                    data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.cnic') }}" method="post" id="updateUserCnicForm">
                    @csrf
                    <div class="form-group p-2">
                        {{-- <input class="form-control" data-inputmask="'mask': '99999-9999999-9'" placeholder="e.g: 00000-0000000-0"  type="text"  name="cnic" id="form_cnic" value="{{ auth()->user()->cnic }}" required> --}}
                        <input class="form-control" type="text"  data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X"  name="cnic" id="form_cnic" value="{{ auth()->user()->cnic }}" required >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateCNIC()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.update') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- experience modal -->
<div class="modal fade library-detail common-model-style" id="experienceModal" tabindex="-1" role="dialog"
    aria-labelledby="experienceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.add-experienc') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.experience') }}" class="dash-comon-form" method="post"
                    id="addExperienceForm">
                    @csrf
                    <div class="row">
                        @foreach(activeLangs() as $lang)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('app.title-'.$lang->key.'') }}</label>
                                    <input class="form-control" type="text" name="title_{{$lang->key}}" required>
                                </div>
                            </div>
                        @endforeach

                        @foreach(activeLangs() as $lang)

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('app.company-'.$lang->key.'') }}</label>
                                    <input class="form-control" type="text" name="experience_company_{{$lang->key}}"
                                        required>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.company-urdu') }}</label>
                                <input class="form-control" type="text" name="experience_company_urdu" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.company-arabic') }}</label>
                                <input class="form-control" type="text" name="experience_company_arabic" required>
                            </div>
                        </div> --}}
                        @foreach(activeLangs() as $lang)

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('app.location-'.$lang->key.'') }}</label>
                                    <input class="form-control" type="text" name="experience_location_{{$lang->key}}"
                                        required>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-12 col-sm-12 ">
                            <div class="form-group d-flex align-items-center mb-2 mt-2">
                                <input class="is_currently_working me-2" type="checkbox" name="is_currently_working"
                                    value="1">
                                <label>{{ __('app.do-currently-work') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.start-date') }}</label>
                                <input class="form-control" id="experience_start_date" type="date" max="<?php echo date("Y-m-d"); ?>" name="experience_start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group experience_end_date_div">
                                <label>{{ __('app.end-date') }}</label>
                                <input class="form-control" id="experience_end_date" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="experience_end_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="addExperience()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.add') }}</button>
            </div>
        </div>
    </div>
</div>

<!--edit experience modal-->

<div class="modal fade library-detail common-model-style" id="editExperienceModal" tabindex="-1" role="dialog"
    aria-labelledby="editExperienceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.edit-experience') }}</h4>
                <button type="button" id="editExperienceModal" class="btn-close close" data-dismiss="modal"></button>


            </div>
            <div class="modal-body edit_experience_section">
                <!-- dynamic section here-->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="editExperienceRequest()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.edit') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- education modal -->
<div class="modal fade library-detail common-model-style" id="addEducationModal" tabindex="-1" role="dialog"
    aria-labelledby="addEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.add-education') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.education') }}" method="post" class="dash-comon-form"
                    id="addEducationForm">
                    @csrf
                    <div class="row">
                        @foreach (activeLangs() as $lang)

                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.institute-'. $lang->key.'') }}</label>
                                <input class="form-control" type="text" name="institute_{{ $lang->key}}" required>
                            </div>
                        </div>
                        @endforeach
                        {{-- <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.institute-urdu') }}</label>
                                <input class="form-control" type="text" name="institute_urdu" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.institute-arabic') }}</label>
                                <input class="form-control" type="text" name="institute_arabic" required>
                            </div>
                        </div> --}}
                        @foreach(activeLangs() as $lang)

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('app.degree-'.$lang->key.'') }}</label>
                                    <input class="form-control" type="text" name="degree_name_{{ $lang->key}}" required>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.degree-urdu') }}</label>
                                <input class="form-control" type="text" name="degree_name_urdu" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.degree-arabic') }}</label>
                                <input class="form-control" type="text" name="degree_name_arabic" required>
                            </div>
                        </div> --}}
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.start-date') }}</label>
                                <input class="form-control" id="start_date" type="date" max="<?php echo date("Y-m-d"); ?>" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('app.end-date') }}</label>
                                <input class="form-control" id="end_date" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="end_date" value="" required>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="addEducation()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.add') }}</button>
            </div>
        </div>
    </div>
</div>

<!--edit education modal-->

<div class="modal fade library-detail common-model-style" id="editEducationModal" tabindex="-1" role="dialog"
    aria-labelledby="editEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.edit-education') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body edit_education_section">
                <!-- dynamic section here-->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="editEducationRequest()" type="button"
                    class="green-hover-bg theme-btn">{{ __('app.edit') }}</button>
            </div>
        </div>
    </div>
</div>


<!--Donor  modal-->

<div class="modal fade library-detail common-model-style" id="donorModal" tabindex="-1" role="dialog"
    aria-labelledby="editEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.donor-details') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <form method="POST" id="donor_form" action="{{ route('user.donor') }}" enctype="multipart/form-data">
                @CSRF

                <div class="modal-body donor-modal-body">

                    <!-- dynamic section here-->
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="green-hover-bg theme-btn">{{ __('app.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--Edit Resume modal-->

<div class="modal fade library-detail common-model-style " id="create_resume_modal" tabindex="-1" role="dialog"
    aria-labelledby="editEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Create Resume</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <form method="POST" id="donor_form" action="{{ route('user.create.resume') }}"
                enctype="multipart/form-data">
                @CSRF
                @if (!empty(auth()->user()->resume))
                    <div class="d-flex justify-content-end py-4 me-2">
                        
                        <a class="white-hover-bg theme-btn"href="{{ getS3File(auth()->user()->resume) }}" download><i
                                class="fa fa-download" aria-hidden="true"></i></a>
                    </div>
                @endif
                <div class="modal-body create-resume-body">
                    <section id="loading">
                        <div id="loading-content"></div>
                    </section>
                    <!-- dynamic section here-->
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="green-hover-bg theme-btn" onclick="downloadCv()">Create
                        Resume</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--Edit Skills Modal modal-->

<div class="modal fade library-detail common-model-style " id="skillModal" tabindex="-1" role="dialog"
    aria-labelledby="editEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.add-skills') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <form method="POST" id="skillForm" action="{{ route('user.skills') }}" enctype="multipart/form-data">
                @CSRF
                <div class="modal-body skill-modal-body">
                    <!-- dynamic section here-->
                    <div class="row">

                        @foreach (activeLangs() as $lang)
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('app.comma-seprated-skills-'.$lang->key   ) }}</label>
                                    <input style="width: 100%" id="skills_{{ $lang->key}}" type="text"
                                        class="form-control tags-input" name="skills_{{ $lang->key }}"
                                        placeholder="{{ __('app.comma-seprated-skills-'.$lang->key) }}"
                                        value="{{ auth()->user()->{'skills_'.$lang->key} }}" required />
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="green-hover-bg theme-btn skill_close_button"
                        onclick="updateSkills()">
                        {{ __('app.update-skills') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Update Blood group !-->

<div class="modal fade library-detail common-model-style" id="bloodModal" tabindex="-1" role="dialog"
    aria-labelledby="bloodModal" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="">{{ __('app.your-blood') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <form method="post" id="bloodForm">
                <div class="modal-body">
                    @csrf
                    <div class="form-group p-2">
                        <label class="form-label">{{ __('app.blood-group-english') }} <span
                                class="text-red">*</span></label>
                        <select name="blood_group_english" id="blood_group_english" class="form-control"
                            placeholder="Enter Blood Group In English">
                            <option value="">--{{ __('app.select-blood-group') }}</option>
                            <option value="A+"
                                {{ auth()->user()->blood_group_english == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="O+"
                                {{ auth()->user()->blood_group_english == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="B+"
                                {{ auth()->user()->blood_group_english == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="AB+"
                                {{ auth()->user()->blood_group_english == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="A-"
                                {{ auth()->user()->blood_group_english == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="O-"
                                {{ auth()->user()->blood_group_english == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="B-"
                                {{ auth()->user()->blood_group_english == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB-"
                                {{ auth()->user()->blood_group_english == 'AB-' ? 'selected' : '' }}>AB-</option>

                        </select>
                    </div>
                    {{-- <div class="form-group p-2">
                        <label class="form-label">{{ __('app.blood-group-urdu') }} </label>
                        <select name="blood_group_urdu" id="blood_group_urdu" class="form-control"
                            placeholder="Enter Blood Group In Urdu">
                            <option value="">--{{ __('app.select-blood-group') }}</option>
                            <option value="اے مثبت"
                                {{ auth()->user()->blood_group_urdu == 'اے مثبت' ? 'selected' : '' }}>اے مثبت</option>
                            <option value="او مثبت"
                                {{ auth()->user()->blood_group_urdu == 'او مثبت' ? 'selected' : '' }}>او مثبت</option>
                            <option value="بی مثبت"
                                {{ auth()->user()->blood_group_urdu == 'بی مثبت' ? 'selected' : '' }}>بی مثبت</option>
                            <option value="اے بی مثبت"
                                {{ auth()->user()->blood_group_urdu == 'اے بی مثبت' ? 'selected' : '' }}>اے بی مثبت
                            </option>
                            <option value="اے منفی"
                                {{ auth()->user()->blood_group_urdu == 'اے منفی' ? 'selected' : '' }}>اے منفی</option>
                            <option value="او منفی"
                                {{ auth()->user()->blood_group_urdu == 'او منفی' ? 'selected' : '' }}>او منفی</option>
                            <option value="بی منفی"
                                {{ auth()->user()->blood_group_urdu == 'بی منفی' ? 'selected' : '' }}>بی منفی</option>
                            <option value="اے بی منفی"
                                {{ auth()->user()->blood_group_urdu == 'اے بی منفی' ? 'selected' : '' }}>اے بی منفی
                            </option>

                        </select>
                    </div> --}}
                    {{-- <div class="form-group p-2">
                        <label class="form-label">{{ __('app.blood-group-arabic') }} <span
                                class="text-red">*</span></label>
                        <select name="blood_group_arabic" id="blood_group_arabic" class="form-control"
                            placeholder="Enter Blood Group In Arabic">
                            <option value="">--{{ __('app.select-blood-group') }}</option>
                            <option value="اے إيجابي"
                                {{ auth()->user()->blood_group_arabic == 'اے إيجابي' ? 'selected' : '' }}>اے إيجابي
                            </option>
                            <option value="او إيجابي"
                                {{ auth()->user()->blood_group_arabic == 'او إيجابي' ? 'selected' : '' }}>او إيجابي
                            </option>
                            <option value="بی إيجابي"
                                {{ auth()->user()->blood_group_arabic == 'بی إيجابي' ? 'selected' : '' }}>بی إيجابي
                            </option>
                            <option value="اے بی إيجابي"
                                {{ auth()->user()->blood_group_arabic == 'اے بی إيجابي' ? 'selected' : '' }}>اے بی
                                إيجابي</option>
                            <option value="اے سلبي"
                                {{ auth()->user()->blood_group_arabic == 'اے سلبي' ? 'selected' : '' }}>اے سلبي
                            </option>
                            <option value="او سلبي"
                                {{ auth()->user()->blood_group_arabic == 'او سلبي' ? 'selected' : '' }}>او سلبي
                            </option>
                            <option value="بی سلبي"
                                {{ auth()->user()->blood_group_arabic == 'بی سلبي' ? 'selected' : '' }}>بی سلبي
                            </option>
                            <option value="اے بی سلبي"
                                {{ auth()->user()->blood_group_arabic == 'اے بی سلبي' ? 'selected' : '' }}>اے بی سلبي
                            </option>

                        </select>
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button onclick="updateBlood()" type="button"
                        class="green-hover-bg theme-btn blood_close_button">
                        {{ __('app.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
