<form id="updatePostForm" method="post" action="{{ route('user.post.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="tracking_files_edit" id="tracking_files_edit">
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.What's-on-your-mind") }}</label>
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="title" type="text" class="form-control post_title" id="exampleFormControlTextarea1"
                      placeholder="{{ __("app.What's-on-your-mind") }}" rows="3">{{ $post->title }}</textarea>
        </div>
        {{-- 5= blood post --}}
        @if($post->post_type == 5)
            <div class="apply_for_whom d-flex justify-content-between mb-3">
                <div>
                    <label for="">{{__('app.apply-as-your-self')}}</label>
                    <input type="radio" name="blood_for" value="0" {{ $post->blood_for == 0 ? 'checked' :'' }}>
                </div>
                <div>
                    <label for="">{{__('app.apply-for-someone-else')}}</label>
                    <input type="radio" value="1" name="blood_for"  {{ $post->blood_for == 1 ? 'checked' :'' }}>
                </div>
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.city") }}</label>
            <div class="mb-3">
                <select name="city" id="" class="form-control" required>
                    <option value="">{{ __('app.select-city') }}</option>
                    @forelse($cities as $city)
                        <option value="{{ $city->id }}" {{ $city->id == $post->city ? 'selected' : '' }}>{{ $city->name }}</option>
                    @empty
                        <option value="">{{ __('app.no-data') }}</option>
                    @endforelse
                </select>
            </div>
            <div class="mb-3">
                <select class="form-select w-100" aria-label="Default select example" id="select_blood" name="blood_group" required="">
                    <option value="">{{ __('app.select-blood-group') }}</option>
                    <option value="A+" {{ $post->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="O+" {{ $post->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="B+" {{ $post->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="AB+" {{ $post->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="A-" {{ $post->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="O-" {{ $post->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="B-" {{ $post->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="AB-" {{ $post->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                </select>
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.hospital") }}</label>
            <div class="mb-3">
                <input type="text" name="hospital" class="form-control" value="{{ $post->hospital }}"
                       placeholder="{{ __("app.hospital") }}">
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.address") }}</label>
            <div class="mb-3">
                <input type="text" name="address" class="form-control" value="{{ $post->address }}"
                       placeholder="{{ __("app.address") }}">
            </div>
        @endif
        {{-- 2 = job post --}}
        @if($post->post_type == 2)
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.occupation") }}</label>
            <div class="mb-3">
                <input type="text" name="occupation" class="form-control" value="{{ $post->occupation }}" placeholder="{{ __("app.occupation") }}">
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.experience") }}</label>
            <div class="mb-3">
                <input type="text" name="experience" class="form-control" value="{{ $post->experience }}" placeholder="{{ __("app.experience") }}">
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.skills") }}</label>
            <div class="mb-3">
                <input type="text" name="skills" class="form-control" value="{{ $post->skills }}" placeholder="{{ __("app.skills") }}">
            </div>

            @if($post->job_type == 2)
                <label class="form-label">{{__('app.currently-working')}}</label>
                <div class="mb-3">
                    <input type="text" name="job_seeker_currently_working"  class="form-control mt-2" value="{{ $post->job_seeker_currently_working }}">
                </div>
            @endif

            <label  class="form-label">{{__('app.your-email')}}</label>
            <div class="mb-3">
                <input type="email" name="job_seeker_or_hire_email"  class="form-control mt-2" value="{{ $post->job_seeker_or_hire_email }}">
            </div>
            <label  class="form-label">{{__('app.Phone-no')}}</label>
            <div class="mb-3">
                <input type="number" name="job_seeker_or_hire_phone"  class="form-control mt-2" value="{{ $post->job_seeker_or_hire_phone }}">
            </div>

            <label for="exampleFormControlTextarea1" class="form-label">{{ __("app.summary") }}</label>
            <div class="mb-3">
                <textarea name="description_english" id="summary_limit" cols="30" rows="5" maxlength="200" class="form-control" style="resize: none;">{{ $post->description_english }}</textarea>
            </div>
        @endif
        <div class="form-group">
            <div class="qt_wrap mt">
                @if($post->post_type != 2)
                    <label class="form-label">{{ __('app.please-upload') }}</label>
                @endif
                <div>
                @foreach($post->images as $image)
                        <input type="hidden" value="{{ $image->id }}" name="old_files[]" class="remove_old_file" data-remove-old-file="{{ $image->id }}">
                        <input type="hidden" value="{{ $post->images()->count()+1 }}" class="total_files_counter">
                   @if($loop->first)
                        <div class="file_wraping_div_edit">
                          <span class="hiddenFileInputEdit">
                             <input id="files" type="file" name="files[]" class="form-control m-input post-input-file-edit" multiple accept="image/*">
                          </span>
                        </div>
                   @endif
                       @if($loop->first) <div id="editNewRow"> @endif
                           <div class="post-upload-image" data-image-name=${fileName}">
                               <span class="image-dell" data-image-name={{ $image->id }} onclick="editRemoveImage(this)"><i class="fa fa-times" aria-hidden="true"></i></span>
                               <img class="edit_remove_image muli-images-upload" data-image-name={{ $image->id }} src="{{ getS3File($image->file) }}" alt="image">
                           </div>
                       @if($loop->last) </div> @endif

                @endforeach
                    <!-- in case if post has no media files -->
                    @if($post->post_type != 2 && $post->images->count() == 0)
                            <div class="file_wraping_div_edit">
                                <span class="hiddenFileInputEdit">
                                 <input id="files" type="file" name="files[]" class="form-control m-input post-input-file-edit" accept="image/*"  multiple>
                                </span>
                            </div>
                        <div id="editNewRow"></div>
                    @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="green-hover-bg theme-btn update_post">{{ __('app.update') }}</button>
        </div>
</form>

<script>

    $(document).on('click', '#editRemoveRow', function () {
        $(this).closest('#editInputFormRow').remove();
    });

    $('.update_post').click(function () {
        $('#updatePostForm').submit();
    })

</script>
