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
  <meta property="og:title" content="{{ isset($og_title) ? $og_title :'' }}" />
  <meta property="og:description" content="{{ isset($og_description)? $og_description :'' }}" />
  <meta property="og:image" content="{{ isset($og_image) ?$og_image :'' }}" />
  <!-- Site Title -->
  <title>Mustafai Tehreek</title>
  <!-- Bootstrap-5 CSS -->
  <link rel="stylesheet" href="{{ asset('assets/home/css/bootstrap-5.min.css') }}" />
  <!-- owl carousel -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.css" integrity="sha512-GR6qRxTldLcjTLNcciylGAYoMuUh1jB5alVktd1NgLFRVe+hW1Ao2LewohEWSjGdOmU50gfXMChOx+o83Nc7Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}" />
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
    <main>
      <div class="mt-4 pt-4">
        <div class="container">
          <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="cms-page-content" style="width:100%">
              @if(isset($type) && ($type == 4 || $type == 5))
                <div class="_df_book" height="500" width="500" webgl="true" backgroundcolor="teal" source="{{$filePath}}" id="df_manual_book"></div>
              @endif
              @if (request()->get('type')=="magazine")
              <div class="_df_book" height="500" width="500" webgl="true" backgroundcolor="teal" source="{{$filePath}}" id="df_manual_book"></div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </main>

  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="{{ asset('assets/home/js/jquery.js') }}"></script>
  <script src="{{ asset('assets/home/js/bootstrap-5.min.js') }}"></script>

  <!-- Flipbook main Js file -->
  <script src="{{ asset('assets/dflip/js/dflip.min.js') }}"></script>
</body>

</html>
