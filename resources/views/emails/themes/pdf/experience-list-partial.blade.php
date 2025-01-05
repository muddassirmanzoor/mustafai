@forelse($experiences as $experience)
<div class="d-flex justify-content-between align-items-center">
    <div class="profile-todo-list d-flex">
        {{-- <div class="todo-img">
            <figure class="mb-0">
                <img src="./images/indtitute-img.png" alt="" class="img-fluid">
            </figure>
        </div> --}}
        <div class="todo-info">
            <ul>
                <li>
                    <div class="todo-info-div">
                        <span class="todo-title">{{ availableField($experience->title, $experience->title_english, $experience->title_urdu, $experience->title_arabic) }}</span> <br>
                        <small>{{ availableField($experience->experience_company, $experience->experience_company_english, $experience->experience_company_urdu, $experience->experience_company_arabic) }}</small>
                        <div class="d-flex align-items-center">
                            <p class="me-lg-4 me-3 dot d-flex align-items-center">{{ $experience->experience_start_date }} - {{ $experience->is_currently_working == 1 ? 'currently working' : $experience->experience_end_date }}</p>
                            <p> {{ $experience->created_at->diffForHumans() }}</p>
                        </div>
                        <p>{{ availableField($experience->experience_location, $experience->experience_location_english, $experience->experience_location_urdu, $experience->experience_location_arabic) }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="d-flex">
        <button type="submit" onclick="deleteExperience(this)" data-id="{{ $experience->id }}" class="btn btn-danger btn-sm me-2">{{__('app.delete')}}</button>
        <button type="submit" onclick="editExperience(this)" data-id="{{ $experience->id }}" class="btn btn-warning btn-sm">{{__('app.edit')}}</button>
    </div>
</div>
@empty
<li>{{__('app.no-added-yet')}}</li>
@endforelse
