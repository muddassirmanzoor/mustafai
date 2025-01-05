<div class="d-flex flex-column justify-content-center align-items-center">
    <div class="d-flex">
        <div class="cv-image-wraper">
            <img src="{{ auth()->user()->profile_image ? getS3File(auth()->user()->profile_image) : './images/user-round-img.png' }}" alt="profile" class="img-fluid rounded-circle">
        </div>
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center mt-3">
        <h5 class="profile-user-name text-center">{{auth()->user()->user_name}}</h5>
        <ul class="ul-style profile-intro-list">
            <li>
                <p><span class="user_tagline text-center">{{ auth()->user()->tagline_english ?? '--' }}</span> </p>
            </li>
        </ul>
    </div>
</div>
<div class="row mt-3 mb-3">
    <div class="col-md-4">
        <div class="intro-info">
            <h6 class="profile-user-name">
                Phone Number
            </h6>
            <ul class="ul-style profile-intro-list">
                <li>
                    <p><span class="user_tagline">{{ auth()->user()->phone_number ?? '--' }}</span> </p>
                </li>
            </ul>

        </div>
    </div>
    <div class="col-md-4">
        <div class="intro-info">
            <h6 class="profile-user-name">Email</h6>
            <ul class="ul-style profile-intro-list">
                <li>
                    <p><span class="user_tagline">{{ auth()->user()->email ?? '--' }}</span> </p>
                </li>
            </ul>

        </div>
    </div>
    <div class="col-md-4">
        <div class="intro-info">
            <!-- <div class="user-round-img">
                    <figure>
                        <img src="./images/user-round-img.png" alt="" class="img-fluid">
                    </figure>
                </div> -->
            <h6 class="profile-user-name">Address</h6>
            <ul class="ul-style profile-intro-list">
                <li>
                    <p><span class="user_tagline">{{ auth()->user()->address_english ?? __('app.your-tagline-here') }}</span> </p>
                </li>
            </ul>


        </div>
    </div>
</div>
<hr />
<div class="">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="profile-chunks-title">About</h3>
        </div>
        {{-- <div class="d-flex align-items-center">
            <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true" data-toggle="modal" data-target="#aboutMoadl"></i></span>
        </div> --}}
    </div>
    <span class="about_section mt-2">
        {!! auth()->user()->about_english ?? 'yout about here' !!}
    </span>
</div>
<hr />
<div class="">
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="profile-chunks-title">Experiance</h3>
        </div>
        {{-- <div class="d-flex align-items-center">
            <span class="profile-icon me-3"><i data-toggle="modal" data-target="#experienceModal" class="fa fa-plus" aria-hidden="true"></i></span>
            <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span>
        </div> --}}
    </div>
    <ul class="ul-style wrap-todo experience_list mt-2">
        @forelse(auth()->user()->experience as $experience)
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <div class="profile-todo-list">
                    {{-- <div class="todo-img">
                        <figure class="mb-0">
                            <img src="./images/indtitute-img.png" alt="" class="img-fluid">
                        </figure>
                    </div> --}}
                    <div class="todo-info">
                        <ul>
                            <li>
                                <div class="todo-info-div">
                                    <span class="todo-title">{{ $experience->experience_company_english }}</span>
                                    <div class="d-flex align-items-center">
                                        <p class="me-lg-4 me-3 dot d-flex align-items-center">{{ $experience->experience_start_date }}-{{ $experience->experience_end_date }}</p>
                                        <p> {{ $experience->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p>{{ $experience->experience_location_english }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr />
            {{-- <div class="d-flex">
                <button type="submit" onclick="deleteExperience(this)" data-id="{{ $experience->id }}" class="btn btn-danger btn-sm me-2">delete</button>
            <button type="submit" onclick="editExperience(this)" data-id="{{ $experience->id }}" class="btn btn-warning btn-sm">edit</button>
        </div> --}}
</div>
@empty
<li>No experience added yet!</li>
@endforelse
</ul>
</div>
<hr />
<div class="">
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="profile-chunks-title">Education</h3>
        </div>
        {{-- <div class="d-flex align-items-center">
            <span class="profile-icon me-3" data-toggle="modal" data-target="#addEducationModal"><i class="fa fa-plus" aria-hidden="true"></i></span>
            <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span>
        </div> --}}
    </div>
    <div class="ul-style wrap-todo education_list mt-2">
        @forelse(auth()->user()->education as $education)
        <div class="career-detail d-flex justify-content-between">
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
                                <span class="todo-title">{{ $education->institute_english }}</span>
                                <p>{{ $education->degree_name_english }}</p>
                                <p>{{ date('Y',strtotime($education->start_date)) .' - '. date('Y',strtotime($education->end_date)) }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- <div class="button-wraper">
                <button type="submit" onclick="deleteEducation(this)" data-id="{{ $education->id }}" class="btn btn-danger btn-sm">delete</button>
            <button type="submit" onclick="editEducation(this)" data-id="{{ $education->id }}" class="btn btn-warning btn-sm">edit</button>
        </div> --}}
    </div>
    </hr> @empty <p>No education data added yet!</p>
    @endforelse
</div>

</div>
<hr />
<div class="">
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="profile-chunks-title">Skills</h3>
        </div>
        {{-- <div class="d-flex align-items-center">
            <span class="profile-icon me-3" data-toggle="modal" data-target="#addEducationModal"><i class="fa fa-plus" aria-hidden="true"></i></span>
            <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span>
        </div> --}}
    </div>
    @php
        if(!empty(auth()->user()->skills_english)){
            $skills=explode(',',auth()->user()->skills_english);
        }else{
            $skills =[];
        }

        @endphp
        <div class="ul-style wrap-todo " id="skillsDiv">
            @forelse($skills as $key=>$val)
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
            </hr> @empty <p>{{__('app.no-added-yet')}}</p>
            @endforelse
        </div>

</div>
