<script>
    function updateProvince(_this, permanent = false) {

    let id =  $(_this).val();
    if (permanent) {
        $('#province_select_permanent').find('option').not(':first').remove();
    } else {
        $('#province_select').find('option').not(':first').remove();
    }
    $.ajax({
        type: "POST",
        url: "{{route('user.addresses.filter')}}",
        data: {'_token': "{{csrf_token()}}", country_id: id},
        dataType: "json",
        success: function(result) {
            if (result.status == 200) {
                for (var i = 0; i < result.total; i++) {
                    var id = result['data'][i].id;
                    var name =result['data'][i].name;

                    var option = "<option value='" + id + "'>" + name + "</option>";

                    permanent ? $("#province_select_permanent").append(option) : $("#province_select").append(option);
                }

                for (var i = 0; i < result.city_total; i++) {
                    var id = result['cities'][i].id;
                    var name =result['cities'][i].name;

                    var option = "<option value='" + id + "'>" + name + "</option>";

                    permanent ? $("#province_select_permanent").append(option) : $("#city_select").append(option);
                }
            }
        }

    });
}

function updateDivisions(_this, permanent = false) {

    let id =  $(_this).val();
    if (permanent) {
        $('#division_select_permanent').find('option').not(':first').remove();
        $('#city_select_permanent').find('option').not(':first').remove();
    } else {
        $('#division_select').find('option').not(':first').remove();
        $('#city_select').find('option').not(':first').remove();
    }
    $.ajax({
        type: "POST",
        url: "{{route('user.addresses.filter')}}",
        data: {'_token': "{{csrf_token()}}", province_id: id},
        dataType: "json",
        success: function(result) {
            if (result.status == 200) {
                for (var i = 0; i < result.total; i++) {

                    var id = result['data'][i].id;
                    var name =result['data'][i].name;
                    var option = "<option value='" + id + "'>" + name + "</option>";
                   permanent ?  $("#division_select_permanent").append(option) :  $("#division_select").append(option)
                }
                for (var i = 0; i < result.city_total; i++) {
                    var city_id = result['cities'][i].id;
                    var city_name =result['cities'][i].name;
                    var city_option = "<option value='" + city_id + "'>" + city_name + "</option>";
                    permanent ? $("#city_select_permanent").append(city_option) : $("#city_select").append(city_option)
                }
            }

        }

    });
}

function updateDistricts(_this, permanent = false) {

    let id =  $(_this).val();
    if (permanent) {
        $('#district_select_permanent').find('option').not(':first').remove();
    } else {
        $('#district_select').find('option').not(':first').remove();
    }
    $.ajax({
        type: "POST",
        url: "{{route('user.addresses.filter')}}",
        data: {'_token': "{{csrf_token()}}", division_id: id},
        dataType: "json",
        success: function(result) {
            if (result.status == 200) {
                for (var i = 0; i < result.total; i++) {
                    console.log(result['data'][i].name)
                    var id = result['data'][i].id;
                    var name =result['data'][i].name;

                    var option = "<option value='" + id + "'>" + name + "</option>";

                    permanent ? $("#district_select_permanent").append(option) : $("#district_select").append(option)
                }
            }
        }
    });
}

function updateTehsil(_this, permanent = false) {

    let id =  $(_this).val();
    if (permanent) {
        $('#tehsil_select_permanent').find('option').not(':first').remove();
    } else {
        $('#tehsil_select').find('option').not(':first').remove();
    }
    $.ajax({
        type: "POST",
        url: "{{route('user.addresses.filter')}}",
        data: {'_token': "{{csrf_token()}}", district_id: id},
        dataType: "json",
        success: function(result) {
            if (result.status == 200) {
                for (var i = 0; i < result.total; i++) {
                    console.log(result['data'][i].name)
                    var id = result['data'][i].id;
                    var name =result['data'][i].name;

                    var option = "<option value='" + id + "'>" + name + "</option>";

                   permanent ? $("#tehsil_select_permanent").append(option) :  $("#tehsil_select").append(option)
                }
            }
        }
    });
}

function updateZone(_this , permanent = false) {

    let id =  $(_this).val();
    if (permanent) {
        $('#zone_select_permanent').find('option').not(':first').remove();
    } else {
        $('#zone_select').find('option').not(':first').remove();
    }
    $.ajax({
        type: "POST",
        url: "{{route('user.addresses.filter')}}",
        data: {'_token': "{{csrf_token()}}", tehsil_id: id},
        dataType: "json",
        success: function(result) {
            if (result.status == 200) {
                for (var i = 0; i < result.total; i++) {
                    console.log(result['data'][i].name)
                    var id = result['data'][i].id;
                    var name =result['data'][i].name;

                    var option = "<option value='" + id + "'>" + name + "</option>";

                    permanent ? $("#zone_select_permanent").append(option) : $("#zone_select").append(option)
                }
                updateCouncils(_this, true);
            }
        }
    });
}

function updateCouncils(_this) {

    let id =  $(_this).val();
    $('#council_select').find('option').not(':first').remove();
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

                    $("#council_select").append(option);
                }
            }
        }
    });
}

$('#country_id').on('change', function () {
    let countryId = $(this).val()
    if (countryId == 1) {
        $('.division_select_div').css('display', 'block')
        $('.district_select_div').css('display', 'block')
        $('.tehsil_select_div').css('display', 'block')
        $('.zone_select_div').css('display', 'block')

    } else {
        $('.division_select_div').css('display', 'none')
        $('.district_select_div').css('display', 'none')
        $('.tehsil_select_div').css('display', 'none')
        $('.zone_select_div').css('display', 'none')
    }
    })

    $('#country_id_permanent').on('change', function () {
        let countryId = $(this).val()
        if (countryId == 1) {
            $('.division_select_div_permanent').css('display', 'block')
            $('.district_select_div_permanent').css('display', 'block')
            $('.tehsil_select_div_permanent').css('display', 'block')
            $('.zone_select_div_permanent').css('display', 'block')

        } else {
            $('.division_select_div_permanent').css('display', 'none')
            $('.district_select_div_permanent').css('display', 'none')
            $('.tehsil_select_div_permanent').css('display', 'none')
            $('.zone_select_div_permanent').css('display', 'none')
        }
    })

</script>
