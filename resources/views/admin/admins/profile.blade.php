@extends('admin.layout.app')

@push('header-scripts')
   <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
 <!-- for sweet alert  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">

@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Mustafai Admin</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
            <div class="row">
              <div class="col-md-3">

                <div class="card card-primary card-outline">
                  <div class="card-body box-profile">
                    <div class="text-center">
                      <img class="profile-user-img img-fluid img-circle"
                      src="{{ getS3File(Auth::user()->profile) }}"
                      alt="User profile picture" onclick="uploadProfilePic()" id="sample_image">

                    </div>
                    <h3 class="profile-username text-center">{{ $first_name . ' ' . $last_name  }}</h3>
                    <p class="text-muted text-center">{{ Auth::user()->role->name }}</p>
                    <form class="form-horizontal" id="uplode_pic_form" action="{{ URL('admin/update-pic') }}" method="POST" >
                      {!! csrf_field() !!}
                       <input type="file" id="user_profile_pic" name="image"  class="d-none"   accept="image/*">
                    </form>
                  </div>
                </div>

              </div>
              <!-- /.col -->
              <div class="col-md-9">
                <div class="card">
                  {{-- <div class="card-header p-2">
                    <ul class="nav nav-pills">
                      <li class="nav-item"><a class="nav-link active" data-toggle="collapse" data-target="#collapseSettting" aria-expanded="true" aria-controls="collapseSettting">Settings</a></li>
                    </ul>
                  </div> --}}
                  <!-- /.card-header -->
                  <div class="collapse show" id="collapseSettting">
                  <div class="card-body">
                    <div class="tab-content">

                      <div class="tab-pane active" id="settings">
                        <form class="form-horizontal" id="profile_setting" action="{{ URL('admin/profile') }}" method="POST">
                           {!! csrf_field() !!}
                          <div class="form-group row">
                            <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="{{ $first_name }}" required>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{ $last_name }}" required>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                              <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ $email }}" readonly>
                            </div>
                          </div>
                          <div class="row d-flex justify-content-end" >

                            <p class="">
                              <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" onclick="requiredFrom()">
                               Change Password
                              </button>
                            </p>
                          </div>
                          <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                              <div class="row dash-pass-sh-hi">
                                <div class="col-md-4">
                                  <label for="old_password" class="">Old Password</label>
                                  <div class="form-group  show-hide-pass-eye div-custom">
                                    <input type="password" class="form-control" id="old_password" placeholder="old password" name="old_password"  autocomplete="off"  required>
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                  </div>

                                </div>
                                <div class="col-md-4">
                                  <label for="password" class="">New Password</label>
                                  <div class="form-group  show-hide-pass-eye div-custom">
                                    <input type="password"  passwordCheck_adminSetting="passwordCheck_adminSetting" class="form-control" id="password" placeholder="new password" name="password"   required>
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                  </div>

                                </div>
                                <div class="col-md-4">
                                  <label for="confirm_password" class="">Confirm Password</label>
                                  <div class="form-group  show-hide-pass-eye div-custom">
                                    <input type="password" class="form-control" id="confirm_password" placeholder="confirm password" name="confirm_password"  required>
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                                  </div>

                                </div>

                              </div>

                            </div>
                          </div>
                          <div class="form-group d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary">Update</button>
                          </div>
                        </form>
                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div><!-- /.card-body -->
                </div>
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
  </div>

@endsection

@push('footer-scripts')


  <!-- jQuery -->
  <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- DataTables  & Plugins -->
  <script src="{{asset('assets/admin/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/admin/jszip/jszip.min.js')}}"></script>
  <script src="{{asset('assets/admin/pdfmake/pdfmake.min.js')}}"></script>
  <script src="{{asset('assets/admin/pdfmake/vfs_fonts.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('assets/admin/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
  	<!-- jquery-validation -->
	<script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
	<script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
  	<!--sweat alert -->
	{{-- <script src="{{asset('assets/admin/sweetalert/sweetalert.min.js')}}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js">
	</script>

  <!-- Page specific script -->
  <script>


   //_________For Click On Image______________//
    function uploadProfilePic()
    {
        $('#user_profile_pic').trigger('click');
    }
    //__________For select Any Image____________//
    user_profile_pic.onchange = evt =>
    {
			const [file] = user_profile_pic.files
      if(!checkFileExtentions(file)){
        //   alert("invalid extension! ");
          swal("Oops!", "invalid extension! ", "error");

          return 0;
		  }
      var formData = new FormData($('#uplode_pic_form')[0]);
        $.ajax({
            type: "POST",
            url: '{{url("admin/update-pic")}}',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
              console.log(data)
              if(data)
              {
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : true
                }
                toastr.success('Profile Pic Successfully Update');
              }
            }
        });
			if (file) {
				sample_image.src = URL.createObjectURL(file);
                admin_pic.src=URL.createObjectURL(file);
			}

		}
    //_________check Extention Of File__________//
  	function checkFileExtentions(f)
    {

      let fExtention =f.name.split('.').pop().toLowerCase();
      if($.inArray(fExtention, ['gif','png','jpg','jpeg','webp']) == -1)
      {
        return 0;
      }
      else
      {
        return 1;
      }

    }
 //___________Validation Of Profile Setting form________________//

 jQuery.validator.addMethod("passwordCheck_adminSetting",
        function(value, element, param) {
            if (this.optional(element)) {
                return true;
            } else if (!/[a-z]/.test(value)) {
                return false;
            } else if (!/[0-9]/.test(value)) {
                return false;
            }

            return true;
        },
        "Password must contain characters and digits");

    $('#profile_setting').validate({
        ignore:":not(:visible)",
        rules:
        {
          password: {
              minlength: 8
          },
          confirm_password: {
              minlength: 8,
              equalTo: "#password"
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {

          // $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          // $(element).removeClass('is-invalid');


        },

			});

  </script>
@endpush
