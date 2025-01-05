@php
if($skills->skills ?? availableField($skills->skills, $skills->skills_english, $skills->skills_urdu, $skills->skills_arabic)){
    $skills->skills=explode(',',availableField($skills->skills, $skills->skills_english, $skills->skills_urdu, $skills->skills_arabic));
}else{
    $skills->skills =[];
}

@endphp
@forelse($skills->skills as $key=>$val)
<div class="career-detail d-flex justify-content-between mt-5" >
    <div class="profile-todo-list">
        {{-- <div class="todo-img">
            <figure class="mb-0">
                <img src="./images/indtitute-img2.png" alt="" class="img-fluid">
            </figure>
        </div> --}}
        <div class="todo-info">
            <ul>
                <li>
                    <div class="todo-info-div">
                        <span class="todo-title">{{ $val }}</span>

                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>
<hr> @empty <p>{{__('app.no-added-yet')}}</p>
@endforelse
