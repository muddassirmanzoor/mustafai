@if(!empty($namazTime))
{{-- hidden namaz time --}}
<input type="hidden" name="aftar_time" id="aftar_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Maghrib. ' +1 minutes'))}}">
<input type="hidden" name="sehar_time" id="sehar_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Fajr. '+ 1 day -2 minutes'))}}">
<input type="hidden" name="fajar_time" id="fajr_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Fajr.'+ 1 day'))}}">
<input type="hidden" name="today_fajr_time" id="today_fajr_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Fajr))}}">
<input type="hidden" name="dhuhr_time" id="dhuhr_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Dhuhr))}}">
<input type="hidden" name="asr_time" id="asr_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Asr))}}">
<input type="hidden" name="maghrib_time" id="maghrib_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Maghrib))}}">
<input type="hidden" name="isha_time" id="isha_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Isha))}}">

{{-- hidden nawafil time --}}
<input type="hidden" name="tahajjud_start_time" id="tahajjud_start_time"  value="{{date("M d, Y H:i:s", strtotime('23:00'))}}">
<input type="hidden" name="tahajjud_end_time" id="tahajjud_end_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Fajr. '+ 1 day -5 minutes'))}}">
<input type="hidden" name="ishraq_start_time" id="ishraq_start_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Sunrise. ' +10 minutes'))}}">
<input type="hidden" name="ishraq_end_time" id="ishraq_end_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Sunrise. ' +90 minutes'))}}">
<input type="hidden" name="chasht_start_time" id="chasht_start_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Sunrise. ' +90 minutes'))}}">
<input type="hidden" name="chasht_end_time" id="chasht_end_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Dhuhr. ' -15 minutes'))}}">
<input type="hidden" name="awwabin_start_time" id="awwabin_start_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Maghrib. ' +10 minutes'))}}">
<input type="hidden" name="awwabin_end_time" id="awwabin_end_time"  value="{{date("M d, Y H:i:s", strtotime($namazTime->timings->Isha. ' -10 minutes'))}}">

{{-- parayer widget --}}
<div class="timer-header">
    <div class="d-flex align-items-center namz-header-content">
        <div class="d-flex flex-column">
            <p class="text-white calendar-date"> {{$namazTime->date->readable }}</p>
            <p class="small-text text-center text-white" style="direction: ltr !important">
                {{ $namazTime->date->hijri->year }} {{ $namazTime->date->hijri->weekday->ar }}  {{ date("d",strtotime($namazTime->date->hijri->date))}} {{ $namazTime->date->hijri->month->ar }}
                {{-- {{ ( new App\Http\Controllers\Hijri\HijriDateTime())->format('D _j _M _Yهـ'); }} --}}
            </p>
        </div>
        <div class="d-flex">
            <div class="namz-wdg-logo">
                <img src="{{asset('assets/home/images/namz-logo.png')}}" alt="image not found" class="img-fluid" />
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="img-sun-for-time d-flex flex-column align-items-center">
                <img src="{{asset('assets/home/images/morning-sun.png')}}" alt="image not found" class="img-fluid" />
                <p class="size-13 text-white text-center" id="today-sunrise" style="direction: ltr;">{{date("g:i a", strtotime($namazTime->timings->Sunrise))}}</p>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="img-sun-for-time d-flex flex-column align-items-center">
                <img src="{{asset('assets/home/images/evening-sun.png')}}" alt="image not found" class="img-fluid" />
                <p class="size-13 text-white text-center" id="today-sunset" style="direction: ltr;">{{date("g:i a", strtotime($namazTime->timings->Sunset))}}</p>
            </div>
        </div>
    </div>
</div>
<div class="timer-body">
    <div class="d-flex justify-content-between">
        <p class="text-white calendar-date"> {{ __('app.prayers-time') }}</p>
        <p class="text-white green-time-box" id="next-prayer-time"></p>
    </div>
    <div class="d-flex justify-content-between namz-timetable-row">
        <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv" id="fajr_id">
                <p class="opacity-1 text-center">{{__('app.fajr')}}</p>
                <div class="moon-img-container">
                    <img src="{{asset('assets/home/images/fajr-moon.png')}}" alt="image not found" class="img-fluid" />
                </div>
                <p class="namazTimePara text-center" {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Fajr))}}</p>
            </div>
        </div>
        <!-- <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv">
                <p class="opacity-1">{{__('app.sunrise')}}</p>
                <p class="namazTimePara" {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Sunrise))}}</p>
            </div>
        </div> -->
        <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv" id="dhuhr_id">
                <p class="opacity-1 text-center">{{__('app.dhuhr')}}</p>
                <div class="moon-img-container">
                    <img src="{{asset('assets/home/images/asar-moon.png')}}" alt="image not found" class="img-fluid" />
                </div>
                <p class="namazTimePara text-center"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Dhuhr))}}</p>
            </div>
        </div>
        <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv" id="asr_id">
                <p class="opacity-1 text-center">{{__('app.asr')}}</p>
                <div class="moon-img-container">
                    <img src="{{asset('assets/home/images/asar-moon.png')}}" alt="image not found" class="img-fluid" />
                </div>
                <p class="namazTimePara text-center"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Asr))}}</p>
            </div>
        </div>
        <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv" id="maghrib_id">
                <p class="opacity-1 text-center" {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{__('app.maghrib')}}</p>
                <div class="moon-img-container">
                    <img src="{{asset('assets/home/images/magrib-moon.png')}}" alt="image not found" class="img-fluid" />
                </div>
                <p class="namazTimePara text-center"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Maghrib))}}</p>
            </div>
        </div>
        <div class="d-flex namaz-time-row">
            <div class="inner-detail namazDiv" id="isha_id">
                <p class="opacity-1 text-center">{{__('app.isha')}}</p>
                <div class="moon-img-container">
                    <img src="{{asset('assets/home/images/isha-moon.png')}}" alt="image not found" class="img-fluid" />
                </div>
                <p class="namazTimePara text-center"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Isha))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end  mt-lg-1 mt-2 mb-lg-1 mb-2 ">
        <div class="salah-btn see-monthly-time-table">
            <button class="" id="btn_model_prayer" href="#monthly-model-div" role="button" onclick="getPrayerTime('monthly')">{{ __('app.monthly-prayer-times') }}</button>
        </div>
    </div>
    <div class="nawafil-prayers table-responsive">
        <table class="table table-borderless" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="font-18 text-white">{{ __('app.nawafil-prayers') }}</th>
                    <th class="text-green font-16 text-center">{{ __('app.prayer-start-time') }}</th>
                    <th class="text-green font-16 text-center">{{ __('app.prayer-end-time') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr id="tahajjud-time">
                    <td>{{ __('app.tahajjud') }}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime('23:00'))}}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Fajr. '+ 1 day -5 minutes'))}}</td>
                </tr>
                <tr id="ishraq-time">
                    <td>{{ __('app.ishraq') }}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Sunrise. ' +10 minutes'))}}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Sunrise. ' +90 minutes'))}}</td>
                </tr>
                <tr id="chasht-time">
                    <td>{{ __('app.chasht') }}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Sunrise. ' +90 minutes'))}}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Dhuhr. ' -15 minutes'))}}</td>
                </tr>
                <tr id="awwabin-time">
                    <td>{{ __('app.awwabin') }}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Maghrib. ' +10 minutes'))}}</td>
                    <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($namazTime->timings->Isha. ' -10 minutes'))}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end  mt-lg-1 mt-2 mb-lg-2 mb-2 ">
        <div class="salah-btn see-monthly-time-table">
            <button class="e" id="btn_model_prayer" href="#monthly-model-div" role="button" onclick="getPrayerTime('nawafil')">{{ __('app.monthly-prayer-times') }}</button>
        </div>
    </div>
    <div class="aftar-time-info">
        <div class="aftar-time-info-header">
           <div class="row">
            <div class="col-lg-7 d-flex flex-column justify-content-center">
                <p class="font-16">{{$city }}, {{ $country}}</p>
                <p class="font-16" style="direction: ltr;">{{ date("d",strtotime($namazTime->date->hijri->date))}} <sup>th</sup> {{ $namazTime->date->hijri->month->en }}</p>
            </div>
            <div class="col-lg-5 d-flex align-items-center justify-content-between sehr-time">
               <div class="d-flex flex-column">
                    <p class="font-16">{{ __('app.SEHER') }}</p>
                    <p class="font-16" style="direction: ltr;">{{date("g:i a", strtotime($namazTime->timings->Fajr. ' -2 minutes'))}}</p>
               </div>
               <div class="d-flex">
                    <div class="moon-img-container">
                        <img src="{{asset('assets/home/images/fajr-moon.png')}}" alt="image not found" class="img-fluid" />
                    </div>
               </div>
            </div>
           </div>
        </div>
        <div class="after-time-info-body">
           <div class="row align-items-center">
           <div class="col-lg-4">
                <p class="font-16">{{ __('app.time-left') }} <span id="sehar-aftar"></span></p>
            </div>
            <div class="col-lg-8">
                <!-- <p id="prayer_counter"></p> -->
                <div id="prayer_counter" style=" direction: ltr">

                </div>
            </div>
           </div>
        </div>
    </div>
    <div class="d-flex justify-content-end  mt-lg-1 mt-2 mb-lg-3 mb-2 ">
        <div class="salah-btn see-monthly-time-table">
            <button class="e" id="btn_model_prayer" href="#monthly-model-div" role="button" onclick="getPrayerTime('sehar_aftar')">{{ __('app.seher-or-aftaer') }}</button>
        </div>
    </div>
</div>
@else
<div class="timer-header">
    <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex flex-column">
            <p class="text-white ">Today {{ date('y') }}</p>
            <p class="small-text text-center text-white">
                {{ __('app.no-time-available') }}
            </p>
        </div>
    </div>
</div>
@endif
{{-- <div class="salah-btn d-flex justify-content-center align-items-center mt-lg-2 mt-2">
    <button class="white-hover-bg theme-btn" id="btn_model_prayer" href="#monthly-model-div" role="button" onclick="getPrayerTime('monthly')">{{__('app.see-monthly-salah-timetable')}}</button>
</div> --}}
@include('home.scripts.prayer-countdown-script')
