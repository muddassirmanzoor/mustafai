@php
    $date=date("d");
    $todayDate=$date-1;
@endphp
 <div class="monthly-pray-namz table-responsive">
    @if ($type=="monthly")
    <table class="table">
        <thead>
            <tr>
                <th style="direction: ltr;text-align: center;">{{__('app.day')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.fajr')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.sunrise')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.dhuhr')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.asr')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.maghrib')}}</th>
                <th style="direction: ltr;text-align: center;">{{__('app.isha')}}</th>

            <tr>
        </thead>
        <tbody>
            @foreach($namazTime as $key=>$val)
            <tr class="{{ $todayDate==$key ? 'active-namz-monthly' : ''}}">
                <td style="direction: ltr;text-align: center;">{{$val->date->readable}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Fajr))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Sunrise))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Dhuhr))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Asr))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Maghrib))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Isha))}}</td>

            </tr>
            @endforeach
        </tbody>

    </table>
    @elseif ($type=="nawafil")
    <table class="table">
        <thead>
            <tr>
                <th style="direction: ltr;text-align: center;">{{__('app.day')}}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.tahajjud') }}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.ishraq') }}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.chasht') }}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.awwabin') }}</th>
            <tr>
        </thead>
        <tbody>
            @foreach($namazTime as $key=>$val)
            <tr class="{{ $todayDate==$key ? 'active-namz-monthly' : ''}}">
                <td style="direction: ltr;text-align: center;">{{$val->date->readable}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime('23:00'))}} - {{date("g:i a", strtotime($val->timings->Fajr. '+ 1 day -5 minutes'))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Sunrise. ' +10 minutes'))}} - {{date("g:i a", strtotime($val->timings->Sunrise. ' +90 minutes'))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Sunrise. ' +90 minutes'))}} - {{date("g:i a", strtotime($val->timings->Dhuhr. ' -15 minutes'))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Maghrib. ' +10 minutes'))}} - {{date("g:i a", strtotime($val->timings->Isha. ' -10 minutes'))}}</td>

            </tr>
            @endforeach
        </tbody>

    </table>
    @elseif ($type=="sehar_aftar")
    <table class="table">
        <thead>
            <tr>
                <th style="direction: ltr;text-align: center;">{{__('app.day')}}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.seher') }}</th>
                <th style="direction: ltr;text-align: center;">{{ __('app.aftar') }}</th>
            <tr>
        </thead>
        <tbody>
            @foreach($namazTime as $key=>$val)
            <tr class="{{ $todayDate==$key ? 'active-namz-monthly' : ''}}">
                <td style="direction: ltr;text-align: center;">{{$val->date->readable}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Fajr. ' -2 minutes'))}}</td>
                <td style="direction: ltr;text-align: center;">{{date("g:i a", strtotime($val->timings->Maghrib. ' +1 minutes'))}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    @endif
</div>
