<!-- Global Var -->
<script src="{{asset('assets/admin/dist/js/binary-image.js')}}"></script>
<script>

    var isFilter = true;
     var locale = $('#locale').val()
    $(function() {
        $('.js-example-basic-multiple').select2();
        var type = $('.user-tab.active').attr('data-val');
        var tabClass = $('.user-tab.active').attr('data-cl');
        getUserType(type,tabClass);
    });

    //______________For Append Data In DataTable_____________//
    function getUserType(type='',tabClass='')
    {
        // alert("ok");
        // alert(type);
        if (type == 5) return // type 5 = defaulter, because we load another datatable in case of defaulters

        $('.defaulter_datatable').css('display', 'none');
        $('.cabinet_users_div').css('display', 'block');

        var imge= "{{asset("assets/home/images/site-logo.png")}}";
        // alert(imge)
        clearForm();
        $('.user-tab').removeClass('active');
        $('.user-tab.'+tabClass).addClass('active');

        // // data table work
      
        $('#user_table').dataTable().fnClearTable();
        $('#user_table').dataTable().fnDestroy();
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
                scrollX: true,
                sort: false,
                pageLength: 50,
                processing: false,
                // language: { "processing": showOverlayLoader()},
                drawCallback : function( ) {
                    hideOverlayLoader();
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons:dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '33%', '33%', '33%'],  arraycolumn = [0,1,2],"{{__('app.user-list')}}"),
                // dom: 'Blfrtip',
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
                    //     {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {data: 'user_name', name: 'user_name'},
                    {data: 'email', name: 'email'},
        
                    // {data: 'address_'+locale, name: 'address_'+locale},
                    // {data: 'city_id', name: 'city_id'},
                    // {data: 'phone_number', name: 'phone_number'},
                    // {
                    //     data: 'profileStatus', name: 'profileStatus',visible: false
                    // },

                    {
                        data: 'profileStatus', name: 'profileStatus',visible: false
                    },
                    {
                        data: 'profile', name: 'profile',

                        // render: function(data, type, row) {
                        //     if (type === 'myExport') {
                        //       return data;
                        //     //   let colarray=data.replace(/(<([^>]+)>)/ig,"").split("");
                        //     //   return colarray.reverse().join("")
                        //     }
                        //      return data;
                        // },
                        
                        
                        
                    },
                    // {
                    //     data: 'occupation_data',
                    //     name: 'occupation_data',
                    // },

                 

                ]
            }).on( 'length.dt', function () {
            }).on('page.dt', function () {
            }).on( 'order.dt', function () {
            }).on( 'search.dt', function () {
            });

    }

    function applyFilter()
    { 
        // alert("ok")
        $('.user-tab').removeClass('active');
        let data = $('#userAddressForm').serialize();
        $('.defaulter_datatable').css('display', 'none');
        $('.cabinet_users_div').css('display', 'block');
       
       
        $('#user_table').dataTable().fnClearTable();
        $('#user_table').dataTable().fnDestroy();
   
  
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
                sort: false,
                pageLength: 50,
                scrollX: true,
                processing: false,
                drawCallback : function( ) {
                    hideOverlayLoader();
                },
                responsive: true,
                dom: 'Blfrtip',
                buttons:dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '33%', '33%', '33%'],  arraycolumn = [0,1,3],"{{__('app.user-list')}}"),
                lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                serverSide: true,

                ajax: {
                    url: '{{ URL("user/apply-filter-addresses") }}',
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
                        d.data_type = 'user-list';
                    },
                    success: function (response) {                   
                        var table = $('#user_table').DataTable();
                        table.ajax.reload();
                    }
                },

                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {data: 'user_name', name: 'user_name'},
                    {data: 'email', name: 'email'},
                   
                    // {data: 'address_english', name: 'address_english' },
                    // {data: 'city_id', name: 'city_id'},
                    // {data: 'phone_number', name: 'phone_number'},
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
                    // {
                    //     data: 'occupation_data',
                    //     name: 'occupation_data',
                    // },

                 

                ]
            }).on( 'length.dt', function () {
        }).on('page.dt', function () {
        }).on( 'order.dt', function () {
        }).on( 'search.dt', function () {
        });
    }


    function getDefaulterUsers(type='',tabClass='')
    {
        $('.defaulter_datatable').css('display', 'block');
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
                buttons:dataCustomizetbale("{{__('app.user-list-import')}}",  arrywidth= [ '33%', '33%', '33%'],  arraycolumn = [0,2,3],"{{__('app.user-list')}}"),
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
                    {data: 'name', name: 'name'},
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
