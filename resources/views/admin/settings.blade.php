@extends('admin.layout.app')

@push('header-scripts')
   <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
@endpush

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Site Setting</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ URL('admin/admins') }}">Admin Users</a></li>
              <li class="breadcrumb-item active">Create Admin User</li>
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
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Setting Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form id="user-form" action="{{ URL('admin/site-setting') }}" enctype="multipart/form-data" method="POST">
                        {!! csrf_field() !!}

                    <div class="accordion" id="accordionExample">
                      <div class="card">

                        <!-- For Logo -->
                      <div class="card-header" id="title-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
                            Logo
                          </button>
                        </h2>
                      </div>

                      <div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                        <div class="card-body">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Logo *</label>
                            <div class="col-sm-6">
                              <input type="file"  class="form-control" placeholder="Select Logo" id="imageinpt" name="logo" value="" >
                                <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 180 x 50 </span> pixels</small>
                            </div>
                            {{-- <div class="col-sm-2">
                              @if($row->image)
                              <a href="{{ asset($row->image) }}" target="_blank">
                                <img src="{{ asset($row->image) }}" alt="" id="sample_image" width="100" height="100">
                              </a>
                              <button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
                              @else
                                <a href="javascrit:void(0)" target="_blank">
                                  <img id="sample_image" src="{{ asset('images/dummy-images/dummy.PNG') }}" alt="your image" width="60" height="60" />
                                </a>
                                <button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
                                @endif
                            </div> --}}
                          </div>

                        </div>
                      </div>
                       <!-- For Video -->
                       <div class="card-header" id="title-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#video" aria-expanded="true" aria-controls="video">
                            Video
                          </button>
                        </h2>
                      </div>

                      <div id="video" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
                        <div class="card-body">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Banner video *</label>
                            <div class="col-sm-6">
                              <input type="file"  class="form-control" placeholder="Select video" id="videoinpt" name="video" accept="video/mp4,video/x-m4v,video/*"  value="" onchange="Filevalidation()">
                              <small class="d-block text-muted  mt-1">Please add video file as per mentioned restrictions less than <span class="text-danger"> 8Mb </span></small>
                            </div>
                          </div>

                        </div>
                      </div>
                        <!-- For  title   -->
                          <div class="card-header" id="title-heading">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#title" aria-expanded="true" aria-controls="title">
                                Title
                              </button>
                            </h2>
                          </div>

                          <div id="title" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label"> Title  *</label>
                                  <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Enter First Name" name="title" value="{{isset($settings['title']) ? $settings['title'] : ''}}">
                                </div>

                              </div>
                            </div>
                        </div>

                            <!-- For email  -->
                          <div class="card-header" id="title-heading">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colemail" aria-expanded="true" aria-controls="colemail">
                                Email
                              </button>
                            </h2>
                          </div>

                          <div id="colemail" class="collapse" aria-labelledby="colemail-heading" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label"> Email  *</label>
                                  <div class="col-sm-6">
                                    <input type="email" class="form-control" placeholder="Enter Last Name" name="email" value="{{isset($settings['email']) ? $settings['email'] : ''}}">
                                </div>

                              </div>
                            </div>
                        </div>


                          <!-- For email  -->
                          <div class="card-header" id="title-heading">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#emailForNotification" aria-expanded="true" aria-controls="colemail">
                                 Emails for Notification
                              </button>
                            </h2>
                          </div>

                          <div id="emailForNotification" class="collapse" aria-labelledby="colemail-heading" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="form-group row">
                                  <label class="col-sm-3 col-form-label"> Email for Notification  <span class="text-red">*</span></label>
                                  <div class="col-sm-6">
                                    <input type="email" class="form-control" placeholder="Email for Notification" name="emailForNotification" value="{{isset($settings['emailForNotification']) ? $settings['emailForNotification'] : ''}}">
                                </div>

                              </div>
                            </div>
                        </div>



                          <!-- For Phone  -->
                          <div class="card-header" id="title-heading">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colphone" aria-expanded="true" aria-controls="colphone">
                                Phone
                              </button>
                            </h2>
                          </div>

                          <div id="colphone" class="collapse" aria-labelledby="colphone-heading" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label"> Phone  *</label>
                                  <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Enter Last Name" name="phone" data-inputmask='"mask": "(+99) 9999999999"' data-mask value="{{isset($settings['phone']) ? $settings['phone'] : ''}}">
                                </div>

                              </div>
                            </div>
                        </div>

                        <!-- For  Whatsapp   -->
                        <div class="card-header" id="title-heading">
                          <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#whatsapp" aria-expanded="true" aria-controls="whatsapp" >
                              Whatsapp
                            </button>
                          </h2>
                        </div>

                        <div id="whatsapp" class="collapse" aria-labelledby="whatsapp-heading" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-2 col-form-label"> Whatsapp  *</label>
                                <div class="col-sm-6">
                                  <input type="text" class="form-control" placeholder="Enter First Name" name="whatsapp" data-inputmask='"mask": "(+99) 9999999999"' data-mask value="{{isset($settings['whatsapp']) ? $settings['whatsapp'] : ''}}">
                              </div>

                            </div>
                          </div>
                      </div>

                       <!-- For  Address   -->
                    {{-- <div class="card-header" id="title-heading">
                      <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#address" aria-expanded="true" aria-controls="address">
                          Address
                        </button>
                      </h2>
                    </div>

                    <div id="address" class="collapse" aria-labelledby="address-heading" data-parent="#accordionExample">
                      <div class="card-body">

                          <div class="row" >
                            @foreach (activeLangs() as $lang)

                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>Address  ({{ $lang->name_english }})</label>
                                <input type="text" class="form-control" placeholder="Adrress {{ $lang->name_english }}" name="name_{{ strtolower($lang->key) }}"   value="{{isset($settings['address_'.strtolower($lang->key).'']) ? $settings['address_'.strtolower($lang->key).''] : ''}}">
                                </div>
                              </div>
                            @endforeach

                            <div class="col-sm-4">
                              <div class="form-group ">
                              <label>Address Urdu</label>
                              <input type="text" class="form-control" placeholder="Adrress Urdu" name="address_urdu"  value="{{isset($settings['address_urdu']) ? $settings['address_urdu'] : ''}}">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                              <label>Address Arabic</label>
                              <input type="text" class="form-control" placeholder="Adrress Arabic" name="address_arabic"   value="{{isset($settings['address_arabic']) ? $settings['address_arabic'] : ''}}">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                              <label>Address Link</label>
                              <input type="text" class="form-control" placeholder="Adrress Link" name="address_link"   value="{{isset($settings['address_link']) ? $settings['address_link'] : ''}}">
                              </div>
                            </div>
                          </div>
                      </div>
                    </div> --}}

                      <!-- For  Opening Time   -->
                      <div class="card-header" id="title-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#colsopening" aria-expanded="true" aria-controls="colsopening">
                            Opening Time
                          </button>
                        </h2>
                      </div>

                      <div id="colsopening" class="collapse" aria-labelledby="colsopening-heading" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="form-group row">
                              <label class="col-sm-2 col-form-label"> Opening Time  *</label>
                              <div class="col-sm-6">
                                <input type="time" class="form-control" placeholder="Enter First Name" name="opening_time" value="{{isset($settings['opening_time']) ? $settings['opening_time'] : ''}}">
                            </div>

                          </div>
                        </div>
                    </div>

                     <!-- For Download Links   -->
                     <div class="card-header" id="title-heading">
                      <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#download_links" aria-expanded="true" aria-controls="download_links">
                          Download Links
                        </button>
                      </h2>
                    </div>

                    <div id="download_links" class="collapse" aria-labelledby="download_links-heading" data-parent="#accordionExample">
                        <div class="card-body">
                          <div class="row" >
                            <div class="col-sm-4">
                              <div class="form-group ">
                              <label>Play Store</label>
                              <input type="url" class="form-control" placeholder="Play store Link" name="play_store"   value="{{isset($settings['play_store']) ? $settings['play_store'] : ''}}">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                              <label>App Store</label>
                              <input type="url" class="form-control" placeholder="App store Link" name="app_store"  value="{{isset($settings['app_store']) ? $settings['app_store'] : ''}}">
                              </div>
                            </div>
                          </div>

                          </div>
                      </div>
                      <!-- For Social Links   -->
                      <div class="card-header" id="title-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#social_links" aria-expanded="true" aria-controls="social_links">
                            Social Links
                          </button>
                        </h2>
                      </div>

                      <div id="social_links" class="collapse" aria-labelledby="social-links-heading" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="row" >
                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>FaceBook</label>
                                <input type="url" class="form-control" placeholder="facebook" name="facebook"   value="{{isset($settings['facebook']) ? $settings['facebook'] : ''}}">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>Linkedin</label>
                                <input type="url" class="form-control" placeholder="Linkedin" name="linkedin"  value="{{isset($settings['linkedin']) ? $settings['linkedin'] : ''}}">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>Pinterest</label>
                                <input type="url" class="form-control" placeholder="pinterest" name="pinterest"  value="{{isset($settings['pinterest']) ? $settings['pinterest'] : ''}}">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>Twitter</label>
                                <input type="url" class="form-control" placeholder="twitter" name="twitter"  value="{{isset($settings['twitter']) ? $settings['twitter'] : ''}}">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group ">
                                <label>Youtube</label>
                                <input type="url" class="form-control" placeholder="youtube" name="youtube"  value="{{isset($settings['youtube']) ? $settings['youtube'] : ''}}">
                                </div>
                              </div>
                            </div>

                            </div>
                        </div>

                      </div>


                      <!-- For Social Links   -->
                      <div class="card-header" id="title-heading">
                        <h2 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#monthly-subscription" aria-expanded="true" aria-controls="monthly-subscription">
                            Subscription Period
                          </button>
                        </h2>
                      </div>

                      <div id="monthly-subscription" class="collapse" aria-labelledby="social-links-heading" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="row" >
                              <!-- <div class="col-sm-4">
                                <div class="form-group ">
                                  <label>Monthly Fee</label>
                                  <input type="number" class="form-control" placeholder="Monthly Fee" name="monthly_fee" value="{{isset($settings['monthly_fee']) ? $settings['monthly_fee'] : 0}}">
                                </div>
                              </div> -->
                              <div class="col-sm-6">
                                <div class="form-group ">
                                  <label>Fall Back Role</label>
                                  <select name="fall_back_role_id" id="" class="form-control">
                                      <option value="">Select Role</option>
                                      @foreach($roles as $role)
                                        <option value="{{$role->id}}" {{ (isset($settings['fall_back_role_id']) && $settings['fall_back_role_id'] == $role->id) ? 'selected':'' }} >{{$role->name_english}}</option>
                                      @endforeach
                                  </select>
                                </div>
                              </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Deferred Days</label>
                                        <input type="number" class="form-control" name="deferred_days" value="{{ isset($settings['deferred_days']) ? $settings['deferred_days'] : '' }}" required>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      </div>
                    </div> <!-- end of accordian -->
                          <div class="d-flex justify-content-end mb-3 mr-3">
                              <a href="{{ URL('admin/dashboard') }}" class="btn btn-info mr-3"> Cancel </a>
                              <button type="submit" class="btn btn-primary float-right"> Update Settings </button>
                          </div>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- Main content -->
  </div>
@endsection

@push('footer-scripts')
  <!-- jQuery -->
  <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- jquery-validation -->
  <script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
  <script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>
  <!-- InputMask -->
  <script src="{{asset('assets/admin/moment/moment.min.js')}}"></script>
  <script src="{{asset('assets/admin/inputmask/jquery.inputmask.min.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
      $('[data-mask]').inputmask();
      bsCustomFileInput.init();
      $('#user-form').validate({
        rules:
        {
          title: {
            required: true,
          },
          email: {
            required: true,
          },
          phone:{
            required: true,
          }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
  </script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
    Filevalidation = () => {
        const fi = document.getElementById('videoinpt');
        // Check if any file is selected.
        if (fi.files.length > 0) {
                const fsize = fi.files.item(0).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file >= 8192) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a file less than 8mb',
                    })
                    fi.value='';
                } else {
                    document.getElementById('size').innerHTML = '<b>'
                    + file + '</b> KB';
                }
        }
    }
</script>
@endpush
