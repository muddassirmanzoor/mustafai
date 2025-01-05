<div class="modal fade library-detail common-model-style" id="applyJobModal" role="dialog" tabindex="-1" aria-labelledby="applyJobModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.apply-for-job') }}</h4>
                <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    @csrf

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-name') }}" name="name" id="person-name" required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-experience') }}" name="experience" id="person-experience" required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="{{ __('app.enter-age') }}" name="age" id="person-age" required>
                    </div>
                    <div class="form-group mt-3 to_append_resume_div">
                        <label>{{__('app.apply_prof')}}</label>
                        @php
                            $isResumeExists = auth()->check() ? (auth()->user()->resume == null ? false : true) : false;
                        @endphp
                        <input class="is_resume_exists" type="checkbox" name="is_resume" {{ $isResumeExists ? '' : 'disabled' }}>
                        <br>
                        <small>{{ $isResumeExists ? '' : __('app.apply_prof_err') }}</small>
                    </div>
                    <div class="form-group mt-3 appended_resume_div">
                        <input type="file" class="form-control" name="resume" id="person-resume" required>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="button" class="green-hover-bg theme-btn apply_job_btn">{{ __('app.apply-now') }}</button>
            </div>
        </div>
    </div>
</div>
