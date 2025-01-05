@extends('home.layout.app')

@section('content')
  <div class="csm-pages-wraper">
    <div class="mustafai-newswrap">
      <div class="d-flex align-items-center headlineheading-wrap justify-content-center">
          <h3>{{__('app.title')}} :</h3>
          <p class="headline-pad">{{ $headline->title }}</p>
      </div>
      <p>
        <b>{!! $headline->content !!} </b>
      </p>
      <div class="info-wraper-content">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <p><b>{{ __('app.reporter-name')}}</b></p>
          <p>{{ $headline->reporter_name }}</p>
        </div>
        <div class="d-flex align-items-center justify-content-center mb-2">
          <p><b> {{ __('app.repporting-city')}} </b></p>
          <p>{{ (!empty($headline->city)) ? optional($headline->city)->{'name_'.lang().''} :'N/A' }}</p>
        </div>
        <div class="d-flex align-items-center justify-content-center flex-md-row flex-column v ">
            <p><b>{{ __('app.date-and-time')}}</b></p>
            <p>{{ $headline->reporting_date_time }}</p>
        </div>
      </div>
    </div>
  </div>
  @include('home.home-sections.get-in-touch')

@endsection
