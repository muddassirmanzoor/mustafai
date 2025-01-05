<form action="{{ route('user.experience-edit') }}" class="dash-comon-form"  method="post" id="editExperienceForm">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
    <div class="row">
        @foreach (activeLangs() as $lang)
            <div class="col-md-4 clo-sm-6">
                <div class="form-group mb-1">
                    @php
                        $experience_company = 'experience_company_';
                        $lang_key = $lang->key;
                        $resultcompany = $experience_company . $lang_key;
                    @endphp
                    <label>{{ __('app.company-'.$lang->key.'') }}</label>
                    <input class="form-control" type="text" name="experience_company_{{$lang->key}}" value="{{ $$resultcompany }}" required>
                </div>
            </div>
        @endforeach
        {{-- <div class="col-md-4 clo-sm-6">
            <div class="form-group mb-1">
                <label>{{ __('app.company-urdu') }}</label>
                <input class="form-control" type="text" name="experience_company_urdu" value="{{ $experience_company_urdu }}" required>
            </div>
        </div>
        <div class="col-md-4 clo-sm-6">
            <div class="form-group mb-1">
                <label>{{ __('app.company-arabic') }}</label>
                <input class="form-control" type="text" name="experience_company_arabic" value="{{ $experience_company_arabic }}" required>
            </div>
        </div> --}}
        @foreach (activeLangs() as $lang)
                @php
                $experience_location = 'experience_location_';
                $lang_key = $lang->key;
                $resultlocaiton = $experience_location . $lang_key;
            @endphp
            <div class="col-md-4 clo-sm-6">
                <div class="form-group mb-1">
                    <label>{{ __('app.location-'.$lang->key.'') }}</label>
                    <input class="form-control" type="text" name="experience_location_{{$lang->key}}" value="{{ $$resultlocaiton }}" required>
                </div>
            </div>
        @endforeach
        {{-- <div class="col-md-4 clo-sm-6">
            <div class="form-group mb-1">
                <label>{{ __('app.location-urdu') }}</label>
                <input class="form-control" type="text" name="experience_location_urdu" value="{{ $experience_location_urdu }}" required>
            </div>
        </div>
        <div class="col-md-4 clo-sm-6">
            <div class="form-group mb-1">
                <label>{{ __('app.location-arabic') }}</label>
                <input class="form-control" type="text" name="experience_location_arabic" value="{{ $experience_location_arabic }}" required>
            </div>
        </div> --}}
        <div class="col-md-12 clo-sm-12">
            <div class="form-group d-flex align-items-center mt-2 mb-2">
                <input class="is_currently_working form-check-input me-2" type="checkbox" name="is_currently_working" value="1" {{ $is_currently_working == 1 ? 'checked' : '' }}>
                <label>{{ __('app.do-currently-work') }}</label>
                <input type="hidden" id="is_working" value="{{ $is_currently_working }}">
            </div>
        </div>
        <div class="col-md-6 clo-sm-6">
            <div class="form-group mb-1">
                <label>{{ __('app.start-date') }}</label>
                <input class="form-control" id="experience_start_date_partial" onchange="endDatevalidationExperience()" type="date" max="<?php echo date("Y-m-d"); ?>" name="experience_start_date" value="{{ $experience_start_date }}" required>
            </div>
        </div>
        <div class="col-md-6 clo-sm-6">
            <div class="form-group mb-1 experience_end_date_div dynamic_end_date_div">
                <label>{{ __('app.end-date') }}</label>
                <input class="form-control" id="experience_end_date_partial" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="experience_end_date" value="{{ $experience_end_date }}" required>
            </div>
        </div>
    </div>
</form>

<script>
    let isWorkingFine = $('#is_working').val();
    if(isWorkingFine == 1)
    {
        $('.dynamic_end_date_div').css('display', 'none')
    }
    else
    {
        $('.dynamic_end_date_div').css('display', 'block')
    }
</script>
