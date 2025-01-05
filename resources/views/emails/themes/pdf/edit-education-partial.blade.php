<form action="{{ route('user.education') }}" class="dash-comon-form" method="post" id="editEducationForm">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
    <div class="row">
        @foreach (activeLangs() as $lang)
                @php
                $institute = 'institute_';
                $lang_key = $lang->key;
                $resultinstitute = $institute . $lang_key;
            @endphp
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <label>{{ __('app.institute-'.$lang->key.'') }}</label>
                    <input class="form-control" type="text" name="institute_{{$lang->key}}" value="{{ $$resultinstitute }}" required>
                </div>
            </div>
        @endforeach

        @foreach (activeLangs() as $lang)
            @php
                $degree_name = 'degree_name_';
                $lang_key = $lang->key;
                $resultdegree = $degree_name . $lang_key;
            @endphp
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <label>{{ __('app.degree-'.$lang->key.'') }}</label>
                    <input class="form-control" type="text" name="degree_name_{{$lang->key}}" value="{{ $$resultdegree }}" required>
                </div>
            </div>
        @endforeach

        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label>{{ __('app.start-date') }}</label>
                <input class="form-control" onchange="endDatevalidation()" type="date" id="start_date_partial" max="<?php echo date("Y-m-d"); ?>" name="start_date" value="{{ $start_date }}"  required>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label>{{ __('app.end-date') }}</label>
                <input class="form-control" id="end_date_partial" type="date" max="<?php echo date('Y') . '-12-31'; ?>" name="end_date" value="{{ $end_date }}" required>
            </div>
        </div>
    </div>
</form>
