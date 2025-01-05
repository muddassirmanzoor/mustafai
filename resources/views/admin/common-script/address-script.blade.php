<script>
    function adminUpdateProvince(_this) {
        let id =  $(_this).val();
        $('#province_select').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('admin.cabinet-address')}}",
            data: {'_token': "{{csrf_token()}}", country_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#province_select").append(option);
                    }
                }
            }

        });
    }

    function adminUpdateDivisions(_this) {

        let id =  $(_this).val();
        $('#division_select').find('option').not(':first').remove();
        $('#city_select').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('admin.cabinet-address')}}",
            data: {'_token': "{{csrf_token()}}", province_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {

                        var id = result['data'][i].id;
                        var name =result['data'][i].name;
                        var option = "<option value='" + id + "'>" + name + "</option>";
                        $("#division_select").append(option)
                    }
                    for (var i = 0; i < result.city_total; i++) {
                        var city_id = result['cities'][i].id;
                        var city_name =result['cities'][i].name;
                        var city_option = "<option value='" + city_id + "'>" + city_name + "</option>";
                        $("#city_select").append(city_option)
                    }
                }

            }

        });
    }
    function adminUpdateDistricts(_this) {

        let id =  $(_this).val();
        $('#district_select').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('admin.cabinet-address')}}",
            data: {'_token': "{{csrf_token()}}", division_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {
                        console.log(result['data'][i].name)
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#district_select").append(option)
                    }
                }
            }
        });
    }

    function adminUpdateTehsil(_this) {

        let id =  $(_this).val();
        $('#tehsil_select').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('admin.cabinet-address')}}",
            data: {'_token': "{{csrf_token()}}", district_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {
                        console.log(result['data'][i].name)
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#tehsil_select").append(option)
                    }
                }
            }
        });
    }

    function adminUpdateZone(_this) {

        let id =  $(_this).val();
        $('#zone_select').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('admin.cabinet-address')}}",
            data: {'_token': "{{csrf_token()}}", tehsil_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {
                        console.log(result['data'][i].name)
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#zone_select").append(option)
                    }
                    updateCouncils(_this, true);
                }
            }
        });
    }

    function adminUpdateCouncils(_this) {
        let id =  $(_this).val();
        $('#union_council_id').find('option').not(':first').remove();
        $.ajax({
            type: "POST",
            url: "{{route('user.addresses.filter')}}",
            data: {'_token': "{{csrf_token()}}", zone_id: id},
            dataType: "json",
            success: function(result) {
                if (result.status == 200) {
                    for (var i = 0; i < result.total; i++) {
                        var id = result['data'][i].id;
                        var name =result['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";

                        $("#union_council_id").append(option);
                    }
                }
            }
        });
    }
    //Show Hide hierarchy of cabinet address by clicking cabinet level

    $('#country_level').click(function() {
    if ($(this).is(':checked')) {
        $('#country_div').show();
        $('#province_div').hide();
        $('#division_div').hide();
        $('#district_div').hide();
        $('#city_div').hide();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
        }
    });
    $('#province_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').hide();
            $('#district_div').hide();
            $('#city_div').hide();
            $('#tehsil_div').hide();
            $('#zone_div').hide();
            $('#union_council_div').hide();
        }
    });
    $('#division_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').hide();
            $('#city_div').hide();
            $('#tehsil_div').hide();
            $('#zone_div').hide();
            $('#union_council_div').hide();
        }
    });
    $('#district_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').show();
            $('#city_div').hide();
            $('#tehsil_div').hide();
            $('#zone_div').hide();
            $('#union_council_div').hide();
        }
    });
    $('#city_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').hide();
            $('#city_div').show();
            $('#tehsil_div').hide();
            $('#zone_div').hide();
            $('#union_council_div').hide();
        }
    });
    $('#tehsil_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').show();
            $('#city_div').show();
            $('#tehsil_div').show();
            $('#zone_div').hide();
            $('#union_council_div').hide();
        }
    });
    $('#branch_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').show();
            $('#city_div').show();
            $('#tehsil_div').show();
            $('#zone_div').show();
            $('#union_council_div').hide();
        }
    });
    $('#union_counsil_level').click(function() {
        if ($(this).is(':checked')) {
            $('#country_div').show();
            $('#province_div').show();
            $('#division_div').show();
            $('#district_div').show();
            $('#city_div').show();
            $('#tehsil_div').show();
            $('#zone_div').show();
            $('#union_council_div').show();
        }
    });

    //Show Hide hierarchy of cabinet address on load page

    if ($('#country_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').hide();
        $('#division_div').hide();
        $('#district_div').hide();
        $('#city_div').hide();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#province_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').hide();
        $('#district_div').hide();
        $('#city_div').hide();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#division_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').hide();
        $('#city_div').hide();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#district_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').show();
        $('#city_div').hide();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#city_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').hide();
        $('#city_div').show();
        $('#tehsil_div').hide();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#tehsil_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').show();
        $('#city_div').show();
        $('#tehsil_div').show();
        $('#zone_div').hide();
        $('#union_council_div').hide();
    }
    if ($('#branch_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').show();
        $('#city_div').show();
        $('#tehsil_div').show();
        $('#zone_div').show();
        $('#union_council_div').hide();
    }
    if ($('#union_counsil_level').is(':checked')) {
        $('#country_div').show();
        $('#province_div').show();
        $('#division_div').show();
        $('#district_div').show();
        $('#city_div').show();
        $('#tehsil_div').show();
        $('#zone_div').show();
        $('#union_council_div').show();
    }

</script>
