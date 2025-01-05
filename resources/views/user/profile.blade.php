@extends('user.layouts.layout')
@section('content')
@push('styles')
 <style>

.bootstrap-tagsinput .tag {
        background: red;
        padding: 4px;
        font-size: 14px;
        line-height: 34px;
}

.bootstrap-tagsinput input {
    width: 100% !important;
}
 </style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<!-- tags input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <!-- sweet alert -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
@endpush
<div class="profile-page">

    <div class="profile-gap profile-intro">
        <div class="position-relative wrap-img">
            <figure class="mb-0 profile-banner-img ">
                @can('update', $user)
                    <span class="camera-btn" type="btn" data-toggle="modal" data-target="#updateBannerModal"><i class="fa fa-camera" aria-hidden="true"></i></span>
                @endcan
                <span class="user_banner"><img src="{{ $user->banner ? getS3File($user->banner) : './images/profile-banner.png' }}" alt="User Profile" class="img-fluid"></span>
            </figure>
            <div class="user-round-img update-user-profile-image" @can('update', $user) data-toggle="modal" data-target="#updateProfileModal" @endcan>
                <figure>
                    <span class="user_profile_image">
                        @can('update', $user)
                            <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                        @endcan
                        <img src="{{ $user->profile_image ? getS3File($user->profile_image) : './images/user-round-img.png' }}" alt="profile" class="img-fluid">
                    </span>
                </figure>
            </div>
        </div>
        <div class="intro-info">
            <div class="d-flex mb-3">
                <div class="profile-user-name">
                    <span class="user_name_data">
                        {{-- {{ availableField($user->user_name, $user->user_name_english, $user->user_name_urdu, $user->user_name_arabic) ?? auth()->user()->user_name }} --}}
                        {{ $user->{'user_name_'.app()->getLocale()} }}
                    </span>

                    @if ($user->id == auth()->user()->id)
                        <button class="btn" id="UserNameModal" data-toggle="modal" data-target="#updateUserNameModal"><span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span></button>
                        <p>
                            @php
                                $role = null;
                                $cabin_name = null;
                                if(auth()->user()->login_role_id)
                                {
                                    $role = \App\Models\Admin\Role::where('id', auth()->user()->role_id)->first();
                                    $designation = \App\Models\Admin\Designation::where('id',auth()->user()->designation_id)->first();
                                    $cabinetUsers = \App\Models\Admin\CabinetUser::where('designation_id', auth()->user()->designation_id)
                                        ->pluck('cabinet_id');

                                    // Fetch the cabinet where status is 1
                                    $cabin_nam = \App\Models\Admin\Cabinet::whereIn('id', $cabinetUsers)
                                        ->where('status', 1)
                                        ->first();
                                }

                            @endphp
                            <small class="text-green">{{ (!empty($cabinetUser)) ? availableField($cabinetUser->cabinet->name_english, $cabinetUser->cabinet->name_english,$cabinetUser->cabinet->name_urdu,$cabinetUser->cabinet->name_arabic) . " /" : '' }}</small>
                            <small class="text-green">{{ (!empty($role)) ? availableField($role->name_english, $role->name_english, $role->name_urdu, $role->name_arabic) : '' }}</small>
                            @if (!empty($designation))
                            <?php echo "/"; ?>
                                <small class="text-green">{{ (!empty($designation)) ? strtok(trim($designation->name_english), '/') : '' }}</small>
                                @if(!empty($cabin_nam))
                                    <small class="text-green">{{ (!empty($cabin_nam)) ? availableField($cabin_nam->name_english, $cabin_nam->name_english, $cabin_nam->name_urdu, $cabin_nam->name_arabic) : '' }}</small>
                                @endif
                            @endif
                        </p>
                    @endif

                    @can('update', $user)
                        <div class= "d-flex">
                            <p>
                                <small class="text-blue text-normal">{{ auth()->user()->email }}</small>
                            </p>
                        </div>
                    @endcan
                </div>

                @if(isset($friend))
                    @if(isset($friendStatus) && isset($friend))
                        @if($friendStatus->user_1 == auth()->user()->id && $friendStatus->status == 0)
                            <button class="ms-4 btn btn-warning friend-request-button" disabled>{{ __('app.request-sent') }}</button>
                        @endif
                        @if($friendStatus->status == 1)
                            <form action="{{ route('user.friend-request') }}" method="post" id="friendForm">
                                <input type="hidden" name="user_id" value="{{ $friend }}">
                                <input type="hidden" value="unfriend" name="unfriend">
                                <button onclick="friendRequest(this)" data-friend-id="{{ $friend }}" class="ms-4 btn btn-warning friend-request-button">
                                    @csrf
                                    {{ __('app.unfriend') }}
                                </button>
                            </form>
                        @endif

                        @if($friendStatus->status == 2)
                            <form action="{{ route('user.friend-request') }}" method="post" id="friendForm">
                                <input type="hidden" name="user_id" value="{{ $friend }}">
                                <input type="hidden" name="again_friend" value="{{ $friend }}">
                                <button onclick="friendRequest(this)" data-friend-id="{{ $friend }}" class="ms-4 btn btn-warning friend-request-button">
                                    @csrf
                                    {{ __('app.send-request') }}
                                </button>
                            </form>
                        @endif

                        {{-- case 2--}}
                        @if($friendStatus->user_1 == $friend && $friendStatus->status == 0)
                            <button class="ms-4 btn btn-warning friend-request-button" disabled>{{ __('app.request-received') }}</button>
                        @endif
                    @else
                        <form action="{{ route('user.friend-request') }}" method="post" id="friendForm">
                            <input type="hidden" name="user_id" value="{{ $friend }}">
                            <button onclick="friendRequest(this)" data-friend-id="{{ $friend }}" class="ms-4 btn btn-warning friend-request-button">
                                @csrf
                                {{ __('app.send-request') }}
                            </button>
                        </form>
                    @endif
                @endif
            </div>
            <ul class="ul-style profile-intro-list">
                <li class="d-flex align-items-center">
                    <p class="d-flex"><span class="user_tagline">{{availableField($user->tagline, $user->tagline_english, $user->tagline_urdu, $user->tagline_arabic) ?? __('app.your-tagline-here') }}</span> @can('update', $user)<button class="btn" data-toggle="modal" data-target="#updateTaglineModal"><span class="profile-icon"> <i class="fa fa-edit" aria-hidden="true"></i> @endcan</span></button></p>
                </li>
            </ul>
            <div class="d-flex flex-md-row flex-column align-items-md-center">
                <p class="me-md-3">{{__('app.blood-group')}} <span class="me-2" id="blood_section">{{  $user->blood_group_english ?? __('app.not-given') }}</span>@can('update', $user)<button class="btn p-0" data-toggle="modal" data-target="#bloodModal"><span class="profile-icon"> <i class="fa fa-edit" aria-hidden="true"></i> @endcan</span></button></p>
                <p class="me-md-3"><span class="user_address">{{availableField($user->address, $user->address_english, $user->address_urdu, $user->address_arabic) ?? __('app.your-address-here') }}</span> @can('update', $user)<button class="btn p-0" data-toggle="modal" data-target="#updateAddressModal"><span class="profile-icon"> <i class="fa fa-edit" aria-hidden="true"></i> @endcan</span></button></p>
                <p><span class="user_cnic">{{__('app.cnic')}} : {{str_replace('-', '', $user->cnic) ?? __('app.your-cnic') }}</span> @can('update', $user)<button class="btn p-0" data-toggle="modal" data-target="#updateCnicModal"><span class="profile-icon"> <i class="fa fa-edit" aria-hidden="true"></i> @endcan</span></button></p>
            </div>
            @can('update', $user)
                <div class="d-flex align-items-center profile-visibility justify-content-between flex-wrap">
                    @if(empty(auth()->user()->donor))
                        <button class="theme-btn-borderd-btn theme-btn text-capitalize mb-2 " href="/"  data-toggle="modal" data-target="#donorModal" aria-hidden="true" onclick="update_donor_details()">
                            {{__('app.add-blood-donners')}}
                        </button>
                    @endif

                    <div class="form-group table-form select-wrap d-flex flex-sm-row flex-column align-items-sm-center align-items-start custom-select mt-ms-0 mt-2">
                        <label class="sort-form-select me-2"> {{__('app.select-visibility')}} </label>
                        <div class="dropdown store-dropdown">
                            <button onclick="myFunction()" class="btn form-select d-flex justify-content-between align-items-center bg-white w-100" type="button">

                            <span class="fetched_visibility_status">
                                @if(auth()->user()->is_public == 0)
                                {{__('app.private')}}
                                @else
                                {{__('app.public')}}
                                @endif
                            </span>

                                <i class="fa fa-angle-down store-icon" aria-hidden="true"></i>
                            </button>
                            <ul id="myDIV" class="my-dropdown-menu profile_visibility" aria-labelledby="dropdownMenuButton1">
                                <li><a onclick="profileVisibility(this)" data-id="0" data-text="{{__('app.private')}}" class="dropdown-item" href="javascript:void(0)">{{__('app.private')}}</a></li>
                                <li><a onclick="profileVisibility(this)" data-id="1" data-text="{{__('app.public')}}" class="dropdown-item" href="javascript:void(0)">{{__('app.public')}}</a></li>
                            </ul>
                        </div>
                    </div>
                    @endcan
                    <div class="form-group table-form select-wrap d-flex flex-sm-row flex-column align-items-sm-center align-items-start custom-select mt-ms-0 mt-2">
                        <label class="sort-form-select me-2">{{__('app.occupation')}}:</label>
                        <button class="btn" data-toggle="modal" onclick="userOccupation({{ $user->id }})"><span class="profile-icon"> <i class="fa fa-edit" aria-hidden="true"></i></span></button>
                        {{-- <select onchange="profileOccupation(this)" class="form-select" style="width: 100%">
                            <option value="">{{__('app.select-occupation')}}</option>
                            @foreach($occupations as $occupation)
                                <option  value="{{ $occupation->id }}" {{ $occupation->id==auth()->user()->occupation_id ? 'selected' : '' }} > {{ $occupation->title }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    @can('update', $user)
                    <div>
                        <button class="theme-btn-borderd-btn theme-btn text-capitalize mt-2 " href="/"data-toggle="modal" data-target="#create_resume_modal" aria-hidden="true" onclick="create_resume_modal()">
                            {{__('app.create-resume')}}
                        </button>
                    </div>
                </div>
            @endcan

        </div>
    </div>
    {{--<div class="profile-gap profile-feature">
        <div class="wrap-subheading d-flex justify-content-between align-items-center">
            <div>
                <h4 class="profile-chunks-title">{{__('app.my-post')}}</h4>
            </div>
            <div class="d-flex align-items-center">
                --}}{{-- <span class="profile-icon me-3"><i class="fa fa-plus" aria-hidden="true"></i></span>
                <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span> --}}{{--
            </div>
        </div>
        <div class="feature-card responsive user-profile-feature-slider">
            @forelse($posts as $post)
            <div class="feature-cards">
                <div class="feature-card-header">
                    <span class="grey-color post-text">Post</span>
                    <p>{{Str::limit($post->title, 43, $end='.......')}}</p>
                </div>
                @if (isset($post->images[0]->file))
                <figure class="mb-0 feature-carousal-img">
                    @php
                    $file = $post->images[0]->file;
                    $image_extensions = ["jpg","jpeg","png","bmp","svg","gif","webp"];
                    $video_extensions = ["flv","mp4","m3u8","ts","3gp","mov","avi","wmv"];
                    $get_mimes=\File::extension($file)
                    @endphp
                    @if (in_array($get_mimes , $image_extensions))
                    <div class="item">
                        <img src="{{ url($file) }}" class="img-fluid" alt="image">
                    </div>
                    @endif
                    @if (in_array($get_mimes , $video_extensions))
                    <div class="item">
                        <video id="myvideo" preload="none" width="100%" height="280px" type="video/{{ $get_mimes }}" muted controls>
                            <source src="{{ url($file) }}">
                        </video>
                    </div>
                    @endif

                </figure>

                @endif
                <div class="feature-card-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="icon-wrap d-flex align-items-center">
                            <div class="round-icon bg-blue-icon me-2"><i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
                            <p class="grey-color">{{ $post->likes_count }}</p>
                        </div>
                        <p class="grey-color">{{ $post->comments_count }} Comments</p>
                    </div>
                </div>
            </div>
            @empty
            <p>no posts yet!</p>
            @endforelse
        </div>
    </div>--}}
    <div class="profile-gap profile-about">
        <div class="wrap-subheading d-flex justify-content-between align-items-center">
            <div>
                <h4 class="profile-chunks-title">{{__('app.about')}}</h4>
            </div>
            <div class="d-flex align-items-center">
                <span class="profile-icon">
                    @can('update', $user)
                        <i class="fa fa-edit" aria-hidden="true" data-toggle="modal" data-target="#aboutMoadl"></i>
                    @endcan
                </span>
            </div>
        </div>
        <span class="about_section">
            {!!availableField($user->about, $user->about_english, $user->about_urdu, $user->about_arabic) ?? __('app.not-given') !!}
        </span>
    </div>
    <div class="profile-gap profile-experience">
        <div class="wrap-subheading d-flex justify-content-between">
            <div>
                <h4 class="profile-chunks-title">{{__('app.my-experiences')}}</h4>
            </div>
            <div class="d-flex align-items-center">
                @can('update', $user)
                <span class="profile-icon me-3">
                        <i data-toggle="modal" data-target="#experienceModal" class="fa fa-plus" aria-hidden="true"></i>
                </span>
                @endcan
                {{-- <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span> --}}
            </div>
        </div>
        <ul class="ul-style wrap-todo experience_list">
            @forelse($user->experience as $experience)
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
                                        <span class="todo-title">{{ availableField($experience->title, $experience->title_english, $experience->title_urdu, $experience->title_arabic) }}</span> <br>
                                        <small>{{ availableField($experience->experience_company, $experience->experience_company_english, $experience->experience_company_urdu, $experience->experience_company_arabic) }}</small>
                                        <div class="d-flex flex-md-row flex-column align-items-md-center">
                                            <p class="me-lg-4 me-3 dot d-flex align-items-center">{{ $experience->experience_start_date }} - {{ $experience->is_currently_working == 1 ? __('app.currently-working') : $experience->experience_end_date }}</p>
                                            <p> {{ $experience->created_at->diffForHumans()}}</p>
                                        </div>
                                        <p>{{ availableField($experience->experience_location, $experience->experience_location_english, $experience->experience_location_urdu, $experience->experience_location_arabic) }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    @can('update', $user)
                        <button type="submit" onclick="deleteExperience(this)" data-id="{{ $experience->id }}" class="btn btn-danger btn-sm me-2">{{__('app.delete')}}</button>
                        <button type="submit" onclick="editExperience(this)" data-id="{{ $experience->id }}" class="btn btn-warning btn-sm">{{__('app.edit')}}</button>
                    @endcan
                </div>
            </div>
            @empty
            <li>{{__('app.no-added-yet')}}</li>
            @endforelse
        </ul>
    </div>
    <div class="profile-gap profile-education">
        <div class="wrap-subheading d-flex justify-content-between">
            <div>
                <h4 class="profile-chunks-title">{{__('app.my-educations')}}</h4>
            </div>
            <div class="d-flex align-items-center">
                @can('update', $user)
                <span class="profile-icon me-3" data-toggle="modal" data-target="#addEducationModal">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
                @endcan
                {{-- <span class="profile-icon"><i class="fa fa-edit" aria-hidden="true"></i></span> --}}
            </div>
        </div>
        <div class="ul-style wrap-todo education_list">
            @forelse($user->education as $education)
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
                                    <span class="todo-title">{{ availableField($education->institute, $education->institute_english, $education->institute_urdu, $education->institute_arabic) }}</span>
                                    <p>{{ availableField($education->degree_name, $education->degree_name_english, $education->degree_name_urdu, $education->degree_name_arabic) }}</p>
                                    <p>{{ date('Y',strtotime($education->start_date)) .' - '. date('Y',strtotime($education->end_date)) }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="button-wraper">
                    @can('update', $user)
                        <button type="submit" onclick="deleteEducation(this)" data-id="{{ $education->id }}" class="btn btn-danger btn-sm">{{__('app.delete')}}</button>
                        <button type="submit" onclick="editEducation(this)" data-id="{{ $education->id }}" class="btn btn-warning btn-sm">{{__('app.edit')}}</button>
                    @endcan
                </div>
            </div>
            </hr> @empty <p>{{__('app.no-added-yet')}}</p>
            @endforelse
        </div>
    </div>
     {{-- Skills Data  --}}

     <div class="profile-gap profile-education">
        <div class="wrap-subheading d-flex justify-content-between">
            <div>
                <h4 class="profile-chunks-title">{{__('app.my-skills')}}</h4>
            </div>
            <div class="d-flex align-items-center">
                @can('update', $user)
                <span class="profile-icon me-3" data-toggle="modal" data-target="#skillModal">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
                @endcan

            </div>
        </div>
        @php
        if($user->skills ?? availableField($user->skills, $user->skills_english, $user->skills_urdu, $user->skills_arabic)){
            $skills=explode(',',$user->skills ?? availableField($user->skills, $user->skills_english, $user->skills_urdu, $user->skills_arabic));
        }else{
            $skills =[];
        }

        @endphp
        <div class="ul-style wrap-todo " id="skillsDiv">
            @forelse($skills as $key=>$val)
            <div class="career-detail d-flex justify-content-between mt-2" >
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

    <div class="profile-gap profile-feature">
        <div class="wrap-subheading d-flex justify-content-between align-items-center">
            <div>
                <h4 class="profile-chunks-title">{{__('app.my-posts')}}</h4>
            </div>
        </div>
        <div class="post-details">
            <div class="row">
                <input id="user-id" type="hidden" value="{{ Auth::check() ? Auth::id() : '' }}">
                <div id="posts-data">
                    @forelse($posts as $post)
                        <div class="{{ $post->shared_user != null ? 'user-shared-post' : 'post-details-bg mb-3' }}" data-main-post="{{ $post->id }}">
                            @if($post->shared_user != null)
                                <div class="post-shared d-flex align-items-md-center justify-content-between">
                                    <div class="d-flex flex-md-row flex-column ">
                                        <div class="shared-profile mb-md-0 mb-3">
                                            <img class="start-a-post-profile" src="{{ getS3File($post->shared_user->profile_image) }}" alt="profile">
                                        </div>
                                        <div class="d-flex flex-column ms-3">
                                            <p><strong>{{ $post->shared_user->user_name }} </strong>{{ __('app.has-shared-post') }}</p>
                                            <p>{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if (!empty($post->shared_user) && $post->shared_user->id == auth()->user()->id)
                                        <div class="delete-post-btn">
                                            <i data-post-id="{{ $post->id }}" style="cursor: pointer" class="fa fa-trash delete_post"></i>
                                        </div>
                                    @endif
                                </div>

                            @endif
                            <div class="col-12 mb-3">
                                <div>
                                    <div class="post-header d-flex justify-content-between">
                                        <div class="d-flex flex-md-row flex-column align-items-md-center">
                                            <figure class="mb-0 me-lg-3 me-2">
                                                <img class="start-a-post-profile" src="{{ getS3File($post->user->profile_image ?? $post->admin->profile) }}" alt="" class="img-fluid">
                                            </figure>
                                            <div class="post-creator-profile w-100">
                                                <h2 class="person-name text-capitalize">
                                                    <a style="color: black" href="{{ ($post->user_id != null || $post->user_id != '') ? route('user.profile', hashEncode($post->user_id)) : '#' }}">
                                                        {{ $post->admin->name ?? $post->user->user_name }}
                                                    </a>
                                                </h2>
                                                <div class="d-flex align-items-center justify-content-between w-100">
                                                    <div class="d-flex">
                                                        <figure class="mb-0 me-2">
                                                            <img src="./images/public-icon.png" alt="" class="img-flud">
                                                        </figure>
                                                        <span>{{ $post->user != null && $post->user->is_public == 0 ? __('app.private') : __('app.public') }}</span>

                                                    </div>
                                                    {{--<div class="d-flex">
                                                        @if($post->user_id == auth()->user()->id && $post->shared_user == null)
                                                            <span data-toggle="modal" data-target="#editPostModal" data-post-id="{{ $post->id }}" style="cursor: pointer" class="edit_post"><strong>...</strong></span>
                                                        @endif
                                                    </div>--}}
                                                </div>
                                            </div>
                                        </div>
                                        @if($post->user_id == auth()->user()->id && $post->shared_user == null)
                                            <div class="d-flex flex-md-row flex-column align-items-md-center">
                                                <div class="d-flex me-2">
                                                    <span data-toggle="modal" data-target="#editPostModal" data-post-id="{{ $post->id }}" style="cursor: pointer;" class="edit_post edit-post-btn">
                                                        <strong><i class="fa fa-edit"></i></strong></span>
                                                </div>
                                                <div class="d-flex delete-post-btn">
                                                    <i data-post-id="{{ $post->id }}" style="cursor: pointer" class="fa fa-trash delete_post"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-3 mb-3">
                                        @if ($post->status==0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($post->status==1)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </div>
                                    <p class="mb-lg-3 mb-2 post_title {{ $post->lang=='urdu' ? 'ur-direction'  : 'en-direction' }}" data-post-title-id="{{ $post->id }}" data-post-detail="{{ $post->title }}" data-splitted-post-detail="{{ Str::length($post->title) >=350 ? Str::limit($post->title, 350, '') : '' }}">{!! Str::length($post->title) >=350 ? Str::limit($post->title, 350, ' ...<a href="javascript:void(0)" onclick="togglePostTitle(this)" id="'.$post->id.'">'.__('app.read-more').'</a>') : $post->title !!}</p>
                                    <!-- if post is product post-->
                                    @if($post->post_type == '4')
                                        <div class="d-flex justify-content-end align-items-end mb-3"><a data-product-id="{{ $post->product_id }}" href="{{ route('user.add-cart',['quantity' => '1','product_id'=>$post->product_id,'type'=> 'product_shop']) }}" type="submit" class="btn chat-user-name">{{ __('app.shop_now') }}</a>
                                        </div>
                                    @endif

                                    <!--if post is job hiring post-->
                                    {{--@if ($post->post_type == '2' && $post->job_type=='1')
                                        <div class="d-flex justify-content-end align-items-end mb-3"><a
                                                data-post-id="{{ $post->id }}" href="javascript:void(0)"
                                                class="btn chat-user-name" data-toggle="modal" data-target="#applyJobModal"
                                                onclick="applyNow(this)">apply now</a>
                                        </div>
                                    @endif--}}
                                    <!--if post is job seeking post-->
                                    @if ($post->post_type == '2' && $post->job_type=='2')
                                    <table class="table" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('app.occupation') }} :</th>
                                                <td>{{ $post->occupation }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.experience') }} :</th>
                                                <td>{{ $post->experience }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.skills') }} :</th>
                                                <td>{{ $post->skills }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.resume') }} :</th>
                                                <td><a href="{{ asset($post->resume) }}" target="_blank">{{ __('app.download-resume') }}</a></td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.description') }} :</th>
                                                <td>{{ $post->description_english }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endif
                                    <!--if post is Blood post-->
                                    @if ($post->post_type == '5')
                                    <table class="table" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('app.city') }} :</th>
                                                <td>{{ $post->citi->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.hospital') }} :</th>
                                                <td>{{ $post->hospital }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('app.address') }} :</th>
                                                <td>{{ $post->address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endif
                                    <figure class="mb-0 post-img">
                                        <div class="user-panel-post-slider {{ ($post->images->count() >1 )? 'owl-carousel dynamic_owl':'' }} owl-theme">
                                            @foreach($post->images as $image)
                                                @php
                                                    $image_extensions = ["jpg","jpeg","png","bmp","svg","gif","webp"];
                                                    $video_extensions = ["flv","mp4","m3u8","ts","3gp","mov","avi","wmv"];
                                                    $get_mimes=\File::extension($image->file)
                                                @endphp
                                                @if (in_array($get_mimes , $image_extensions))
                                                    <div class="item">
                                                        <img src="{{ getS3File($image->file) }}" class="img-fluid" alt="image">
                                                    </div>
                                                @endif
                                                @if (in_array($get_mimes , $video_extensions))
                                                    <div class="item">
                                                        <video id="myvideo" preload="none" width="100%" height="350" type="video/{{ $get_mimes }}" muted controls>
                                                            <source src="{{ getS3File($image->file) }}">
                                                        </video>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </figure>
                                    <div class="media-icon">
                                        <ul>
                                            @if(have_permission('Like-News-Feed-Posts'))
                                                <li>
                                                    <a href="javascript:void(0)">
                                                        <div class="d-flex align-items-center">
                                                            <i data-likes-counter="{{ hashEncode($post->id) }}"
                                                            class="fa fa-thumbs-up me-2 like_post {{ $post->is_like == 1 ? 'text-green' : '' }}"
                                                            aria-hidden="true">
                                                                <span>{{ $post->likes_count }} {{ ($post->likes_count>1)?__('app.likes'):__('app.like')}}</span>
                                                            </i>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                            @if(have_permission('Add-Comment-News-Feed-Posts'))
                                                <li>
                                                    <a href="javascript:void(0)">
                                                        <div class="d-flex align-items-center">
                                                            <i data-post-id="{{ hashEncode($post->id) }}"
                                                            class="fa fa-comment me-2 comment_button read_comments" aria-hidden="true">
                                                            <span
                                                                data-comments-counter="{{ hashEncode($post->id) }}">{{ $post->comments_count }} {{ ($post->comments_count>1)?__('app.comments'):__('app.comment')}}
                                                            </span>
                                                            </i>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                            @if(have_permission('Share-News-Feed-Posts'))
                                                <li>
                                                    <a href="">
                                                        <div class="news-feed-share drop-down">
                                                            <div id="dropDown_{{ $post->id }}" class="drop-down__button">
                                                                <a>
                                                                    <span><i class="fa fa-share me-2" aria-hidden="true"></i></span>{{__('app.share')}}
                                                                </a>
                                                            </div>
                                                            <div class="drop-down__menu-box">
                                                                <ul class="drop-down__menu">
                                                                    {{--<li data-name="profile">
                                                                        <a data-post-id="{{ $post->id }}" class="drop-down__item mustafai_timeline_share" href="javascript:void(0)">{{ __('app.profile') }}</a>
                                                                    </li>--}}
                                                                    <li data-name="profile">
                                                                        <span class="fa fa-twitter"></span>
                                                                        <a class="drop-down__item" target="_blank" href="https://twitter.com/intent/tweet?text={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.twitter') }}</a>
                                                                    </li>
                                                                    <li data-name="profile">
                                                                        <span class="fa fa-facebook"></span>
                                                                        <a class="drop-down__item" target="_blank" href="https://www.facebook.com/dialog/share?app_id=656385503002340&display=popup&href={{ route('user.specific-post', hashEncode($post->id)) }}&redirect_uri={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.facebook') }}</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div data-comment-div="{{ hashEncode($post->id) }}" class="addcomment mt-3 comment_div"
                                         style="display: none">
                                        {{-- <a data-post-id="{{ $post->id }}" class="small-text graish-color cri-pointer read_comments">{{__('app.read-comments')}}</a>--}}
                                        <ul data-comments-list="{{ hashEncode($post->id) }}" class="comments_list" style="display: none">
                                            @if($post->comments_count > 0)
                                                @foreach($post->comments as $comment)
                                                    <li data-comment-element="{{ $comment->id }}" class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <img class="start-a-post-profile" src="{{ getS3File($comment->user->profile_image) }}" alt="" class="img-fluid"> <b>{{ $comment->user->user_name ?? 'guest'  }}</b> {{ $comment->body }}
                                                        </div>
                                                        @can('update', $comment->user)
                                                            <i data-comment-id="{{ hashEncode($comment->id)  }}" onclick="deleteComment(this)" class="fa fa-trash dell-btn"></i>
                                                        @endcan
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="d-flex relative mt-3 add-comments">
                                            <input data-comment-id="{{ hashEncode($post->id) }}"
                                                   class="form-control form-control-lg  comment_input" type="text" data-comment-id-input="{{ hashEncode($post->id) }}"
                                                   placeholder="{{__('app.add-a-comment')}}..." aria-label=".form-control-lg example"/>
                                            <span data-post-id="{{ hashEncode($post->id) }}" class="send-msg btn-primary send_comment">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                     <p>{{__('app.no-data-available')}}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('user.profile-modals')

<!-- Edit Post Modal -->
<div class="modal fade library-detail common-model-style" id="editPostModal" tabindex="-1" role="dialog"
     aria-labelledby="editPostModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content create-post-modal">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.edit-post') }}</h4>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="edit_modal_body">
                {{-- dynamic content here --}}
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- tags input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
@include('user.scripts.profile-script')
@include('user.scripts.post-comment-script')
@include('user.scripts.add-friend-script')
@include('user.scripts.post-like-script')
@include('user.scripts.delete-comment-script')
@include('user.scripts.create-post-script')
@include('home.partial.apply-job-script')
@include('home.partial.edit-post-script')
@include('user.scripts.post-share-script')
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script>
    $(":input").inputmask();
</script>
<script>
  $('#address_input').val($('.user_address').text());
  $("#country_id").select2();

  function togglePostTitle(_this, hide = false) {
      let id = $(_this).attr('id')
      let titleElement = $('[data-post-title-id="' + id + '"]')

      if (hide) {
          titleElement
              .text(titleElement.attr('data-splitted-post-detail')).
          append(`<a href='javascript:void(0)' onclick='togglePostTitle(this, false)' id=${id}>{{ __('app.read-more') }}</a>`)
          return 1
      }

      titleElement.
      text(titleElement.attr('data-post-detail')).
      append(`<a href='javascript:void(0)' onclick='togglePostTitle(this, true)' id=${id}>{{ __('app.read-less') }}</a>`)
      return 1
  }

</script>
<script>
    function sameAddress()
    {
        // alert('hi');
        // $( ".current-address" ).each(function( index ) {
            if($('#same-address').is(":checked"))
            {
                $('.permanent_address_div').css('display','none');
                // console.log($('.permanent-address').eq(index).val());
                // $( this ).val( $('.permanent-address').eq(index).val() );
                // $( this ).focus();
            }
            else{
                $('.permanent_address_div').css('display','block');
            }
        // });
    }

    if($('#same-address').is(":checked"))
    {
        $('.permanent_address_div').css('display','none');
    }
    else{
        $('.permanent_address_div').css('display','block');
    }
</script>
<script>
    $(".zone_id").on("change", function() {
    var selectedOption = $(this).val();
    var tehsil_id = $('#tehsil_select').val();
    $.ajax({
        url: "{{route('user.store-branch')}}",
        type: 'POST',
        data: {
        option: selectedOption,
        tehsil_id:tehsil_id
        },
        success: function(response) {
            console.log('Option stored successfully');
            var optionName = response.name;
            var optionValue = response.value;
            if(response.new){
                var newOption = new Option(optionName, optionValue, true, true);
                $('.zone_id').append(newOption).trigger('change');
            }
        },
        error: function(xhr, status, error) {
        console.error(error);
        }
    });
    });
</script>
<script>
    $(".zone_id_permanent").on("change", function() {
    var selectedOption = $(this).val();
    var tehsil_id = $('#tehsil_select_permanent').val();
    $.ajax({
        url: "{{route('user.store-branch')}}",
        type: 'POST',
        data: {
        option: selectedOption,
        tehsil_id:tehsil_id
        },
        success: function(response) {
            console.log('Option stored successfully');
            var optionName = response.name;
            var optionValue = response.value;
            if(response.new){
                var newOption = new Option(optionName, optionValue, true, true);
                $('.zone_id_permanent').append(newOption).trigger('change');
            }
        },
        error: function(xhr, status, error) {
        console.error(error);
        }
    });
    });
</script>
<script>
    $(document).ready(function () {
        $('#start_date').on('change', function () {
            var minDate=$('#start_date').val();
            var originalDate = new Date(minDate);
            var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
            var minDate = newDate.toISOString().split('T')[0];
            $('#end_date').attr('min', minDate);
            $('#end_date').val('');
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#experience_start_date').on('change', function () {
            var minDate=$('#experience_start_date').val();
            var originalDate = new Date(minDate);
            var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
            var minDate = newDate.toISOString().split('T')[0];
            $('#experience_end_date').attr('min', minDate);
            $('#experience_end_date').val('');
        });
    });
</script>

@include('user.scripts.delete-comment-script')
{{-- script for dynamic country, province...change --}}
@include('user.scripts.profile-address-script')
@endpush
