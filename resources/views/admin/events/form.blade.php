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
   <!-- SummerNote -->
   <link rel="stylesheet" href="{{asset('assets/admin/summernote/summernote-bs4.min.css')}}">
   <style>
       .imageThumb {
           max-height: 75px;
           border: 2px solid;
           padding: 1px;
           cursor: pointer;
       }
   </style>
@endpush
<?php
$mindate = date('Y-m-d');
$mintime = date('h:i');
$min = $mindate . 'T' . $mintime;
$maxdate = date('Y-m-d', strtotime('+10 Days'));
$maxtime = date('h:i');
$max = $maxdate . 'T' . $maxtime;
?>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Event</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/events') }}">Events</a></li>
                            <li class="breadcrumb-item active">Create Event</li>
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
                                <h3 class="card-title">Event Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="page-form" class="form-horizontal label-left" action="{{ URL('admin/events') }}"
                                    enctype="multipart/form-data" method="POST">
                                    {!! csrf_field() !!}

                                    <input type="hidden" name="action" value="{{ $action }}">
                                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <!-- For Title -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#title" aria-expanded="true" aria-controls="title">
                                                        Title
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="title" class="collapse" aria-labelledby="title-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label>Title {{ $lang->name_english }} <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Title {{ $lang->name_english }}" name="title_{{ $lang->key }}"
                                                                    required value="{{ $row->{'title_'.$lang->key} }}">
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Image -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#image" aria-expanded="true" aria-controls="image">
                                                        Image
                                                    </button>
                                                </h2>
                                            </div>

										<div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Image <span class="text-red">*</span></label>
                                                    <div class="col-sm-6">

                                                        @if($action == 'edit')
                                                            @foreach($files as $image)
                                                                <div id="inputFormRow">
                                                                    <input type="hidden" value="{{ $image->id }}" name="old_files[]">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-append" style="margin-bottom: 2%">
                                                                            <button id="removeRow" type="button" style="height:40px" class="btn btn-danger">Remove</button>
                                                                            <div style="margin-left:50px">
                                                                                <div>
                                                                                    <img class="imageThumb" src="{{ getS3File($image->image) }}"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <div id="newRow" class="input_image_scroller"></div>
                                                        <button id="addRow" type="button" class="btn btn-primary add_event_image_button">Add Event Image</button>
                                                        <small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 500 x 500 </span> pixels</small>
                                                    </div>
<!--													<div class="col-sm-6">
                                					<input type="file" class="form-control" name="image" {{ !empty($row->image) ? '' : 'required' }} id="imageinpt" value="{{ $row->image }}">
													</div>
													<div class="col-sm-2">
														@if($row->image)
														<a href="{{ getS3File($row->image) }}" target="_blank">
															<img src="{{ getS3File($row->image) }}" alt="" id="sample_image" width="100" height="100">
														</a>
                            							<button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
														@else
															<a href="javascrit:void(0)" target="_blank">
															 	<img id="sample_image" src="{{ getS3File('images/dummy-images/dummy.png') }}" alt="your image" width="60" height="60" />
															</a>
                              							<button class="btn btn-sm btn-danger d-block mt-2" id="clear_image" > clear Image</button>
													  @endif

													</div>-->
												</div>

                                                </div>
                                            </div>
                                            <!-- For Location -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#location" aria-expanded="true"
                                                        aria-controls="location">
                                                        Location
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="location" class="collapse" aria-labelledby="location"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label>Location {{ $lang->name_english }} <span
                                                                        class="text-red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Location {{ $lang->name_english }}" name="location_{{ $lang->key }}"
                                                                    required value="{{ $row->{'location_'.$lang->key} }}">
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- For Date & time -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#date_time" aria-expanded="true"
                                                        aria-controls="date_time">
                                                        Date & Time
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="date_time" class="collapse" aria-labelledby="location"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label>Start Date Time <span
                                                                        class="text-red">*</span></label>
                                                                <input type="datetime-local" class="form-control"
                                                                    name="start_date_time"
                                                                    id= "start_date_time"
                                                                    required value="{{ $row->start_date_time }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label>End Date Time <span
                                                                        class="text-red">*</span></label>
                                                                <input type="datetime-local" class="form-control"
                                                                    name="end_date_time"
                                                                    id="end_date_time"
                                                                    required value="{{ $row->end_date_time }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- For Content -->
                                            <div class="card-header" id="title-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#message" aria-expanded="true"
                                                        aria-controls="message">
                                                        Content
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="message" class="collapse" aria-labelledby="message-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach (activeLangs() as $lang)
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="col-form-label">Content {{ $lang->name_english }} <span
                                                                        class="text-red">*</span></label><br>
                                                                <textarea class="summernotee" name="content_{{ $lang->key }}" required style="width: 307px; height: 150px;">{{ $row->{'content_'.$lang->key} }}</textarea>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- for sessions -->
                                            <div class="card-header" id="sessions-content">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                            data-target="#sessions" aria-expanded="true"
                                                            aria-controls="sessions">
                                                        Sessions
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="sessions" class="collapse" aria-labelledby="sessions"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div>
                                                            @if($action == 'edit')
                                                                @foreach($sessions as $session)
                                                                    <div id="inputSessionFormRow">
                                                                        <div class="row">
                                                                            @foreach (activeLangs() as $lang)
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Session Title ({{ $lang->name_english }}) </label>
                                                                                    <input value="{{ $session->{'session_'.$lang->key} }}" type="text" class="form-control" placeholder="Session {{ $lang->key }} title" name="session_{{ $lang->key }}[]">
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <div class="row">
                                                                              <!-- description -->
                                                                              @foreach (activeLangs() as $lang)
                                                                              <div class="col-sm-6">
                                                                                  <div class="form-group">
                                                                                      <label>Description ({{ $lang->name_english }})</label>
                                                                                      <textarea class="form-control" name="description_{{ $lang->key }}[]">{{ $session->{'description_'.$lang->key} }}</textarea>
                                                                                  </div>
                                                                              </div>
                                                                              @endforeach
                                                                        </div>
                                                                        <div class="row">
                                                                            <!-- start and end time -->
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Session Start Date and Time</label>
                                                                                    <input type="datetime-local" class="form-control" name="session_start_date_time[]" value="{{ $session->session_start_date_time }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Session End Date and Time</label>
                                                                                    <input type="datetime-local" class="form-control" name="session_end_date_time[]" value="{{ $session->session_end_date_time }}">
                                                                                </div>
                                                                            </div>


                                                                            <!--remove row-->
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group">
                                                                                    <button id="" type="button" style="height:40px" class="btn btn-danger" onclick="removeSessionRow($(this))">Remove</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div id="sessionNewRow"></div>
                                                            <button id="" type="button" class="btn btn-primary add_event_image_button" onclick="appendSessionDiv()">Add Session</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- for Gernal -->
                                            <div class="card-header" id="short-heading">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#general" aria-expanded="true"
                                                        aria-controls="general">
                                                        General
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="general" class="collapse" aria-labelledby="short-heading"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Status</label>
                                                        <div class="col-sm-6">
                                                            <div class="icheck-primary d-inline">
                                                                Active
                                                                <input type="radio" name="status"
                                                                    id="active-radio-status" value="1"
                                                                    {{ $row->status == 1 ? 'checked' : '' }}>
                                                                <label for="active-radio-status">
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                In-Active
                                                                <input type="radio" name="status"
                                                                    id="in-active-radio-status" value="0"
                                                                    {{ $row->status == 0 ? 'checked' : '' }}>
                                                                <label for="in-active-radio-status">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
                                    <div class="col-sm-12">
                                        <a href="{{ URL('admin/events') }}" class="btn btn-info"
                                            style="margin-right:05px;"> Cancel </a>
                                        <button type="submit" class="btn btn-primary float-right">
                                            {{ $action == 'add' ? 'Save' : 'Update' }} </button>
                                    </div>
                                </div>
                            </div>

                            </form>
                        </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
    </div>
    <div id="inputSessionFormNewRow" style="display: none">
        <div class="row">
            @foreach (activeLangs() as $lang)
                <div class="col-sm-6">
                    <div class="form-group">
                    <label>Session Title ({{ $lang->name_english }} )</label>
                    <input type="text" class="form-control" placeholder="Session {{ $lang->name_english }} title" name="session_{{ $lang->key }}[]">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <!-- description -->
            @foreach (activeLangs() as $lang)
                <div class="col-sm-6">
                    <div class="form-group">
                    <label>Description ({{ $lang->name_english }})</label>
                    <textarea class="form-control" name="description_{{ $lang->key }}[]"></textarea>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <!-- start and end time -->
            <div class="col-sm-6">
                 <div class="form-group">
                   <label>Session Start Date and Time</label>
                   <input type="datetime-local" class="form-control" name="session_start_date_time[]">
                </div>
            </div>
            <div class="col-sm-6">
                 <div class="form-group">
                   <label>Session End Date and Time</label>
                   <input type="datetime-local" class="form-control" name="session_end_date_time[]">
                </div>
            </div>

            <!--remove row-->
            <div class="col-sm-12">
                 <div class="form-group">
                   <button id="" type="button" style="height:40px" class="btn btn-danger" onclick="removeSessionRow($(this))">Remove</button>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- Main content -->
    </div>
@endsection

@push('footer-scripts')
    <!-- jQuery -->
    <script src="{{ asset('assets/admin/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/admin/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('assets/admin/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>
    <!-- Page specific script -->
    <!-- SummerNote -->
    <script src="{{ asset('assets/admin/summernote/summernote-bs4.min.js') }}"></script>

  <script>
	  $(function () {
	  	$('[data-mask]').inputmask();
		  bsCustomFileInput.init();
		  $('#page-form').validate({
				ignore: false,
			    rules:
			    {},
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
			    },
				invalidHandler: function(e,validator)
				{
					// loop through the errors:
					for (var i=0;i<validator.errorList.length;i++){
						// "uncollapse" section containing invalid input/s:
						$(validator.errorList[i].element).closest('.collapse').collapse('show');
					}
				}
			});
      // Summernote
      $('.summernote').summernote({
          height: ($(window).height() - 300),
          callbacks: {
              onImageUpload: function(image) {
                uploadEditoImage(image[0]);
              },
              onMediaDelete : function(target) {
                  deleteEditoImage(target[0].src)
              }
          }
      })
		});
    imageinpt.onchange = evt => {
			const [file] = imageinpt.files
			if (file) {
				sample_image.src = URL.createObjectURL(file)
				$('#sample_image').parent().attr('href',URL.createObjectURL(file));
			}
		}
    $('#clear_image').click(function(){
      event.preventDefault();
      $('#imageinpt').val('');
      $('#sample_image').parent().attr('href','https://www.freeiconspng.com/uploads/no-image-icon-6.png');
      $('#sample_image').attr('src','https://www.freeiconspng.com/uploads/no-image-icon-6.png');
      $('#imageinpt').attr('required',true);
    });
	</script>

  <script>
      var rowIncrese = 0
      $("#addRow").click(function () {
          rowIncrese+=1
          var html = '';
          html += '<div id="inputFormRow">';
          html += '<div class="input-group" style="margin-bottom: 2%">';
          html += '<input id="files" type="file" name="images[]" data-file-num="'+rowIncrese+'" data-preview-id="sample_image_'+rowIncrese+'" class="form-control m-input imageinpt dynamic_class_'+rowIncrese+'" accept="image/*">';
          html += '<div class="input-group-append">';
          html += '<button id="removeRow" type="button" style="height:40px" class="btn btn-danger dynamic_files_'+rowIncrese+'">Remove</button>';
          html += '</div>';
          html += '</div>';

          $('#newRow').append(html);
      });

      // remove row
      $(document).on('click', '#removeRow', function () {
          $(this).closest('#inputFormRow').remove();
      });

      $("body").on("change",'input:file', function (e) {
         let inputFileNumber = $(this).attr('data-file-num')

          var files = e.target.files,
              filesLength = files.length;
          for (var i = 0; i < filesLength; i++) {
              var f = files[i]
              var fileReader = new FileReader();
              fileReader.onload = (function (e) {
                  var file = e.target;
                  var previewerId = `previewer_${inputFileNumber}`;
                    if ($("#" + previewerId).length) {
                    $("#" + previewerId).remove();
                    }

                  $(`<div class="bar" style="margin-left:50px" id="${previewerId}">
                        <div><img class="imageThumb" id="sample_image_${inputFileNumber}" src="${e.target.result}" title="${f.name}"/></div>
                        `).insertAfter($(`.dynamic_files_${inputFileNumber}`));

              });
              fileReader.readAsDataURL(f);
          }
      });

      /* add/remove event session */

      function appendSessionDiv() {
        var html=$('#inputSessionFormNewRow').html();
        $('#sessionNewRow').append(html);
    }
    function removeSessionRow(_this) {
        $(_this).parent().parent().parent().remove();
    }
    /* Change End Date According to Start Date */
    $('#start_date_time').change(function(event){
        var changed_date =  $(this).val();
        var originalDate = new Date(changed_date);
        // var newDate = new Date(originalDate.getTime() + (24 * 60 * 60 * 1000));
        var minDate = originalDate.toISOString().slice(0, 16);
        $('#end_date_time').attr('min', minDate);
        var startDate =$(this).val();
        var endDate = $("#end_date_time").val();
        if(Date.parse(startDate) > Date.parse(endDate)){
            $("#end_date_time").val(minDate);
        }
    });
    /* Set Default Start and End Date according to current date */
    $( document ).ready(function() {
        var dtToday = new Date().toISOString().slice(0, 16);
        $('#start_date_time').attr('min', dtToday);
        $('#end_date_time').attr('min', dtToday);
    });
  </script>

@endpush
