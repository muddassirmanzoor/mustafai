@forelse($educations as $education)
<div class="d-flex justify-content-between align-items-center">
    <div class="profile-todo-list d-flex">
        {{-- <div class="todo-img">
            <figure class="mb-0">
                <img src="./images/indtitute-img2.png" alt="" class="img-fluid">
            </figure>
        </div> --}}
        <div class="todo-info">
            <ul>
                <li>
                    <div class="todo-info-div">
                        <span class="todo-title">{{ availableField($education->institute, $education->institute_english, $education->institute_urdu, $education->institute_arabic) }}</span>
                        <p>{{ availableField($education->degree_name, $education->degree_name_english, $education->degree_name_urdu, $education->degree_name_arabic) }}</p>
                        <p>{{ date('Y',strtotime($education->start_date)) .' - '. date('Y',strtotime($education->end_date)) }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="d-flex">
        <button type="submit" onclick="deleteEducation(this)" data-id="{{ $education->id }}" class="btn btn-danger btn-sm me-2">{{__('app.delete')}}</button>
        <button type="submit" onclick="editEducation(this)" data-id="{{ $education->id }}" class="btn btn-warning btn-sm">{{__('app.edit')}}</button>
    </div>
</div>
@empty
<li>{{__('app.no-added-yet')}}</li>
@endforelse
