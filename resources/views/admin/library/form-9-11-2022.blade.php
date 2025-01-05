


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

   {{-- sweat alert  --}}
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
		        <h1 class="m-0 text-capitalize">Create {{ $libraryType->title_english}} </h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/library') }}">library</a></li>
		          <li class="breadcrumb-item active">Create {{ $libraryType->title_english}}</li>
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
			                <h3 class="card-title">Library Form</h3>
			              </div>
			              <!-- /.card-header -->
						  <form id="thumbnail_form">
							  <input type="file" id="update_thumbnail" name="file" value="" class="d-none"  accept="image/*" >
						  </form>
			              <div class="card-body">
			                <form id="events-form" action="{{ URL('admin/library') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}
			                	<input type="hidden" name="action" value="{{$action}}">
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
								<input type="hidden" id="thumb_nail" value="{{!empty($libraryType->img_thumb_nail)?asset($libraryType->img_thumb_nail):asset('images/dummy-images/dummy.PNG')}}">
								 <input type="hidden" id="update_thumbid"  value="">
							<div class="accordion" id="accordionExample">

								<div class="card">

								<div id="library" class="collapse d-none" aria-labelledby="library-heading" data-parent="#accordionExample">
									<div class="card-body">

											<div class="row" >
												<div class="col-sm-4">
													<!-- text input -->
													<div class="form-group">
														<label>library Type</label>
														<select class="form-control" name="type_id" id="library_type" required>
															<option value="{{$libraryType->id}}" selected>{{$libraryType->title_english}}</option>

														</select>

													</div>
												</div>
											</div>
									</div>
								</div>

								<!-- For Content -->
								<div class="card-header" id="content-heading">
									<h2 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#message" aria-expanded="true" aria-controls="message">
											Content
										</button>
									</h2>
								</div>

								<div id="message" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
									<div class="card-body">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group ">
														<label class="col-form-label">Content English</label>
                                        				<textarea class="summernote" name="content_english" required>{{$libraryType->content_english}}</textarea>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group ">
														<label class="col-form-label">Content Urdu</label>
                                        				<textarea class="summernote" name="content_urdu" required>{{$libraryType->content_urdu}}</textarea>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group ">
														<label class="col-form-label">Content Arabic</label>
                                        				<textarea class="summernote" name="content_arabic" required>{{$libraryType->content_arabic}}</textarea>
													</div>
												</div>
											</div>
										</div>
									</div>


						<!-- For multipule images  resumable-->
							<div class="card-header" id="library-heading">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#multiFile" aria-expanded="true" aria-controls="multiFile">
											Files
									</button>
								</h2>
							</div>

							<div id="multiFile" class="collapse" aria-labelledby="library-heading" data-parent="#accordionExample">
								<div class="card-body">

											@if($libraryType->id ==2)
												<div class="row align-items-center">
													<div class="col-md-5">
														<div class="qt_wrap" style="" id="video_file">
															<label class="form-label">Please upload</label>
															<div class="fileupload">
																<div class="fileupload-inner">
																	<img src="{{asset('images/file-upload/upload-icon.svg')}}" alt="upload-icon">
																	<p>Upload Files</p>
																</div>
																<input  class="upload" type="file" multiple class="form-control"  id="browseFile">

															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="d-flex">OR</div>
													</div>
													<div class="col-md-5">
														<div class="qt_wrap d-flex flex-column" style="" id="link_embed">
															<label>Paste Link</label>
															<input type="text"  class="form-control"  id="link_upload" placeholder="Please enter valid youtube or goggle drive link" autocomplete="off">
															<button class="btn btn-small btn-primary mt-2" onclick="uploadLink()">Upload Link</button>
														</div>
													</div>
												</div>

												@else

												<div class="col-sm-4 mx-auto">


													<div class="qt_wrap mt">
														<div class="fileupload">
															<div class="fileupload-inner">
																<img src="{{asset('images/file-upload/upload-icon.svg')}}" alt="upload-icon">
																<p>Upload Files</p>
															</div>
															<input  class="upload" type="file" multiple class="form-control"  id="browseFile">

														</div>
													</div>
												</div>
											@endif



										<div class="col-md-12 loaderfile d-none" style="margin-left: 34% ">
											<img src="{{asset('assets/admin/loader/fileloder.gif')}}" style="display:block;"/>
										</div>
										<div  style="display: none" class="progress mt-3" style="height: 25px">
											<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
										</div>
										<div class="row  preview-area mt-5">
												@forelse($row as $key=>$val)
												@php
												 $html = '';
												@endphp

												@if($libraryType->id  == 1 )
												<div class="col-lg-2 col-md-3 col-sm-4 " id="dynamic_row_{{$loop->iteration}}">
													<input type="hidden" name="old_libraries_ids[]" value="{{$val->id}}">
													<input type="file" name="file[]" value="dummy" class="d-none">
														<div class="box_item" >
															<div class="image_overlay">
																<a href="{{asset($val->file)}}">
																<img src="{{asset($val->file)}}">
																</a>
																<div class="cross_icon"> <a href="javascript:void(0)" onclick="removeRow({{$loop->iteration}})">+</a> </div>
															</div>
															<div class="description-box">
																<div class="box-content">
																	<label class="form-label">File Title</label>
																	<div class="form-group mb-0">
																		<input type="text" class="form-control" placeholder="File Title" name="file_title[]" value="{{$val->file_title}}">
																	</div>
																</div>
																<div class="box-content">
																	<label class="form-label">Description</label>

																	<div class="form-group mb-0">
																		<textarea name="description[]" class="form-control" placeholder="Message" required>{{$val->description}}</textarea>
																	</div>
																</div>
															</div>
														</div>
												 </div>
												  @elseif($libraryType->id ==2)
												<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_{{$loop->iteration}}">
													<input type="hidden" name="old_libraries_ids[]" value="{{$val->id}}">
													<input type="file" name="file[]" value="dummy" class="d-none">
													<div class="box_item">
														<div class="image_overlay" >
															<img id="thumb_specific_{{$val->id}}" src="{{empty($val->img_thumb_nail)?asset('images/thumbnails/video.png'):asset($val->img_thumb_nail)}}" alt="upload-icon">
															<div class="cross_icon">
																<a href="javacript:void(0)" onclick="removeRow({{$loop->iteration}})">+</a>
															</div>
															<div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons">
																<div class="show-video-btn">
																	<a href="javacript:void(0)" class="btn btn-lg video" data-val="video" data-video="{{asset($val->file)}}" data-toggle="modal" data-target="#videoModal">Preview</a>
																</div>
																<div class="change_thumb_btn">
																	<a href="javacript:void(0)" class="btn btn-lg " onclick="changeThumbnail('{{$val->id}}')">Change Thumbnail</a>
																</div>
															</div>
														</div>
														<div class="description-box">
															<div class="box-content">
																<label class="form-label">File Title</label>
																<div class="form-group mb-0">
																	{{-- <textarea name="file_title[]" value="{{$val->file_title}}" class="form-control" placeholder="Enter File Title" required>{{$val->file_title}}</textarea> --}}
																	<input type="text" class="form-control" placeholder="File Title" name="file_title[]" value="{{$val->file_title}}">
																</div>
															</div>
															<div class="box-content">
																<label class="form-label">Description</label>

																<div class="form-group mb-0">
																	<textarea name="description[]" class="form-control" placeholder="Message" required>{{$val->description}}</textarea>
																</div>
															</div>
														</div>
												    </div>
												</div>
												  @elseif($libraryType->id ==3)


													<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_{{$loop->iteration}}">
														<input type="hidden" name="old_libraries_ids[]" value="{{$val->id}}">
														<input type="file" name="file[]" value="dummy" class="d-none">
														<div class="box_item">
															<div class="image_overlay">
																{{-- <img src="{{asset('images/file-upload/library-img-1.png')}}" alt="upload-icon"> --}}
																<img id="thumb_specific_{{$val->id}}" src="{{empty($val->img_thumb_nail)?asset('images/thumbnails/audio.png'):asset($val->img_thumb_nail)}}" alt="upload-icon">
																	{{-- <audio class="vid" controls  >
																	<source src="{{asset($val->file)}}" type="video/mp4">
																	</audio>  --}}

																	<div class="cross_icon">
																		<a href="javacript:void(0)" onclick="removeRow({{$loop->iteration}})">+</a>
																	</div>
																	<div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons">
																		<div class="show-video-btn">
																			<a href="javacript:void(0)" class="btn btn-lg video" data-val="audio" data-video="{{asset($val->file)}}" data-toggle="modal" data-target="#videoModal">Preview</a>
																		</div>
																		<div class="change_thumb_btn">
																			<a href="javacript:void(0)"  class="btn btn-lg "   onclick="changeThumbnail('{{$val->id}}')">Change Thumbnail</a>
																		</div>
																	</div>
															</div>
															<div class="description-box">
																<div class="box-content">
																	<label class="form-label">File Title</label>
																	<div class="form-group mb-0">
																		{{-- <textarea name="file_title[]" value="{{$val->file_title}}" class="form-control" placeholder="Enter File Title" required>{{$val->file_title}}</textarea> --}}
																		<input type="text" class="form-control" placeholder="File Title" name="file_title[]" value="{{$val->file_title}}">
																	</div>
																</div>
																<div class="box-content">
																	<label class="form-label">Description</label>

																	<div class="form-group mb-0">
																		<textarea name="description[]" class="form-control" placeholder="Message" required>{{$val->description}}</textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>

												  @elseif($libraryType->id ==4)
													<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_{{$loop->iteration}}">
														<input type="hidden" name="old_libraries_ids[]" value="{{$val->id}}">
														<input type="file" name="file[]" value="dummy" class="d-none">
														<div class="box_item">
															<div class="image_overlay">
																	<a href="{{asset($val->file)}}"  target="_blank"  class="media">


																	<img id="thumb_specific_{{$val->id}}" src="{{empty($val->img_thumb_nail)?asset('images/thumbnails/books.png'):asset($val->img_thumb_nail)}}" alt="upload-icon">

																	</a>
																	<div class="cross_icon">
																	<a href="javacript:void(0)" onclick="removeRow({{$loop->iteration}})">+</a>
																	</div>
																	<div class="change_thumb_btn">
																		<a href="javacript:void(0)"  class="btn btn-lg "   onclick="changeThumbnail('{{$val->id}}')">Change Thumbnail</a>
																	</div>
															</div>
															<div class="description-box">
																<div class="box-content">
																	<label class="form-label">File Title</label>
																	<div class="form-group mb-0">
																		{{-- <textarea name="file_title[]" value="{{$val->file_title}}" class="form-control" placeholder="Enter File Title" required>{{$val->file_title}}</textarea> --}}
																		<input type="text" class="form-control" placeholder="File Title" name="file_title[]" value="{{$val->file_title}}">
																	</div>
																</div>
																<div class="box-content">
																	<label class="form-label">Description</label>

																	<div class="form-group mb-0">
																		<textarea name="description[]" class="form-control" placeholder="Message" required>{{$val->description}}</textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												  @elseif( $libraryType->id ==5)
													<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_{{$loop->iteration}}">
														<input type="hidden" name="old_libraries_ids[]" value="{{$val->id}}">
														<input type="file" name="file[]" value="dummy" class="d-none">
														<div class="box_item">
															<div class="image_overlay">
																	<a href="{{asset($val->file)}}"   class="media">
																		<img id="thumb_specific_{{$val->id}}" src="{{empty($val->img_thumb_nail)?asset('images/thumbnails/documents.png'):asset($val->img_thumb_nail)}}" alt="upload-icon">
																	</a>
																	<div class="cross_icon">
																	<a href="javacript:void(0)" onclick="removeRow({{$loop->iteration}})">+</a>
																	</div>
																	<div class="change_thumb_btn">
																		<a href="javacript:void(0)"   class="btn btn-lg "   onclick="changeThumbnail('{{$val->id}}')">Change Thumbnail</a>
																	</div>
															</div>
															<div class="description-box">
																<div class="box-content">
																	<label class="form-label">File Title</label>
																	<div class="form-group mb-0">
																		{{-- <textarea name="file_title[]" value="{{$val->file_title}}" class="form-control" placeholder="Enter File Title" required>{{$val->file_title}}</textarea> --}}
																		<input type="text" class="form-control" placeholder="File Title" name="file_title[]" value="{{$val->file_title}}">
																	</div>
																</div>
																<div class="box-content">
																	<label class="form-label">Description</label>

																	<div class="form-group mb-0">
																		<textarea name="description[]" class="form-control" placeholder="Message" required>{{$val->description}}</textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												   @else

												  @endif

													 @php
													 	$counter_row = $loop->iteration +1;

													 @endphp
													@empty
													@php
														$counter_row =1;
													@endphp

												@endforelse
											</div>
												<input type="hidden" value='{{$counter_row }}' id="counter_row">
										</div>
								</div>
							</div>
								<!-- for Gernal -->
								<div class="card-header" id="short-heading">
									<h2 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#general" aria-expanded="true" aria-controls="general">
											General
										</button>
									</h2>
								</div>
								<div id="general" class="collapse" aria-labelledby="short-heading" data-parent="#accordionExample">
									<div class="card-body">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Status</label>
											<div class="col-sm-6">
												<div class="icheck-primary d-inline">
													Active
													<input type="radio" name="status" id="active-radio-status" value="1"  {{ ($libraryType->status==1) ? 'checked' : '' }}>
													<label for="active-radio-status">
													</label>
												</div>
												<div class="icheck-primary d-inline">
													In-Active
													<input type="radio" name="status" id="in-active-radio-status" value="0" {{ ($libraryType->status==0) ? 'checked' : '' }}>
													<label for="in-active-radio-status">
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>


							</div>
							<!-- end accordian -->
			                	<div class="card-body">
									<div class="form-group text-right">
										<div class="col-sm-12">
											<a href="{{ URL('admin/dashboard') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a>
											<button type="submit" class="btn btn-primary float-right" id="btn_submit"> {{ ($action == 'add') ? 'Save' : 'Update' }} </button>
										</div>
									</div>
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
		<!-- Button trigger modal -->
		{{-- <button type="button" id="modal_btn" class="btn btn-primary d-none" data-toggle="modal" data-target="#exampleModal">
			Launch demo modal
		</button> --}}

		<!-- Modal -->
		{{-- <button class="btn btn-lg video" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal">Play Video</button> --}}

		<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						<div class="" id="video_1" style="display: none">
							{{-- <video controls width="100%" id="video_tag" src="">
							</video> --}}
							<iframe allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="100%" height="425" id="video_tag" src="">
							</iframe>
						</div>
						<div class="" id="audio_1" style="display: none">
								<audio controls width="100%" id="audio_tag" src="">
								</audio>
						</div>

			  </div>
			</div>
		  </div>
		</div>
		<!-- Modal End-->
	</div>

@endsection

@push('footer-scripts')
   <!-- Larage File resumable -->
   		<script src="{{asset('assets/admin/dist/js/resumable.js')}}"></script>

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
  <!-- SummerNote -->
  <script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
	{{-- sweat alert  --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

	<script>
	  $(function () {

	  	  $('[data-mask]').inputmask();
		  bsCustomFileInput.init();


		  //___________For Validate Form______________//
		  $('#events-form').validate({
			ignore: false,
		    rules:
		    {
                content_english: {
                    required: true,
                },
                content_urdu: {
                    required: true,
                },
                content_arabic: {
                    required: true,
                },
                'description[]': { // some problem
                    required: true,
                },
		      status: {
		        required: true,
		      },

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
		    },
			invalidHandler: function(e,validator)
			{
				// loop through the errors:
				for (var i=0;i<validator.errorList.length;i++){
					// "uncollapse" section containing invalid input/s:
					$(validator.errorList[i].element).closest('.collapse').collapse('show');
				}
			},

		  });

          // Summernote
			$('.summernote').summernote({
				height: ($(window).height() - 300),
				callbacks: {
					onImageUpload: function(image) {
						uploadImage(image[0]);
					},
					onMediaDelete : function(target) {
						// alert(target[0].src)
						console.log(target) ;
						removeImage(target[0].src)
					}
				}
			})

			});

			function removeRow(row_id){
				$("#dynamic_row_"+row_id).remove();
			}

			//_________for show video and audio on the modal______________//
			$(document).on("click",".video",function() {

				let data_val=$(this).attr('data-val');
				// alert(data_val)
					videoSRC = $(this).attr("data-video"),
					videoSRCauto = videoSRC + "";
					if(data_val == 'audio'){
						$("#audio_1").show()
						$("#audio_tag").attr('src',videoSRCauto)
					}else{
						$("#video_1").show()
						$("#video_tag").attr('src',videoSRCauto)
					}
					$("#videoModal").show()
				$(theModal + ' button.close').click(function () {
				$(theModal + ' source').attr('src', videoSRC);
				});
			});

		//____________FOr Validation Of File Uploaded Type____________//
		var thum_val=$("#thumb_nail").val()
		if($("#library_type").val() == 1){
			var allowTypes = ['gif','png','jpg','jpeg','webp'];

		}else if($("#library_type").val() == 2)
		{
			var allowTypes = ['mp4','mov','wmv','avi','avchd','mkv'];

		}else if($("#library_type").val() == 3)
		{
			var allowTypes= ['m4a','flac','mp3','wav','wma','aac'];

		}else if($("#library_type").val() == 4 || $("#library_type").val() == 5){
			var allowTypes= ['docx','doc','pdf','xml','bmp','ppt','xls'];

		}
		var libId =$("#library_type").val();
		let browseFile = $('#browseFile');
		let resumable = new Resumable({
			target: "{{ url('admin/save-files-ajax')}}/"+libId,
			query:{_token:'{{ csrf_token() }}'} ,// CSRF token
			fileType: allowTypes,
			data: libId,
			chunkSize: 10*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
			headers: {
				'Accept' : 'application/json'
			},
			testChunks: false,
			throttleProgressCallbacks: 1,
		});

		resumable.assignBrowse(browseFile[0]);

		// trigger when file picked
		resumable.on('fileAdded', function (file) {

			showProgress();
			resumable.upload() // to actually start uploading.
		});

		resumable.on('fileProgress', function (file) { // trigger when file progress update
			updateProgress(Math.floor(file.progress() * 100));
		});

		resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete

			response = JSON.parse(response)
			let type=$('#library_type').val();
			if(type == 1){
				$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="'+ response.libId +'"><div class="box_item"><div class="image_overlay"><img src="'+ response.path +'"><div class="cross_icon"><a href="javascript:void(0)" onclick="removeRow('+response.libId+')">+</a></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message" required></textarea></div></div></div></div></div>');
			}else if(type == 2){
				$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="' + response.libId +'"><div class="box_item"><div class="image_overlay"><img id="thumb_specific_'+response.libId+'" src="{{asset('images/thumbnails/video.png')}}" alt="upload-icon"><div class="cross_icon"><a href="javascript:void(0)" onclick="removeRow('+response.libId+')">+</a></div><div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons"><div class="show-video-btn"><a href="javacript:void(0)" class="btn btn-lg video" data-val="video" data-video="'+ response.path +'" data-toggle="modal" data-target="#videoModal">Preview</a></div><div class="change_thumb_btn"><a href="javacript:void(0)" class="btn btn-lg" onclick="changeThumbnail('+response.libId+')">Change Thumbnail</a></div></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message" required></textarea></div></div></div></div></div>')
			}else if(type ==3){
				$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="' + response.libId +'"><div class="box_item"><div class="image_overlay"><img id="thumb_specific_'+ response.libId +'" src="{{asset('images/thumbnails/audio.png')}}" alt="upload-icon"><div class="cross_icon"><a href="javascript:void(0)" onclick="removeRow('+response.libId+')">+</a></div><div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons"><div class="show-video-btn"><a href="javacript:void(0)" class="btn btn-lg video" data-val="audio" data-video="'+ response.path +'" data-toggle="modal" data-target="#videoModal">Preview</a></div><div class="change_thumb_btn"><a href="javacript:void(0)" class="btn btn-lg" onclick="changeThumbnail('+ response.libId +')">Change Thumbnail</a></div></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message"></textarea></div></div></div></div></div>')
			}else if(type ==4 ){
				$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="' + response.libId +'"><div class="box_item"><div class="image_overlay"><a href="'+ response.path +'" download><img id="thumb_specific_' + response.libId +'" src="{{asset('images/thumbnails/books.png')}}" alt="upload-icon"></a><div class="cross_icon"><a href="javacript:void(0)" onclick="removeRow('+response.libId+')">+</a></div></div><div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons"><div class="change_thumb_btn"><a href="javacript:void(0)" class="btn btn-lg" onclick="changeThumbnail('+ response.libId +')">Change Thumbnail</a></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message"></textarea></div></div></div></div></div>')
			}else if(type==5){
				$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="' + response.libId +'"><div class="box_item"><div class="image_overlay"><a href="'+ response.path +'" download><img id="thumb_specific_' + response.libId +'" src="{{asset('images/thumbnails/documents.png')}}" alt="upload-icon"></a><div class="cross_icon"><a href="javacript:void(0)" onclick="removeRow('+response.libId+')">+</a></div></div><div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons"><div class="change_thumb_btn"><a href="javacript:void(0)" class="btn btn-lg" onclick="changeThumbnail('+ response.libId +')">Change Thumbnail</a></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message"></textarea></div></div></div></div></div>')

			}

		});

		// trigger when there is any error
		resumable.on('fileError', function (file, response) {
			// alert('file uploading error.');
            swal("Oops!", "file uploading error.", "error")
		});


		let progress = $('.progress');
		function showProgress() {
			progress.find('.progress-bar').css('width', '0%');
			progress.find('.progress-bar').html('0%');
			progress.find('.progress-bar').removeClass('bg-success');
			progress.show();
		}

		function updateProgress(value) {
			progress.find('.progress-bar').css('width', `${value}%`)
			progress.find('.progress-bar').html(`${value}%`)
		}

		function hideProgress() {
			progress.hide();
		}


		//___________________update thumbnail of video audio and documents_____________//
		function changeThumbnail(id){
			// alert(id);
			$("#update_thumbid").val(id)
			$("#update_thumbnail").click();
		}

		update_thumbnail.onchange = evt => {
			var id=$("#update_thumbid").val();
			const [file] = update_thumbnail.files
			if (file) {
				$("#thumb_specific_"+id).attr('src',URL.createObjectURL(file))
			}
				var formData = new FormData($('#thumbnail_form')[0]);
				var UrlThumb = '{{url("admin/update-thumb-img")}}/'+id;
				$.ajax({
				type: "POST",
				url: UrlThumb,
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'JSON',
				success: function (data) {

				},

			});
		}

		//___________file type for link and video file_____________//

		function fileType(_this){
			$('.qt_wrap').css('display','none');
			var option = $('option:selected', _this).attr('data-attr');
			$("#"+option).css("display",'block');
		}
		//___________upload file Link____________//
		function uploadLink(){
			event.preventDefault();
				var _videoUrl = $("#link_upload").val();
				var matches = _videoUrl.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
				if (!matches)
				{
					swal("Error!", "Video Link Is Not Valid", "error");

				}else{
					_videoUrl=_videoUrl.split('&list')[0];
					var _videoUrl=_videoUrl.replace('watch?v=','embed/');
					var libId =$("#library_type").val();
					$.ajax({
						type: "POST",
						url: "{{ url('admin/save-files-ajax')}}/"+libId,
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						data: {videUrl:_videoUrl},
						success: function (response) {
							var type =$("#library_type").val()
							swal("Done!", "Upload File Link Successfully.", "success");
							$('.preview-area').append('<div class="col-lg-2 col-md-3 col-sm-4" id="dynamic_row_'+response.libId+'"><input type="hidden" name="lib_id" value="'+type+'"> <input type="hidden" name="old_libraries_ids[]" value="' + response.libId +'"><div class="box_item"><div class="image_overlay"><img id="thumb_specific_'+response.libId+'" src="{{asset('images/thumbnails/video.png')}}" alt="upload-icon"><div class="cross_icon"><a href="javascript:void(0)" onclick="removeRow('+response.libId+')">+</a></div><div class="d-flex justify-content-center align-items-center flex-column library-hover-buttons"><div class="show-video-btn"><a href="javacript:void(0)" class="btn btn-lg video" data-val="video" data-video="'+ response.path +'" data-toggle="modal" data-target="#videoModal">show</a></div><div class="change_thumb_btn"><a href="javacript:void(0)" class="btn btn-lg" onclick="changeThumbnail('+response.libId+')">Change ThumbNail</a></div></div></div><div class="description-box"><div class="box-content"><label class="form-label">File Title</label><div class="form-group mb-0"><input class="form-control" placeholder="File Title" name="file_title[]"></div></div><div class="box-content"><label class="form-label">Description</label><div class="form-group mb-0"><textarea name="description[]" class="form-control" placeholder="Message" required></textarea></div></div></div></div></div>')
							$("#link_upload").val('');
						},
					});

				}
		}
	$("#videoModal").on('hide.bs.modal', function () {
        // alert("ok")
        $(this).find('iframe').attr('src', '')
        $(this).find('audio').attr('src', '')
    });
	</script>
@endpush
