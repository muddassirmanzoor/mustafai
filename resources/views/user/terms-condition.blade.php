@extends('user.layouts.layout')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- tags input -->

    <style>
        div#subscription-datatable_length {
        padding-top: 15px;
        }
        div#subscription-datatable_filter {
        margin-top: -33px;
        }
  </style>
@endpush
@section('content')
<div class="userlists-tab">
   <h3>Terma And Condtions</h3>
   <div class></div>
</div>
@endsection

@push('scripts')

@endpush
