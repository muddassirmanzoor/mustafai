<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('assets/home/images/favicon.png')}}" />
  <title>Mustafai Dashboard</title>

  @stack('header-scripts')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/custom.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
   <!-- Flipbook StyleSheet -->
   <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

   <!-- Icons Stylesheet -->
   <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />
  <style rel="stylesheet" type="text/css" >
      .nav-item a p{
        text-transform: capitalize;
      }
      .modal.fade .modal-dialog{transform:none !important;}
      .adminloader{
        height: 100vh !important;
      }
      .adminloader .animation__shake{
        display: block;
      }
      div#pages-datatable_length {
    padding-top: 24px;
    }
    div#pages-datatable_filter {
    margin-top: -41px;
    }

  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}" alt="Mustafai Dashboard">
    </div>

    @include('admin.sections.header')
    @include('admin.sections.left-nav')
    @yield('content')
    @include('admin.sections.footer')
    @include('admin.sections.aside')
  </div>

  @stack('footer-scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
  <script >
    @if(Session::has('message'))
    toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
        toastr.success("{!! session('message') !!}");
    @endif

    @if(Session::has('error'))
    toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
        toastr.error("{!! session('error') !!}");
    @endif

    @if(Session::has('info'))
    toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
        toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
        toastr.warning("{{ session('warning') }}");
    @endif

   function custom_select_option(){
    //  var custom_select_option=$('select').closest('label').html()
    $("select").prepend("<option value='' selected='selected'>-- Select Dropdown --</option>");
  }
  </script>
  <script>
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        if($(this).hasClass('fa-eye-slash')){
            $(this).parent('.div-custom').find('input').attr('type','text')
        }else{
            $(this).parent('.div-custom').find('input').attr('type','password')
        }
});
</script>
<script>

    function showConfirmAlert(_this,custom_message = '',confirmButtonText='') {
      event.preventDefault();
       var title=(custom_message == '')?'Do you want to delete?':custom_message;
       var confirmButtonText=(confirmButtonText == '')?'Delete?':confirmButtonText;
        // swal({
        //     title: 'Do you want to delete?',
        //     showCancelButton: true,
        //     type: 'warning',
        //     confirmButtonText: 'Delete',
        // }).then((result) => {
        //     /* Read more about isConfirmed, isDenied below */
        //     if (result.isConfirmed) {
        //       $(_this).closest("form").submit();
        //     }
        // })
          swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            closeOnConfirm: false
        }, function (isConfirm) {
            if (!isConfirm) return;
              $(_this).closest("form").submit();
            });
	}

  function readMoreString(_this){
		var moreString=_this.attr('data-moreString');
		var lessString=_this.attr('data-lessString');
		var dataType=_this.attr('data-type');
		if(dataType =='more'){
			var newString = `${moreString} <a href="javascript:void(0)" data-moreString="${moreString}" data-lessString="${lessString}" data-type="less"  onClick="readMoreString($(this))">Read Less</a>`;
			_this.parent().html(newString);

		}else{
			var newString = `${lessString} ... <a href="javascript:void(0)" data-moreString="${moreString}" data-lessString="${lessString}" data-type="more"  onClick="readMoreString($(this))">Read More</a>`;
			_this.parent().html(newString);
		}
	}

</script>
<script>
  //_____________This function is check all simple input image files demintion dynamically you should pass data-width,data-height and data-preview-id _________________________________________//
    $(document).on('change', '.imageinpt', function() {
        var width=$(this).attr('data-width');
        var height=$(this).attr('data-height');
        var index=$(this).attr('data-file-num');
        var preview_id=$(this).attr('data-preview-id');
        var file = this.files[0];
        var img = new Image();
        var reader = new FileReader();

        reader.onload = function(e) {
          img.src = reader.result;
            $(img).on('load', function() {
              if (img.width > width || img.height > height) {
                  $(this).val('');
                  swal("Oops!", `Max Image dimension should be ${width} x ${height}.`, "error")
                    setTimeout(function(){
                      $('.dynamic_class_'+index).val('');
                      $("#"+preview_id).attr('src','')
                      $("#"+preview_id).attr('title','no image')
                    }, 1000);
                  }
            });
        };
        reader.readAsDataURL(file);
    });

    $(document).ready(function() {
        pdfMake.fonts = {
            Arial: {
                normal: "{{ asset('assets/home/ttf/arial.ttf') }}",
                bold: "{{ asset('assets/home/ttf/arialbd.ttf') }}",
                italics: "{{ asset('assets/home/ttf/ariali.ttf') }}",
                bolditalics: "{{ asset('assets/home/ttf/arialbi.ttf') }}"
            }
        };
    });
</script>
@include('common-script.admin-export-list-common-script')
<!-- Flipbook main Js file -->
<script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
</body>
</html>
