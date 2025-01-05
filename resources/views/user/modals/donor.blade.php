<!-- update banner modal   -->
<div class="modal fade library-detail common-model-style" id="donorModal" tabindex="-1" role="dialog" aria-labelledby="updateBannerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Update Banner Image</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.banner') }}" method="post" id="updateBannerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="banner" id="banner_input" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateBanner()" type="button" class="green-hover-bg theme-btn">Update</button>
            </div>
        </div>
    </div>
</div>


<!-- about modal   -->
<div class="modal fade library-detail common-model-style" id="aboutMoadl" tabindex="-1" role="dialog" aria-labelledby="aboutMoadl" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">About Your's</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form method="post" id="aboutForm">
                    @csrf
                    <div class="form-group">
                        <textarea id="summernote" name="about_input"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateAbout()" type="button" class="green-hover-bg theme-btn about_close_button">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- upadte profile modal -->
<div class="modal fade library-detail common-model-style" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Update Profile Image</h4>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.image') }}" method="post" id="updateProfileImageForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="profile_image" id="profile_image" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateProfileImage()" type="button" class="green-hover-bg theme-btn">Update</button>
            </div>
        </div>
    </div>
</div>


<!-- update tagline modal -->
<div class="modal fade library-detail common-model-style" id="updateTaglineModal" tabindex="-1" role="dialog" aria-labelledby="updateTaglineModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Update tagline</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.tagline') }}" method="post" id="updateTaglineForm">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" name="tagline" id="tagline_input" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateTagline()" type="button" class="green-hover-bg theme-btn">Update</button>
            </div>
        </div>
    </div>
</div>


<!-- update address modal -->
<div class="modal fade library-detail common-model-style" id="updateAddressModal" tabindex="-1" role="dialog" aria-labelledby="updateAddressModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-green" id="exampleModalLabel">Update address</h5>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.address') }}" method="post" id="updateAddressForm">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" name="address" id="address_input" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="updateAddress()" type="button" class="green-hover-bg theme-btn">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- experience modal -->
<div class="modal fade library-detail common-model-style" id="experienceModal" tabindex="-1" role="dialog" aria-labelledby="experienceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Add experience</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.experience') }}" class="dash-comon-form" method="post" id="addExperienceForm">
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <input class="form-control" type="text" name="experience_company" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input class="form-control" type="date" max="<?php echo date("Y-m-d"); ?>" name="experience_start_date" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input class="form-control" type="date" max="<?php echo date("Y-m-d"); ?>" name="experience_end_date" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input class="form-control" type="text" name="experience_location" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="addExperience()" type="button" class="green-hover-bg theme-btn">Add</button>
            </div>
        </div>
    </div>
</div>

<!--edit experience modal-->

<div class="modal fade library-detail common-model-style" id="editExperienceModal" tabindex="-1" role="dialog" aria-labelledby="editExperienceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Edit experience</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>


            </div>
            <div class="modal-body edit_experience_section">
                <!-- dynamic section here-->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="editExperienceRequest()" type="button" class="green-hover-bg theme-btn">Edit</button>
            </div>
        </div>
    </div>
</div>


<!-- education modal -->
<div class="modal fade library-detail common-model-style" id="addEducationModal" tabindex="-1" role="dialog" aria-labelledby="addEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Add Education</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('user.education') }}" method="post" class="dash-comon-form" id="addEducationForm">
                    @csrf
                    <div class="form-group">
                        <label>Institute name</label>
                        <input class="form-control" type="text" name="institute" required>
                    </div>
                    <div class="form-group">
                        <label>Degree name</label>
                        <input class="form-control" type="text" name="degree_name" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input class="form-control" type="date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input class="form-control" type="date" name="end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="addEducation()" type="button" class="green-hover-bg theme-btn">Add</button>
            </div>
        </div>
    </div>
</div>

<!--edit education modal-->

<div class="modal fade library-detail common-model-style" id="editEducationModal" tabindex="-1" role="dialog" aria-labelledby="editEducationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">Edit education</h4>
                <button type="button" class="btn-close close" data-dismiss="modal"></button>

            </div>
            <div class="modal-body edit_education_section">
                <!-- dynamic section here-->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button onclick="editEducationRequest()" type="button" class="green-hover-bg theme-btn">Edit</button>
            </div>
        </div>
    </div>
</div>
