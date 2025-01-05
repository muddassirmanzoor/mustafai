{{-- @php
    $have_permision=have_permission('Export-User-List');
@endphp --}}
<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
<script>

    var isFilter = true;
     var locale = $('#locale').val()
    $(function() {
        $('.js-example-basic-multiple').select2();
        var type = $('.user-tab.active').attr('data-val');
        var tabClass = $('.user-tab.active').attr('data-cl');
        var load_default_datatable = "<?php if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))  && (!have_permission('View-Cabinet-Members-Based-On-Tehsil')) && (!have_permission('View-Cabinet-Members-Based-On-City')) && (have_permission('View-Defaulter-Users'))){ echo "true";} else{echo "false";} ?>";
        var load_cabinet_list_datatable = "<?php if((!have_permission('View-All-User')) && (!have_permission('View-Cabinet-Members-Based-On-Province')) && (!have_permission('View-Cabinet-Members-Based-On-Divison'))  && (!have_permission('View-Cabinet-Members-Based-On-District'))  && (!have_permission('View-Cabinet-Members-Based-On-Tehsil')) && (!have_permission('View-Cabinet-Members-Based-On-City')) && (!have_permission('View-Defaulter-Users'))){ echo "true";} else{echo "false";} ?>";
        if(load_default_datatable=='true'){
            getDefaulterUsers(type,tabClass);
        }
        if(load_cabinet_list_datatable=='true'){
            getCabinetList(type,tabClass);
        }
        else{
            getUserType(type,tabClass);
        }

    });

    //______________For Append Data In DataTable_____________//
    function getUserType(type='',tabClass='',filter='')
    {
        var visible=true;
        var can_view_all_detail="<?php if((have_permission('Can-View-User-All-Detail')) ){ echo "true";} else{echo "false";} ?>";
        if(can_view_all_detail=='false'){
            visible=false;
        }
        var allow=''
        var have_permision = "<?php echo have_permission('Export-User-List'); ?>";
        if(have_permision){
            var allow=dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '8%', '12%', '12%','12%','12%','12%','12%','19%'],  arraycolumn = [0,2,3,4,5,6,9,10],"{{__('app.user-list')}}")
        }
        if (type == 5) return // type 5 = defaulter, because we load another datatable in case of defaulters

        $('.defaulter_datatable').css('display', 'none');
        $('.cabinet_list').css('display', 'none');
        $('.cabinet_users_div').css('display', 'block');
        $('.user-tab').removeClass('active');
        $('.user-tab.'+tabClass).addClass('active');

        $('#user_table').dataTable().fnClearTable();
        $('#user_table').dataTable().fnDestroy();
        $('#user_table').DataTable().clear().destroy();
        $('#user_table').dataTable(
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
            info : true,
            retrieve: true,
            scrollX: true,
            sort: false,
            pageLength: 50,
            processing: false,
            "deferRender": true,
            "destroy": true,
            drawCallback : function( ) {
                hideOverlayLoader();
            },
            responsive: false,
            buttons:allow,
            dom: 'Blfrtip',
            buttons: arry,
            lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            serverSide: true,
            ajax: {
                url: '{{ URL("user/user-tabs") }}',
                data: function (d) {
                    d._token = "{{csrf_token()}}",
                    d.country_id = $('#country_id').val();
                    d.province_id = $('#province_select').val();
                    d.division_id = $('#division_select').val();
                    d.city_id = $('#city_select').val();
                    d.district_id = $('#district_select').val();
                    d.tehsil_id = $('#tehsil_select').val();
                    d.zone_id = $('#zone_select').val();
                    d.occupation_id = $('#occupation_id').val();
                    d.council_name = $('#council_name').val();
                    d.data_type = 'user-list';
                    d.filter = filter;
                    d.type = type;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'full_name', name: 'full_name'},
                {data: 'user_name', name: 'user_name',visible:false},
                {data: 'email', name: 'email',visible:visible},
                {data: 'address_'+locale, name: 'address_'+locale},
                {data: 'country', name: 'country'},
                {data: 'province', name: 'province'},
                {data: 'division', name: 'division'},
                {data: 'district', name: 'district'},
                {data: 'city_id', name: 'city_id'},
                {data: 'tehsil', name: 'tehsil'},
                {data: 'zone', name: 'zone'},
                {data: 'union_council', name: 'union_council'},
                {data: 'phone_number', name: 'phone_number',visible:visible},
                {data: 'cabinets', name: 'cabinets'},
                {data: 'cabinets_role', name: 'cabinets_role'},
                {data: 'profileStatus', name: 'profileStatus',visible: false},
                { data: 'profile', name: 'profile',visible:visible},
                { data: 'occupation_data', name: 'occupation_data'},
                { data: 'occupationAll', name: 'occupationAll',visible: false},
            ],
            destroy: true,
        }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });
        $('#user_table').DataTable().ajax.reload();
        $('input.form-control.form-control-sm').attr("placeholder", "Search for users");

    }

    function getDefaulterUsers(type='',tabClass='')
    {
        var allow=''
        var have_permision = "<?php echo have_permission('Export-User-List'); ?>";
        if(have_permision){
            var allow=dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '8%', '12%', '12%','12%','12%','12%','12%','19%'],  arraycolumn = [0,1,2,3,4,5,6,9],"{{__('app.user-list')}}")
        }
        $('.defaulter_datatable').css('display', 'block');
        $('.user_table_filter').css('display', 'none');
        $('.cabinet_list').css('display', 'none');

        $('.cabinet_users_div').css('display', 'none');

        var imge= "{{asset("assets/home/images/site-logo.png")}}";
        // alert(imge)
        clearForm();
        $('.user-tab').removeClass('active');
        $('.user-tab.'+tabClass).addClass('active');

        // data table work
        $('#defaulter_datatable').dataTable().fnClearTable();
        $('#defaulter_datatable').dataTable().fnDestroy()
        $('#defaulter_datatable').dataTable(
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
            processing: false,
            // language: { "processing": showOverlayLoader()},
            drawCallback : function( ) {
                hideOverlayLoader();
            },
            responsive: true,
            dom: 'Bfrtip',
            buttons:allow,
            dom: 'Blfrtip',
            buttons: arry,
            lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            serverSide: true,

            ajax: {
                url: '{{ URL("user/user-tabs") }}',
                data: function (d) {
                    d._token = "{{csrf_token()}}",
                        d.type = type;
                }
            },

            columns: [
                {data: 'full_name', name: 'full_name'},
                // {data: 'email', name: 'email'},
                {data: 'defaulter_dates', name: 'defaulter_dates'},
                {data: 'plan', name: 'plan'},
                {
                data: 'profileStatus', name: 'profileStatus',visible: false
                },
                {
                    data: 'profile', name: 'profile',

                    render: function(data, type, row) {
                        if (type === 'myExport') {
                            return data;
                            //   let colarray=data.replace(/(<([^>]+)>)/ig,"").split("");
                            //   return colarray.reverse().join("")
                        }
                        return data;
                    },


                },




            ]
        }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });

    }

    function getCabinetList(type='',tabClass='')
    {
        var allow=''
        var have_permision = "<?php echo have_permission('Export-User-List'); ?>";
        var can_view_cabine_users = "<?php echo have_permission('Can-View-Cabinet-Users'); ?>";
        if(have_permision){
            var allow=dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '100%'],  arraycolumn = [0],"{{__('app.user-list')}}")
        }
        $('.defaulter_datatable').css('display', 'none');
        $('.user_table_filter').css('display', 'none');
        $('.cabinet_list').css('display', 'block');


        $('.cabinet_users_div').css('display', 'none');
        $('.default_list').css('display', 'none');

        var imge= "{{asset("assets/home/images/site-logo.png")}}";
        // alert(imge)
        clearForm();
        $('.user-tab').removeClass('active');
        $('.user-tab.'+tabClass).addClass('active');

        // data table work
        $('#cabinet_list').dataTable().fnClearTable();
        $('#cabinet_list').dataTable().fnDestroy()
        $('#cabinet_list').dataTable(
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
            processing: false,
            // language: { "processing": showOverlayLoader()},
            drawCallback : function( ) {
                hideOverlayLoader();
            },
            responsive: true,
            dom: 'Bfrtip',
            buttons:allow,
            dom: 'Blfrtip',
            buttons: arry,
            lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            serverSide: true,

            ajax: {
                url: '{{ URL("user/cabinent-list") }}',
                data: function (d) {
                    d._token = "{{csrf_token()}}",
                        d.type = type;
                }
            },

            columns: [
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action',visible:can_view_cabine_users},
                // {data: 'status', name: 'status'},
                // {data: 'HiddenStatus', name: 'HiddenStatus',visible: false},
            ]
        }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });

    }


    function updateProvince(_this) {
        if (isFilter === false) return;
        let id =  $(_this).val();
        $('#province_select').find('option').remove();
        $.ajax({
            type: "POST",
            url: "{{route('user.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", country_id: id},
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
            url: "{{route('user.addresses.filter')}}",
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
            url: "{{route('user.addresses.filter')}}",
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
            url: "{{route('user.addresses.filter')}}",
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
            url: "{{route('user.addresses.filter')}}",
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
            url: "{{route('user.addresses.filter')}}",
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

    // apply filter

    $('.apply_filter').click(function () {
        let data = $('#userAddressForm').serialize();

        $.ajax({
            type: "POST",
            url: "{{route('user.addresses.filter.apply')}}",
            data: data,
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {

                }
            }

        });
    });


    function clearForm()
    {

        isFilter = false;
        $('.js-example-basic-single').each(function(){
            $(this).val(' ');
            $(this).trigger('change');

        })
        var oTable = $('#user_table').DataTable();
        oTable.ajax.reload();
        setTimeout(() => isFilter = true, 1000)
    }

    function showDefaulterDates(_this) {
        let dates = $(_this).attr('data-dates').split(',')
        $('.defaulter-dates-body').html('')
        for(let i=0; i<dates.length; i++) {
            $('.defaulter-dates-body').append(`
                    <span class="mb-2">${dates[i]}</span>
                `)
        }
    }

    function userOccupation(userId){
        $.ajax({
            type: "POST",
            url: "{{route('user.show-occupation')}}",
            data: {'_token': "{{csrf_token()}}", user_id: userId},
            success: function(result) {
                if (result.status === 200) {
                    $("#updateOccupationModal").modal('show');
                    $('.user_occupation').html(result.html);
                }
            }
        });
    }

    function showOverlayLoader()
    {

    }
    function hideOverlayLoader()
    {
    }

</script>
