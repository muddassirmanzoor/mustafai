<html>

<head>
  <!-- Mobile Specific Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Favicon-->
  <link rel="shortcut icon" href="{{asset('assets/home/images/favicon.png')}}" />
  <!-- Meta Description -->
  <meta name="description" content="" />
  <!-- Meta Keyword -->
  <meta name="keywords" content="" />
  <!-- meta character set -->
  <meta charset="UTF-8" />
  {{-- facebook meta tags --}}
  <meta property="og:title" content="{{ $og_title }}" />
  <meta property="og:description" content="{{ $og_description }}" />
  <meta property="og:image" content="{{ $og_image }}" />
  <!-- Site Title -->
  <title>Mustafai Tehreek</title>
  <!-- Bootstrap-5 CSS -->
  <link rel="stylesheet" href="{{ asset('assets/home/css/bootstrap-5.min.css') }}" />
  <!-- owl carousel -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  @if(App::getLocale() == 'english')
  <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}" />
  @endif
  @if(App::getLocale() == 'urdu' || App::getLocale() == 'arabic')
  <link rel="stylesheet" href="{{ asset('assets/home/css/ur-ar-style.css') }}" />
  @endif
  <!-- for sweet alert  -->

  <link rel="stylesheet" href="{{ url('user/css/mustafai.css') }}" />
  <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <!-- Flipbook StyleSheet -->
    <link rel="stylesheet" href="{{ asset('assets/dflip/css/dflip.min.css') }}" />

    <!-- Icons Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/dflip/css/themify-icons.min.css') }}" />
</head>

<body class="{{(App::getLocale() != 'english') ? 'ur-ar-version':'en-version'}}">
  <div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center d-none">
      <img class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}" alt="Mustafai Dashboard" height="" width="140">
    </div>
    @include('home.sections.header')
    <main>
      <div class="csm-pages-wraper">
        <div class="container">
          <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="cms-page-title">
              <h3 class="about-h-1 text-center">Title</h3>
            </div>
            <div class="cms-page-content">

              @if($type == 1)

              <div id="images" class="images">
                <div class="share-image-container">
                  <img src="{{$filePath }}" id="imageshare"  class="images2 img-fluid" alt="Picture 1">
                </div>
              </div>
              @elseif($type ==2)

              @if($type_video == 0 )
             <div class="share-video-conatiner">
                <video width="100%" type-data="video" height="100%" controls="" src="{{ $filePath }}">
                </video>
             </div>
              @else
              <div class="share-video-conatiner">
                <iframe width="100%" height="100%" src="{{$filePath}}"></iframe>
              </div>
              @endif

              @elseif($type ==3)

              <audio width="100%"controls src="{{$filePath}}"></audio>

              @elseif($type == 4 || $type == 5)
              {{-- <iframe src="{{$filePath}}"></iframe> --}}
              {{-- <div class="_df_book" background-color="teal" source="{{$filePath}}"></div> --}}
              <!--Normal FLipbook-->
            <div class="_df_book" height="500" width="500" webgl="true" backgroundcolor="teal" source="{{$filePath}}" id="df_manual_book"></div>
              @else
              @endif
            </div>
          </div>
        </div>
      </div>
    </main>
@include('home.home-sections.get-in-touch')

    @include('home.sections.footer')

  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="{{ asset('assets/home/js/jquery.js') }}"></script>
  <script src="{{ asset('assets/home/js/bootstrap-5.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js" integrity="sha512-UzpQxIWgLbHvbVd4+8fcRWqFLi1pQ6qO6yXm+Hiig76VhnhW/gvfvnacdPanleB2ak+VwiI5BUqrPovGDPsKWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    // function loadlibraryApi(){
    const viewer = new Viewer(document.getElementById('images'), {
      toolbar: {
        zoomIn: 4,
        zoomOut: 4,
        oneToOne: 4,
        reset: 4,
        prev: 4,
        play: {
          show: 4,
          size: 'large',
        },
        next: 4,
        rotateLeft: 4,
        rotateRight: 4,
        flipHorizontal: 4,
        flipVertical: 4,
        title:false
      },

    });

    // }
    $("#imageshare").trigger('click')
  </script>
  <!-- Flipbook main Js file -->
  <script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
</body>

</html>
