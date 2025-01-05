@extends('user.layouts.layout')
@section('content')
@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- tags input -->

<style>
    div#user_table_length {
    padding-top: 15px;
    }
    div#user_table_filter {
    margin-top: -33px;
    }
    div#defaulter_datatable_length {
    padding-top: 15px;
    }
    div#defaulter_datatable_filter {
    margin-top: -33px;
    }
    div#cabinet_list_length {
    padding-top: 15px;
    }
    div#cabinet_list_filter {
    margin-top: -33px;
    }


</style>
@endpush
<div class="userlists-tab">
    <div class="list-tab">
        <form action="{{ route('user.addresses.filter') }}" method="post" id="userAddressForm">
            @csrf
            <nav>
                @if(have_permission('Can-Apply-Filter'))
                    <div class="row filter-row">
                        @if(have_permission('Can-Filter-By-Country'))
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <div class="form-group select-wrap d-flex align-items-center">
                                    <label class="sort-form-select me-2">{{__('app.country')}}</label>
                                    <select name="country_id[]" class="js-example-basic-single form-control" onchange="updateProvince(this)" id="country_id" multiple>
                                        {{--<option value="">{{__('app.select-country')}} </option>--}}
                                        @forelse($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @empty
                                            <option value="">{{  __('app.no-added-yet')}}</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(have_permission('Can-Filter-By-Province'))
                                <div class="col-lg-4 col-sm-6  mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.province')}}</label>
                                        <select name="province_id[]" class="js-example-basic-single form-control" onchange="updateDivisions(this)" id="province_select" multiple>
                                            {{--<option value="">{{__('app.select-province') }} </option>--}}
                                            @forelse($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                        @endif

                        @if(have_permission('Can-Filter-By-Division'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.division')}}</label>
                                        <select name="division_id[]" class="js-example-basic-single form-control" onchange="updateDistricts(this)" id="division_select" multiple>
                                        {{-- <option value="">{{__('app.select-division')}} </option>--}}
                                            @forelse($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                        @endif

                            @if(have_permission('Can-Filter-By-Division'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.city')}}</label>
                                        <select name="city_id[]" class="js-example-basic-single form-control" id="city_select" multiple>
                                            @forelse($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            @endif

                        @if(have_permission('Can-Filter-By-District'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.district')}}</label>
                                        <select name="district_id[]" class="js-example-basic-single form-control" onchange="updateTehsil(this)" id="district_select" multiple>
                                            {{--<option value="">{{__('app.select-district')}}</option>--}}
                                            @forelse($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                        @endif

                        @if(have_permission('Can-Filter-By-Tehsil'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.tehsil')}}</label>
                                        <select name="tehsil_id[]" class="js-example-basic-single form-control" onchange="updateZone(this)" id="tehsil_select" multiple>
                                            {{--<option value="">{{__('app.select-tehsil')}} </option>--}}
                                            @forelse($tehsils as $tehsil)
                                                <option value="{{ $tehsil->id }}">{{ $tehsil->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                        @endif

                        @if(have_permission('Can-Filter-By-Zone'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.zone')}}</label>
                                        <select name="zone_id[]" class="js-example-basic-single form-control"  id="zone_select" multiple>
                                            {{--<option value="">{{__('app.select-zone')}}</option>--}}
                                            @forelse($zones as $zone)
                                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                        @endif

                        @if(have_permission('Can-Filter-By-Union-Council'))
                                <div class="col-lg-4 col-sm-6 mb-3 union--consil-input">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{__('app.union-council')}}</label>
                                        <input id="council_name" type="text" class="form-control" name="union_council" value="">
                                        {{-- <select name="council_id[]" class="js-example-basic-single form-control" id="council_select" multiple>
                                            @forelse($councils as $council)
                                                <option value="{{ $council->id }}">{{ $council->name }}</option>
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select> --}}
                                    </div>
                                </div>
                        @endif

                            @if(have_permission('Can-Filter-By-Occupation'))
                                <div class="col-lg-4 col-sm-6 mb-3">
                                    <div class="form-group select-wrap d-flex align-items-center">
                                        <label class="sort-form-select me-2">{{ __('app.occupation') }}</label>
                                        <select name="occupation_id[]" class="js-example-basic-single form-control" id="occupation_id" multiple="multiple">
                                            @forelse($occupations as $occupation)
                                                <option value="{{ $occupation->id }}">{{ $occupation->title }}</option>
                                                    @foreach($occupation->subProfession as $subProfession)
                                                        {!! $loop->first ? '<optgroup label="Sub-professions">' : '' !!}
                                                            <option value="{{ $subProfession->id }}">{{ $subProfession->title }}</option>
                                                        {!! $loop->last ? '</optgroup>' : '' !!}
                                                    @endforeach
                                            @empty
                                                <option value="">{{  __('app.no-added-yet')}}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            @endif

                        <div class="col-12 d-flex justify-content-end align-items-end mb-3 filters-buttons-hold">
                            <button onclick="getUserType('4','user-4','filter')" type="button" class="blue-hover-bg btn btn-success me-2 btn-sm">{{__('app.apply-filters')}}</button>
                            <button onclick="clearForm()" type="button" class=" blue-hover-bg btn btn-warning btn-sm">{{__('app.clear-filters')}}</button>
                        </div>
                    </div>
                @endif
            </nav>
        </form>

        <br><br>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                @if(have_permission('View-All-User'))
                    <button class="nav-link user-tab user-4 @if(have_permission('View-All-User')) active @endif" data-cl="user-4" data-val="4" onclick="getUserType('4','user-4')">{{__('app.all-users')}}</button>
                @endif
                @if(have_permission('View-Cabinet-Members-Based-On-Province'))
                    <button class="nav-link user-tab user-7 @if((!have_permission('View-All-User'))) active @endif" data-cl="user-7" data-val="7" onclick="getUserType('7','user-7')">{{__('app.cabinet-member-based-of-province')}}</button>
                @endif
                @if(have_permission('View-Cabinet-Members-Based-On-Divison'))
                    <button class="nav-link user-tab user-3  @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province'))) active @endif" data-cl="user-3" data-val="3" onclick="getUserType('3','user-3')">{{__('app.cabinet-member-based-of-divisional')}}</button>
                @endif
                @if(have_permission('View-Cabinet-Members-Based-On-District'))
                    <button class="nav-link user-tab user-2 @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))) active @endif" data-cl="user-2" data-val="2" onclick="getUserType('2','user-2')">{{__('app.cabinet-member-based-of-district')}}</button>
                @endif
                @if(have_permission('View-Cabinet-Members-Based-On-Tehsil'))
                    <button class="nav-link user-tab  user-1 @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))) active @endif" data-cl="user-1" data-val="1" onclick="getUserType('1','user-1')">{{__('app.cabinet-member-based-of-tehsil')}}</button>
                @endif
                @if(have_permission('View-Cabinet-Members-Based-On-City'))
                    <button class="nav-link user-tab user-8 @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))  && (!have_permission('View-Cabinet-Members-Based-On-Tehsil'))) active @endif" data-cl="user-8" data-val="8" onclick="getUserType('8','user-8')">{{__('app.cabinet-member-based-of-city')}}</button>
                @endif
                @if(have_permission('View-Defaulter-Users'))
                    <button class="nav-link user-tab user-5 @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))  && (!have_permission('View-Cabinet-Members-Based-On-Tehsil')) && (!have_permission('View-Cabinet-Members-Based-On-City'))) active @endif" data-cl="user-5" data-val="5" onclick="getDefaulterUsers('5','user-5')">{{ __('app.defaulters') }}</button>
                @endif
                @if(have_permission('Can-View-ALL-Cabinet'))
                    <button class="nav-link user-tab user-9 @if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))  && (!have_permission('View-Cabinet-Members-Based-On-Tehsil')) && (!have_permission('View-Cabinet-Members-Based-On-City')) && (!have_permission('View-Defaulter-Users'))) active @endif" data-cl="user-9" data-val="9" onclick="getCabinetList('9','user-9')">{{ __('app.all_cabinent') }}</button>
                @endif
            </div>
        </nav>
        <input type="hidden" id="locale" value="{{App::getLocale()}}">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active user_table" id="nav-tab1" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="users-table cabinet_users_div  " id="style-2">
                    @if((have_permission('View-All-User')) || (have_permission('View-Cabinet-Members-Based-On-Province')) || (have_permission('View-Cabinet-Members-Based-On-Divison'))  || (have_permission('View-Cabinet-Members-Based-On-District'))  || (have_permission('View-Cabinet-Members-Based-On-Tehsil')) || (have_permission('View-Cabinet-Members-Based-On-City')))
                    <table class=" border-0 table common-table" id="user_table" style="width:100% !important">
                        <thead>
                            <tr>
                                <th scope="col">{{__('app.id-num')}}</th>
                                <th scope="col">{{__('app.name')}}</th>
                                <th scope="col">{{__('app.name')}}</th>
                                <th scope="col">{{__('app.email')}}</th>
                                <th scope="col">{{__('app.address')}}</th>
                                <th scope="col">{{__('app.country')}}</th>
                                <th scope="col">{{__('app.province')}}</th>
                                <th scope="col">{{__('app.division')}}</th>
                                <th scope="col">{{__('app.district')}}</th>
                                <th scope="col">{{__('app.city')}}</th>
                                <th scope="col">{{__('app.tehsil')}}</th>
                                <th scope="col">{{__('app.zone')}}</th>
                                <th scope="col">{{__('app.union-council')}}</th>
                                <th scope="col">{{__('app.Phone-no')}}</th>
                                <th scope="col">{{ __('app.cabinet') }}</th>
                                <th scope="col">{{ __('app.cabinet-role') }}</th>
                                <th scope="col">{{__('app.profile')}}</th>
                                <th scope="col">{{__('app.profile')}}</th>
                                <th scope="col"> {{__('app.occupation')}}</th>
                                <th scope="col"> {{__('app.occupation')}}</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                    @endif
                </div>

             {{-- new user filter table for error problem  --}}

                {{-- <div class="users-tabl user_table_filter  " >
                    <table class=" border-0 table common-table" id="user_table_filter"  style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">{{__('app.name')}}</th>
                                <th scope="col">{{__('app.email')}}</th>
                                <th scope="col">{{__('app.profile-status')}}</th>
                                <th scope="col">{{__('app.profile')}}</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div> --}}
            {{-- new user filter table for error problem end --}}
                @if(have_permission('View-Defaulter-Users'))
                    <div class="users-table defaulter_datatable" id="">
                        <table class="table border-0 common-table" id="defaulter_datatable" style="width: 100%;">
                            <thead>
                            <tr>
                                <th scope="col">{{__('app.name')}}</th>
                                <th scope="col">{{ __('app.defaulter-dates') }}</th>
                                <th scope="col">{{ __('app.defaulter-plan') }}</th>
                                <th scope="col">{{__('app.profile')}}</th>
                                <th scope="col">{{__('app.profile')}}</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                @endif
                @if(have_permission('Can-View-ALL-Cabinet'))
                    <div class="users-table cabinet_list" id="">
                        <table class="table border-0 common-table" id="cabinet_list" style="width: 100%;">
                            <thead>
                            <tr>
                                <th scope="col">{{__('app.name')}}</th>
                                <th scope="col">{{ __('app.action') }}</th>
                                {{-- <th scope="col">{{__('app.status')}}</th>
                                <th scope="col">{{__('app.status')}}</th> --}}
                            </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="d-flex justify-content-center justify-content-center my-xl-4 my-2">
                    {{-- <button class="theme-btn text-capitalize" href="/">view all</button> --}}
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Defaulter Dates Modal -->
<div class="modal fade  profile-modlal library-detail common-model-style" id="defaulterDatesModal" tabindex="-1" role="dialog" aria-labelledby="defaulterDatesModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title text-green" id="exampleModalLabel">{{ __('app.defaulter-dates') }}</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="defaulter-dates-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="theme-btn-borderd-btn outline-bg theme-btn" data-dismiss="modal">{{ __('app.close') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- update Occupation modal -->
<div class="modal fade library-detail common-model-style" id="updateOccupationModal" tabindex="-1" role="dialog"
    aria-labelledby="updateOccupationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.occupation') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body user_occupation">

            </div>


        </div>
    </div>
</div>

<!-- view Cabinet Users modal   -->

<div class="modal library-detail common-model-style fade" id="viewCabinetModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.cabinet-user') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body to_append_users">
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<!-- tags input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('assets/admin/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/admin/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/admin/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
@include('user.scripts.font-script')
@include('user.scripts.user-list-script')

<script>

$(document).ready(function() {
  // Initialize Select2
//   $('#country_id').select2();

$(".select2-search__field").each(function(){
    parent = $(this).closest('div')
    innerhtml = parent.children('label').html();
    $(this).attr('placeholder', innerhtml);
});

  // Add placeholder functionality
//   debugger;
  	// $('.select2-search__field').attr('placeholder', "{{__('app.select_option')}}");
    $('.select2-search__field').css({'width':'137px'});
    $('.select2-selection').css({'width':'137px'});
//     // Add placeholder functionality
//   $('.select2-search__field').on('select2:open', function(e) {
//     $(this).each(function(){
//         debugger;
//     parent = $(this).closest('div')
//     innerhtml = parent.children('label').html();
//     $(this).attr('placeholder', innerhtml);
// });
//   });
});





    function viewCabinetUsers(_this) {
    $('.to_append_users').html('');
    let cabinetId = $(_this).attr('data-cabinet-id');

    $.ajax({
        type: "POST",
        url: "{{route('user.view-cabinet-users')}}",
        data: {
            '_token': "{{csrf_token()}}",
            'cabinet_id': cabinetId
        },
        success: function(result) {
            if (result.status === 200) {
                console.log(result, 'result')
                $('.to_append_users').html('');
                $('.to_append_users').html(result.data);
            }
        }
    });

    $('#viewCabinetModal').modal('toggle')
    }

</script>

@endpush
