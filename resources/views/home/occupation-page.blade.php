@extends('home.layout.app')

@push('header-scripts')
<!-- DataTables -->

<link rel="stylesheet" href="{{asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!--select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    div#user_table_length {
    padding-top: 15px;
    }
    div#user_table_filter {
    margin-top: -33px;
    }
    div#seekers-datatable_length {
    padding-top: 15px;
    }

    div#user-table_filter {
    margin-top: -33px;
    text-align: right;
}
</style>
@endpush
@section('content')
    @if(!empty($occupation))
        <div class={{ request()->route()->getPrefix() !== 'api'? "csm-pages-wraper" : "mt-4 mb-4" }} >
            <div class="container">
                <div class="d-flex flex-column justify-content-center align-items-center green-box">
                    <div class="cms-page-title">
                        <h3 class="about-h-1 text-center text-red">{{$occupation->title}}</h3>

                    </div>
                    <div class="cms-page-content mt-3 text-center occupation-content">
                        {!! str_replace('src="http://127.0.0.1:8000','src="'.url('').'',$occupation->content)  !!}
                    </div>
                    <br>
                    @if(count($occupation->subProfession))
                            <div class="child-occupations">
                                <ul>
                                    @foreach($occupation->subProfession  as $profession)
                                    @if(request()->route()->getPrefix() !== 'api')
                                        <li><a href="{{ url('professions/'.$profession->slug) }}">{{ $profession->{'title_'.app()->getLocale()} }}</a></li>
                                    @else
                                        <li><a href="{{ url('api/professions/'.$profession->slug.'?lang='.app()->getLocale()) }}">{{ $profession->{'title_'.app()->getLocale()} }}</a></li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>
                <hr>
                @if(request()->route()->getPrefix() !== 'api')
                {{-- @if(checkAuthToken()===true) --}}
                <div class="related-user-sec ocupation-user users-table green-box">
                    @auth
                    <h3 class="about-h-1 text-center text-red">{{__('app.related-users')}}</h3>
                    <div class="list-tab">
                        <form action="{{ route('profession.addresses.filter') }}" method="post" id="userAddressForm">
                            @csrf
                            <nav>
                                <div class="row filter-row">
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

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.district')}}</label>
                                                <select name="district_id[]" class="js-example-basic-single form-control" onchange="updateTehsil(this)" id="district_select" multiple>
                                                    {{-- <option value="">{{__('app.select-district')}}</option> --}}
                                                    @forelse($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.tehsil')}}</label>
                                                <select name="tehsil_id[]" class="js-example-basic-single form-control" onchange="updateZone(this)" id="tehsil_select" multiple>
                                                    {{-- <option value="" disabled selected>Select options...</option> --}}
                                                    @forelse($tehsils as $tehsil)
                                                        <option value="{{ $tehsil->id }}">{{ $tehsil->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.zone')}}</label>
                                                <select name="zone_id[]" class="js-example-basic-single form-control" onchange="updateCouncils(this)" id="zone_select" multiple>
                                                    <option value="">{{__('app.select-zone')}}</option>
                                                    @forelse($zones as $zone)
                                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.union-council')}}</label>
                                                <select name="council_id[]" class="js-example-basic-single form-control" id="council_select" multiple>
                                                    {{--<option value="">{{__('app.select-union-counsil')}}</option>--}}
                                                    @forelse($councils as $council)
                                                        <option value="{{ $council->id }}">{{ $council->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

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

                                    <div class="col-12 d-flex justify-content-end align-items-end mb-3 filters-buttons-hold">
                                        <input type="hidden" id="slug_input" value="{{ $slug }}">
                                        <button onclick="applyFilter()" type="button" class="blue-hover-bg btn btn-success me-2 btn-sm">{{__('app.apply-filters')}}</button>
                                        <button onclick="clearForm()" type="button" class=" blue-hover-bg btn btn-warning btn-sm">{{__('app.clear-filters')}}</button>
                                    </div>
                                </div>
                            </nav>
                        </form>
                    </div>

                    <div class="related-user-sec-filters">

                    </div>
                    <div class="row">
                        <table class="table border-0" id="user-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('app.name')}}</th>
                                    <th>{{__('app.profile')}}</th>
                                    <th>{{__('app.email')}}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_data">

                            </tbody>
                        </table>
                    </div>
                    @endauth

                    @guest

                    <a href="{{url('login')}}"><h3 class="about-h-1 text-center text-red " >{{__('app.login-to-see-more-details')}}</h3></a>

                    @endguest
                </div>

                @endif
                {{-- make common page for Mobile Screen --}}
                @if(request()->route()->getPrefix() === 'api')
                {{-- @if(checkAuthToken()===true) --}}
                <div class="related-user-sec ocupation-user users-table green-box">

                    <h3 class="about-h-1 text-center text-red">{{__('app.related-users')}}</h3>
                    <div class="list-tab">
                        <form action="{{ route('profession.addresses.filter') }}" method="post" id="userAddressForm">
                            @csrf
                            <nav>
                                <div class="row filter-row">
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

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.district')}}</label>
                                                <select name="district_id[]" class="js-example-basic-single form-control" onchange="updateTehsil(this)" id="district_select" multiple>
                                                    {{-- <option value="">{{__('app.select-district')}}</option> --}}
                                                    @forelse($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.tehsil')}}</label>
                                                <select name="tehsil_id[]" class="js-example-basic-single form-control" onchange="updateZone(this)" id="tehsil_select" multiple>
                                                    {{-- <option value="" disabled selected>Select options...</option> --}}
                                                    @forelse($tehsils as $tehsil)
                                                        <option value="{{ $tehsil->id }}">{{ $tehsil->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.zone')}}</label>
                                                <select name="zone_id[]" class="js-example-basic-single form-control" onchange="updateCouncils(this)" id="zone_select" multiple>
                                                    <option value="">{{__('app.select-zone')}}</option>
                                                    @forelse($zones as $zone)
                                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group select-wrap d-flex align-items-center">
                                                <label class="sort-form-select me-2">{{__('app.union-council')}}</label>
                                                <select name="council_id[]" class="js-example-basic-single form-control" id="council_select" multiple>
                                                    {{--<option value="">{{__('app.select-union-counsil')}}</option>--}}
                                                    @forelse($councils as $council)
                                                        <option value="{{ $council->id }}">{{ $council->name }}</option>
                                                    @empty
                                                        <option value="">{{  __('app.no-added-yet')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

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

                                    <div class="col-12 d-flex justify-content-end align-items-end mb-3 filters-buttons-hold">
                                        <input type="hidden" id="slug_input" value="{{ $slug }}">
                                        <button onclick="applyFilter()" type="button" class="blue-hover-bg btn btn-success me-2 btn-sm">{{__('app.apply-filters')}}</button>
                                        <button onclick="clearForm()" type="button" class=" blue-hover-bg btn btn-warning btn-sm">{{__('app.clear-filters')}}</button>
                                    </div>
                                </div>
                            </nav>
                        </form>
                    </div>

                    <div class="related-user-sec-filters">

                    </div><div class="row">
                        <table class="table border-0" id="user-table" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>{{__('app.name')}}</th>
                                <th>{{__('app.profile')}}</th>
                                <th>{{__('app.email')}}</th>
                            </tr>
                            </thead>
                            <tbody class="tbody_data">

                            </tbody>
                        </table>
                    </div>

                </div>

                @endif
            </div>
        </div>
    @else
        <div class="csm-pages-wraper">
            <div class="container-fluid container-width">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h3 class="about-h-1 text-center">{{ __('app.sorry') }}</h3>
                    <p class="about-t-1 text-center">
                        {{ __('app.no-page-found') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @if(checkAuthToken()===true && request()->route()->getPrefix() !== 'api')
        @include('home.home-sections.get-in-touch')
    @endif

@endsection

@push('footer-scripts')
<script src="{{asset('assets/admin/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>


<script>
    $(function(){
        $('.js-example-basic-single').select2();
        $('#user-table').dataTable(
            {
                "language": {
                    searchPlaceholder: `{{__('app.search_record')}}`,
                    "oPaginate": {
                        "sFirst":    `{{__('app.first')}}`,
                        "sLast":    `{{__('app.last')}}`,
                        "sNext":    `{{__('app.next')}}`,
                        "sPrevious": `{{__('app.previous')}}`,
                    },
                    "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                    "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                    "sSearch":  `{{__('app.search')}}`,
                    "sZeroRecords":   `{{__('app.no-data-available')}}`,
                    "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

                },
                sort: false,
                pageLength: 50,
                scrollX: true,
                processing: false,
                // language: { "processing": showOverlayLoader()},
                drawCallback : function( ) {
                    hideOverlayLoader();
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons:dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '33%', '33%', '33%'],  arraycolumn = [0,1,2],"{{__('app.user-list')}}"),
                dom: 'Blfrtip',
                buttons: arry,
                lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                serverSide: true,

                ajax: {
                    url: '{{ route('occupations', $slug) }}',
                    data: function (d) {
                        d._token = "{{csrf_token()}}"
                            {{--d.slug = {{ $slug }};--}}
                    }
                },

                columns: [
                    {data: 'full_name', name: 'full_name'},
                    {data: 'profile_image', name: 'profile_image'},
                    {data: 'email', name: 'email'},
                ]
            }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });
    })

    var isFilter = true;

    function updateProvince(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#province_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", country_id: id, lang: "{{ app()->getLocale() }}"},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#province_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#province_select").append(option);
                        }
                    }
                    /*for (var i = 0; i < result.total; i++) {
                        console.log(result['data'][i].name)
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#province_select").append(option);
                    }*/
                }
            }

        });
    }

    function updateDivisions(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#division_select').find('option').remove();
        $('#city_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", province_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#division_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#division_select").append(option);
                        }
                    }
                    /* for (var i = 0; i < result.total; i++) {
                         var id = result['data'][i].id;
                         var name =result['data'][i].name;
                         var option = "<option value='" + id + "'>" + name + "</option>";
                         $("#division_select").append(option);
                     }*/

                    for (const [key, value] of Object.entries(result['cities'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#city_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#city_select").append(option);
                        }
                    }

                    /*for (var i = 0; i < result.city_total; i++) {
                        console.log(result['cities'][i].name)
                        var city_id = result['cities'][i].id;
                        var city_name =result['cities'][i].name;
                        var city_option = "<option value='" + city_id + "'>" + city_name + "</option>";
                        $("#city_select").append(city_option);
                    }*/
                }

            }

        });
    }

    function updateDistricts(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#district_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", division_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#district_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#district_select").append(option);
                        }
                    }
                    /*for (var i = 0; i < result.total; i++) {
                        console.log(result['data'][i].name)
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#district_select").append(option);
                    }*/
                }
            }
        });
    }

    function updateTehsil(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#tehsil_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", district_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#tehsil_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#tehsil_select").append(option);
                        }
                    }
                    /* for (var i = 0; i < result.total; i++) {
                         console.log(result['data'][i].name)
                         var id = result['data'][i].id;
                         var name =result['data'][i].name;

                         var option = "<option value='" + id + "'>" + name + "</option>";

                         $("#tehsil_select").append(option);
                     }*/
                }
            }
        });
    }

    function updateZone(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#zone_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", tehsil_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#zone_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#zone_select").append(option);
                        }
                    }
                    /* for (var i = 0; i < result.total; i++) {
                         console.log(result['data'][i].name)
                         var id = result['data'][i].id;
                         var name =result['data'][i].name;

                         var option = "<option value='" + id + "'>" + name + "</option>";

                         $("#zone_select").append(option);
                     }*/
                    updateCouncils(_this);
                }
            }
        });
    }

    function updateCouncils(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#council_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('profession.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", zone_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (const [key, value] of Object.entries(result['data'])) {
                        let option = `<option value='' disabled>${key}</option>`
                        $("#council_select").append(option)

                        for (let i = 0; i < value.length; i++) {
                            let id = value[i].id;
                            let name =value[i].name;

                            let option = `<option value=${id}>${name}</option>`
                            $("#council_select").append(option);
                        }
                    }
                    /*for (var i = 0; i < result.total; i++) {
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#council_select").append(option);
                    }*/
                }
            }
        });
    }

    function applyFilter()
    {
        $('#user-table').dataTable().fnClearTable();
        $('#user-table').dataTable().fnDestroy();
        let data = $('#userAddressForm').serialize();


        $('#user-table').dataTable(
            {
                "language": {
                    "oPaginate": {
                        "sFirst":    `{{__('app.first')}}`,
                        "sLast":    `{{__('app.last')}}`,
                        "sNext":    `{{__('app.next')}}`,
                        "sPrevious": `{{__('app.previous')}}`,
                    },
                    "sLengthMenu":    `{{__('app.showing')}}  _MENU_  {{__('app.enteries')}}`,
                    "sInfo":          `{{__('app.showing')}} _START_ {{__('app.to')}} _END_ {{__('app.of')}} _TOTAL_ {{__('app.enteries')}}`,
                    "sSearch":  `{{__('app.search')}}`,
                    "sZeroRecords":   `{{__('app.no-data-available')}}`,
                    "sInfoEmpty":     `{{__('app.showing')}} 0 {{__('app.to')}} 0 {{__('app.of')}}  0 {{__('app.enteries')}}`,

                },
                sort: false,
                pageLength: 50,
                scrollX: true,
                processing: true,
                drawCallback : function( ) {
                    hideOverlayLoader();
                },
                responsive: true,
                dom: 'Blfrtip',
                buttons:dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '33%', '33%', '33%'],  arraycolumn = [0,1,3],"{{__('app.user-list')}}"),
                lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                serverSide: true,

                ajax: {
                    url: '{{ URL("apply-profession-filter-addresses") }}',
                    data: function (d) {
                        d._token = "{{csrf_token()}}"
                        d.country_id = $('#country_id').val();
                        d.province_id = $('#province_select').val();
                        d.division_id = $('#division_select').val();
                        d.city_id = $('#city_select').val();
                        d.district_id = $('#district_select').val();
                        d.tehsil_id = $('#tehsil_select').val();
                        d.zone_id = $('#zone_select').val();
                        d.occupation_id = $('#occupation_id').val();
                        d.council_id = $('#council_select').val();
                        d.slug = $('#slug_input').val()
                    }
                },

                columns: [
                    {data: 'full_name', name: 'full_name'},
                    {data: 'profile_image', name: 'profile_image'},
                    {data: 'email', name: 'email'},

                ]
            }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });
        // var oTable = $('#user-table').DataTable();
        // oTable.ajax.reload();
    }

    function showOverlayLoader()
    {
    }
    function hideOverlayLoader()
    {
    }

    function clearForm()
    {
        isFilter = false;
        $('.js-example-basic-single').each(function(){
            $(this).val(' ');
            $(this).trigger('change');

        })
        // $('#user-table').dataTable();
        var oTable = $('#user-table').DataTable();
			oTable.ajax.reload();
        // $('.js-example-basic-single').val(null).trigger('change');
        setTimeout(() => isFilter = true, 1000)
    }

    $(document).ready(function() {
  // Initialize Select2
//   $('#country_id').select2();

  // Add placeholder functionality
  	$('.select2-search__field').attr('placeholder', "{{__('app.select_option')}}");
    $('.select2-search__field').css({'width':'137px'});
    $('.select2-selection').css({'width':'137px'});
    // Add placeholder functionality
  $('#country_id').on('select2:open', function(e) {
    $('.select2-search__field').attr('placeholder', "{{__('app.select_option')}}");
    $('.select2-search__field').css({'width':'137px'});
    $('.select2-selection').css({'width':'137px'});
  });
});

$('#user-notification-table_filter input').attr("placeholder", "Search for users");

</script>
@endpush
