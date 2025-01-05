@if(!empty($namazTime))
<div class="timer-header">
    <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex flex-column">
            <p class="text-white "> {{$namazTime->date->readable }}</p>
            <p class="small-text text-center text-white">
                {{ ( new App\Http\Controllers\Hijri\HijriDateTime())->format('D _j _M _Yهـ'); }}
            </p>
        </div>
    </div>
</div>
<div class="timer-body">
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">
            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>
                <p>{{__('app.fajr')}}</p>
            </div>
            <div class="d-flex">
                <p class="namazTimePara" {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Fajr))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">
            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>
                <p>{{__('app.sunrise')}}</p>
            </div>

            <div class="d-flex">
                <p class="namazTimePara" {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Sunrise))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">
            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>
                <p>{{__('app.dhuhr')}}</p>
            </div>
            <div class="d-flex">
                <p class="namazTimePara"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Dhuhr))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">
            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>
                <p>{{__('app.asr')}}</p>
            </div>

            <div class="d-flex">
                <p class="namazTimePara"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Asr))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">

            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>

                <p {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{__('app.maghrib')}}</p>
            </div>
            <div class="d-flex">
                <p class="namazTimePara"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Maghrib))}}</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-lg-3 mb-2 namaz-time-row">
        <div class="inner-detail w-100 d-flex align-items-center justify-content-between namazDiv">

            <div class="d-flex align-items-center">
                <span class="prev-btn me-3">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </span>
                <p>{{__('app.isha')}}</p>
            </div>
            <div class="d-flex">
                <p class="namazTimePara"  {{ app()->getLocale()=="urdu"|| "arabic" ? 'style=direction:ltr':'' }}>{{date("g:i a", strtotime($namazTime->timings->Isha))}}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="timer-header">
    <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex flex-column">
            <p class="text-white ">Today {{ date('y') }}</p>
            <p class="small-text text-center text-white">
                No Time available
            </p>
        </div>
    </div>
</div>
@endif
<div class="salah-btn d-flex justify-content-center align-items-center mt-lg-2 mt-2">
    <button class="white-hover-bg theme-btn" id="btn_model_prayer" href="#monthly-model-div" role="button" onclick="getPrayerTime('monthly')">{{__('app.see-monthly-salah-timetable')}}</button>
</div>
