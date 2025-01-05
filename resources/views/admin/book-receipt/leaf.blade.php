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
@endpush

@section('content')
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
		  <div class="container-fluid">
		    <div class="row mb-2">
		      <div class="col-sm-6">
		        <h1 class="m-0">Add Book Leafs</h1>
		      </div><!-- /.col -->
		      <div class="col-sm-6">
		        <ol class="breadcrumb float-sm-right">
		          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
		          <li class="breadcrumb-item"><a href="{{ URL('admin/book-receipts') }}">Book Receipt</a></li>
		          <li class="breadcrumb-item active">Add Book Leafs</li>
		        </ol>
		      </div><!-- /.col -->
		    </div><!-- /.row -->
		  </div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row table table-responsive">
                <input type="hidden" name="book_id" id="book_id" value="{{$bookId}}">
                    

              <div class="col-md-12 ">
                  <div class="d-flex align-items-center" style="justify-content: space-between">
                        <h3 class="card-title">Book Receipt Leafs</h3>
                        <div class="d-flex align-items-center search-box " style="display: none !important" >
                            <label class="mb-0 mr-2" for="">Search Leaf : </label>
                            <input type="text" name="search_leaf" id="search_leaf" onkeyup="getLeafSearch($(this))">
                        </div>
                  </div>
                  <div class="row card-body no-find  justify-content-center"></div>

                    <div class="row card-body card-primary dynamic_row" id="dynamic_row">    
                        
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
	<!-- SummerNote -->
	<script src="{{asset('assets/admin/summernote/summernote-bs4.min.js')}}"></script>
	<!-- Page specific script -->

    <script>

        $(function () {
            getBookLeafs('add','');

            setTimeout(() => {
                $(".search-box").css('display','flex')
            }, 2000);
        });
    
        function getBookLeafs(type = '',lastLeafNumber='') {
            var bookId = $("#book_id").val();
            $.ajax({
                type: 'get',
                url: '{{ URL("admin/book-receipts-leaf") }}',
                data: {
                    type: type,
                    bookId:bookId,
                    lastLeafNumber:lastLeafNumber,
                },
                dataType: 'JSON',
                success: function(response) {    
                    console.log(response);
                    if(response.data == 'completed'){
                        return true;
                    }else{
                        $(".dynamic_row").append(response.data.html)
                        getBookLeafs('',response.data.lastLeafNumber)
                        
                    }
                }
            });
        }
        function updateReceiptLeaf(_this){
            // event.preventDefault();
            // var formData = new FormData($('#contact-formm')[0]);
            var leafId = _this.attr('data-id');
            var formData = new FormData($('#leaf_form_'+leafId)[0]);

            $('#leaf_form_'+leafId).validate({
				ignore: false,
				rules: {},
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

                submitHandler: function(form) {
                    $.ajax({
                        url: "{{ URL('admin/book-leaf-update') }}",
                        headers: { 'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content') },
                        type: "POST",      
                        data: formData,   
                        processData: false,
                        contentType: false,       
                        success: function(data) {
                            if(data){
                                swal("Update Data!", "Your data is saved successfully!", "success")
                            }else{
                                swal("Invalid Data!", "You data is not save due to some issue!", "error")
                            }
                        }
                    });
                 
                },

			});

   
        }

  
        function previewFile(_this){
            var imdId=_this.attr('id').split("_")[1];
            // alert(_this.attr('id'))
            // alert(imdId)
            var file = $("#imgInp_"+imdId).get(0).files[0];
    
            if(file){
                var reader = new FileReader();
    
                reader.onload = function(){
                    $("#img_prev_"+imdId).attr("src", reader.result);
                    // $("#img_prev_link_"+imdId).attr("href", $("#img_prev_"+imdId).attr("src"));
                }
                
                reader.readAsDataURL(file);
            }
        }
        function getLeafSearch(_this){
            var data= _this.val();
            $('.no-find').html('');
            $('.dynamic_cards').removeClass('leafExist')
            $('.dynamic_cards').removeClass('d-none')
            if(data != ''){
                $('.dynamic_cards').addClass('d-none')
                $('.leaf_class_'+data).removeClass('d-none')
                $('.leaf_class_'+data).addClass('leafExist')
                if(!$('#dynamic_row').find('.dynamic_cards ').hasClass('leafExist')){
                    $('.no-find').html("<p>No record found</p>")
                }
                
            }
            
        }
    </script>
@endpush
