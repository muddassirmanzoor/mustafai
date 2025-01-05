		<!-- Main content -->
        <div class="mb-5">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
		          	<div class="col-md-12">
			            <!-- general form elements -->
			            <div class="card card-primary">
			              <div class="card-header">
			                <h3 class="card-title">Directory Details</h3>
			              </div>

			              <!-- /.card-header -->
			              <div class="card-body">
			                <form id="detail-form" class="form-horizontal label-left" action="{{ URL('admin/dir-details') }}" enctype="multipart/form-data" method="POST">
			                	{!! csrf_field() !!}

								<input type="hidden" name="action" value="{{$action}}">
								<input type="hidden" id="files" name="files" >
								{{-- <input type="hidden" id="parent_id" name="parent_id" >
								<input type="hidden" id="type_id" name="type_id" > --}}
			                	<input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">

								<div class="accordion" id="accordionExample">
									<div class="card">
										{{-- For Title  --}}

										<!-- For Title -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#title" aria-expanded="true" aria-controls="title">
													Title
												</button>
											</h2>
										</div>

										<div id="title" class="collapse" aria-labelledby="title-heading" data-parent="#accordionExample">
											<div class="card-body">
													<div class="row" >
                                                        @foreach (activeLangs() as $keyy=>$vall)
															<div class="col-sm-4">
																<div class="form-group ">
																<label>Title ({{$vall->name_english}}) <span class="text-red">*</span></label>
																<input type="text" class="form-control" placeholder="Title {{ ucfirst($vall->key)}}" name="title_{{$vall->key}}" required value="{{ $row->{'title_'.$vall->key} }}">
																</div>
															</div>
														@endforeach

													</div>
											</div>
										</div>
										<!-- For Image -->
										<!-- For message title general  -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
													Thumbnail
												</button>
											</h2>
										</div>

										<div id="image" class="collapse" aria-labelledby="message-title-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="form-group ">
													<label class="col-sm-2 col-form-label">Image <span class="text-red">*</span></label>
														{{-- <input type="File" class="dropify form-control" data-max-width="215" data-max-height="215"  data-show-remove="false"   data-allowed-file-extensions="jpg png jpeg webp" accept="image/*"  id="imageinpt" name="img_thumb_nail" value="{{ $row->img_thumb_nail }}" @if(empty($row->img_thumb_nail))  @else data-default-file="{{asset($row->img_thumb_nail)}}" @endif> --}}
														<input type="File" class="dropify form-control"   data-show-remove="false"   data-allowed-file-extensions="jpg png jpeg webp" accept="image/*"  id="imageinpt" name="img_thumb_nail" value="{{ $row->img_thumb_nail }}" @if(empty($row->img_thumb_nail))  @else data-default-file="{{getS3File($row->img_thumb_nail)}}" @endif>
														<small class="d-block text-muted  mt-1">Please add image file as per mentioned restrictions <span class="text-danger"> 214 x 214 </span> pixels</small>
												</div>

											</div>
										</div>
										<!-- For Message -->
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#message" aria-expanded="true" aria-controls="message">
													Content
												</button>
											</h2>
										</div>

										<div id="message" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="row">
													@foreach (activeLangs() as $keyy=>$vall)
														<div class="col-sm-4">
															<div class="form-group ">
																<label class="form-label">Content ({{$vall->name_english}}) <span class="text-red">*</span></label>
																	<textarea id="summernote_{{$vall->id}}"  class="summernote " name="content_{{strtolower($vall->key)}}" >{{ $row->{'content_'.strtolower($vall->key)} }}</textarea>
															</div>
														</div>
													@endforeach
													{{-- <div class="col-sm-4">
														<div class="form-group">
															<label class="form-label">Content (Urdu) <span class="text-red">*</span></label>
															<textarea id="summernote"  class="summernote " name="content_urdu" required>{{$row->content_urdu}}</textarea>

														</div>
													</div>

													<div class="col-sm-4">
														<div class="form-group ">
															<label class="form-label">Content (Arabic) <span class="text-red">*</span></label>
															<textarea id="summernote"  class="summernote " name="content_arabic" required>{{$row->content_arabic}}</textarea>

														</div>
													</div> --}}
												</div>

											</div>
										</div>
										<!-- For preview Files -->
										{{-- {{$row->getTable()}} --}}
									{{-- @if($row->getTable() == 'library_album_details')
										<div class="card-header" id="title-heading">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#previewFile" aria-expanded="true" aria-controls="message">
													Preview File
												</button>
											</h2>
										</div>

										<div id="previewFile" class="collapse" aria-labelledby="message-heading" data-parent="#accordionExample">
											<div class="card-body">
												<div class="row">

														<div class="col-md-12 ">
															@php
															    $image_extensions = ["JPG","JPEG","PNG","JFIF","SVG","WEBP","GIF"];
                                                    			$video_extensions = ["MP4","MOV","WMV","AVI","AVCHD","MKV"];
                                                    			$audio_extention = ["M4A","FLAC","MP3","WAV","WMA","AAC"];
                                                    			$book_extention = ["DOCX","DOC","PDF","XML","BMP","PPT","XLS"];
                                                    			$document_extention = ["DOCX","DOC","PDF","XML","BMP","PPT","XLS"];
																$fileExtention=\File::extension($row->file);
															@endphp
																@if (in_array(strtoupper($fileExtention) , $image_extensions))
																<img src="{{asset($row->file)}}"  width="100" height="100vh">
																@endif
																@if (in_array(strtoupper($fileExtention) ,$video_extensions))
																	<iframe width="306" height="200" src="{{asset($row->file)}}" frameborder="0" allowfullscreen></iframe>
																@endif
																@if (in_array(strtoupper($fileExtention) ,$audio_extention))
																	<iframe width="306" height="200" src="{{asset($row->file)}}" frameborder="0" allowfullscreen></iframe>
																@endif
																@if (in_array(strtoupper($fileExtention) ,$book_extention))
																	<a href="{{asset($row->file)}}" target="_blank">click Here To Get File</a>
																@endif
																@if (in_array(strtoupper($fileExtention) ,$document_extention))
																	<a href="{{asset($row->file)}}" target="_blank">click Here To Get File</a>
																@endif

														</div>


												</div>

											</div>
										</div>
									@endif --}}
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
															<input type="radio" name="status" id="active-radio-status" value="1" {{ ($row->status==1) ? 'checked' : '' }}>
															<label for="active-radio-status">
															</label>
														</div>
														<div class="icheck-primary d-inline">
															In-Active
															<input type="radio" name="status" id="in-active-radio-status" value="0" {{ ($row->status==0) ? 'checked' : '' }}>
															<label for="in-active-radio-status">
															</label>
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
				                  			{{-- <a href="{{ URL('admin/ceomessage') }}" class="btn btn-info" style="margin-right:05px;"> Cancel </a> --}}
				                  			<button type="submit" id="save-details" class="btn btn-primary float-right"> {{ ($action == 'add') ? 'Save' : 'Update' }} </button>
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
