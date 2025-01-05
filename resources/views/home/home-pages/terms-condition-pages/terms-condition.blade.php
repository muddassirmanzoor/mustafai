@extends('home.layout.app')
@section('content')
     @include('home.home-sections.banner')
     <div class="csm-pages-wraper">
     <section class="mustafai-managment-pg">
               <div class="container-fluid container-width">
                    <div class="row d-flex justify-content-center mb-3">
                         <h2 class="text-center"> {{ __('app.terms-&-condition')}}</h2>
                    </div>
                    <div class="row">
                         {!! $plan->{'term_conditions_'.App::getLocale()} !!}
                    </div>
               </div>
          </section>
     </div>
     @include('home.home-sections.get-in-touch')
@endsection

