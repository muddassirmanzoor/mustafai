<script>
    $(function() {
    // sehar aftar time interval
        var x = setInterval(function() {
        var countDownDateaftar = $('#aftar_time').val();
        var countDownDatesehar = $('#sehar_time').val();
        var nowTime = new Date().getTime();
        countDownDateaftar = new Date(countDownDateaftar).getTime();
        if(countDownDateaftar < nowTime){
            // alert("sehar time")
            countDownDate = countDownDatesehar;
            $('#sehar-aftar').html('{{ __('app.prayer-seher') }}');
        }else{
            countDownDate = countDownDateaftar;
            $('#sehar-aftar').html('{{ __('app.prayer-aftar') }}');
        }
        var seharAftarTime=new Date(countDownDate).getTime();
      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = seharAftarTime - now;
      // Time calculations for hours, minutes and seconds
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)+1);
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      document.getElementById("prayer_counter").innerHTML=`<ul class="after-timer">
                    <li>
                        <h5 class="prayer-hours">${hours}</h5>
                        <span class="font-16">{{ __('app.hour') }}</span>
                    </li>
                    <li class="bold-colon">:</li>
                    <li>
                        <h5 class="prayer-min">${minutes}</h5>
                        <span class="font-16">{{__('app.min')}}</span>
                    </li>
                    <li class="bold-colon">:</li>
                    <li>
                        <h5 class="prayer-sec">${seconds}</h5>
                        <span class="font-16">{{ __('app.sec') }}</span>
                    </li>
                </ul>`;
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("prayer_counter").innerHTML = "Time complete!";
        // window.location.reload();
      }
    }, 1000);

    //highlight namaz
    var fajrTime = $('#fajr_time').val();
    var fajrFormattedTime=new Date(fajrTime).getTime();
    var today_fajr_time = $('#today_fajr_time').val();
    var todayfajrFormattedTime=new Date(today_fajr_time).getTime();
    var dhuhrTime = $('#dhuhr_time').val();
    var dhuhrFormattedTime=new Date(dhuhrTime).getTime();
    var asrTime = $('#asr_time').val();
    var asrFormattedTime=new Date(asrTime).getTime();
    var maghribTime = $('#maghrib_time').val();
    var maghribFormattedTime=new Date(maghribTime).getTime();
    var ishaTime = $('#isha_time').val();
    var ishaFormattedTime=new Date(ishaTime).getTime();

    var namazArr = { "fajr": fajrFormattedTime,'todayFajarTime':todayfajrFormattedTime, "dhuhr": dhuhrFormattedTime,'asr':asrFormattedTime,'maghrib':maghribFormattedTime,'isha':ishaFormattedTime};
    // console.log(arr['fajr']);

    // next prayer time interval
    var nextPrayerInterval = setInterval(function() {
        // var nowTime =new Date("DEC 30, 2022 11:37:25");
        // nowTime = nowTime.getTime()
        // console.log(nowTime.getTime());
        var nowTime = new Date().getTime();
        if(namazArr['todayFajarTime'] < nowTime && namazArr['dhuhr'] > nowTime){
            $('#fajr_id').addClass('active');
            $('#isha_id').removeClass('active')
            countDownPrayer=namazArr['dhuhr'];
        }else if(namazArr['dhuhr'] < nowTime && namazArr['asr'] >nowTime){
            $('#dhuhr_id').addClass('active');
            $('#fajr_id').removeClass('active');
            countDownPrayer=namazArr['asr'];
        }else if(namazArr['asr'] < nowTime && namazArr['maghrib'] >nowTime ){
            $('#asr_id').addClass('active');
            $('#dhuhr_id').removeClass('active');
            countDownPrayer=namazArr['maghrib'];
        }else if(namazArr['maghrib'] < nowTime && namazArr['isha'] >nowTime){
            $('#maghrib_id').addClass('active');
            $('#asr_id').removeClass('active');
            countDownPrayer=namazArr['isha'];
        }else{
            $('#isha_id').addClass('active');
            $('#maghrib_id').removeClass('active');
            countDownPrayer=namazArr['fajr'];
        }

        var nextPrayer=countDownPrayer;
      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = nextPrayer - now;
      // Time calculations for hours, minutes
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)+1);
      document.getElementById("next-prayer-time").innerHTML=`<span>{{ __('app.next-prayer-in') }}: ${hours}h ${minutes}m</span>`;

      //highlight nawafile
        var tahajjud_start_time = $('#tahajjud_start_time').val();
        var tahajjudStartFormattedTime=new Date(tahajjud_start_time).getTime();
        var tahajjud_end_time = $('#tahajjud_end_time').val();
        var tahajjudEndFormattedTime=new Date(tahajjud_end_time).getTime();
        var ishraq_start_time = $('#ishraq_start_time').val();
        var ishraqStartFormattedTime=new Date(ishraq_start_time).getTime();
        var ishraq_end_time = $('#ishraq_end_time').val();
        var ishraqEndFormattedTime=new Date(ishraq_end_time).getTime();
        var chasht_start_time = $('#chasht_start_time').val();
        var chashtStartFormattedTime=new Date(chasht_start_time).getTime();
        var chasht_end_time = $('#chasht_end_time').val();
        var chashtEndFormattedTime=new Date(chasht_end_time).getTime();
        var awwabin_start_time = $('#awwabin_start_time').val();
        var awwabinStartFormattedTime=new Date(awwabin_start_time).getTime();
        var awwabin_end_time = $('#awwabin_end_time').val();
        var awwabinEndFormattedTime=new Date(awwabin_end_time).getTime();

        var nawafilArr = { "tahajjud_start_time": tahajjudStartFormattedTime, "tahajjud_end_time": tahajjudEndFormattedTime,'ishraq_start_time':ishraqStartFormattedTime,'ishraq_end_time':ishraqEndFormattedTime,'chasht_start_time':chashtStartFormattedTime,'chasht_end_time':chashtEndFormattedTime,'awwabin_start_time':awwabinStartFormattedTime,'awwabin_end_time':awwabinEndFormattedTime};
        var nowNawafilTime = new Date().getTime();
        if(nawafilArr['tahajjud_start_time'] < nowNawafilTime && nawafilArr['tahajjud_end_time'] > nowNawafilTime){
            $('#tahajjud-time').addClass('active-namz');
        }else if(nawafilArr['ishraq_start_time'] < nowNawafilTime && nawafilArr['ishraq_end_time'] >nowNawafilTime){
            $('#ishraq-time').addClass('active-namz');
        }else if(nawafilArr['chasht_start_time'] < nowNawafilTime && nawafilArr['chasht_end_time'] >nowNawafilTime ){
            $('#chasht-time').addClass('active-namz');
        }else if(nawafilArr['awwabin_start_time'] < nowNawafilTime && nawafilArr['awwabin_end_time'] >nowNawafilTime){
            $('#awwabin-time').addClass('active-namz');
        }
      if (distance < 0) {
        clearInterval(nextPrayerInterval);
        document.getElementById("next-prayer-time").innerHTML = "Namaz Time!";
        // window.location.reload();
      }
    }, 1000);
});

</script>
