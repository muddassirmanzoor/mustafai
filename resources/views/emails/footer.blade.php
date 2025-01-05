<tr>
<td valign="top">
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center" style="padding: 0">
{{-- {{ Illuminate\Mail\Markdown::parse($slot) }} --}}
<p class="text-center social-media footer-top-cell">

    <a target="_blank" href="{{getSettingDataHelper('facebook')}}"><img src="{{asset('/images/email-template/facebook.png')}}" alt="facebook"/></a>
    <a target="_blank" href="{{getSettingDataHelper('twitter')}}"><img src="{{asset('/images/email-template/twitter.png')}}" alt="twitter"/></a>
    <a target="_blank" href="{{getSettingDataHelper('linkedin')}}"><img src="{{asset('/images/email-template/linkedin.png')}}" alt="linkedIn" /> </a>
    <a target="_blank" href="{{getSettingDataHelper('youtube')}}"><img src="{{asset('/images/email-template/youtube.png')}}" alt="youtube"/></a>
    <a target="_blank" href="{{getSettingDataHelper('pinterest')}}"><img src="{{asset('/images/email-template/pinterest.png')}}" alt="pinterest"/></a>
    {{-- <a target="_blank" href="{{getSettingDataHelper('facebook')}}"><img src="{{$message->embed(asset('/images/email-template/facebook.png'))}}" alt="facebook"/></a>
    <a target="_blank" href="{{getSettingDataHelper('twitter')}}"><img src="{{$message->embed(asset('/images/email-template/twitter.png'))}}" alt="twitter"/></a>
    <a target="_blank" href="{{getSettingDataHelper('linkedin')}}"><img src="{{$message->embed(asset('/images/email-template/linkedin.png'))}}" alt="linkedIn" /> </a>
    <a target="_blank" href="{{getSettingDataHelper('youtube')}}"><img src="{{$message->embed(asset('/images/email-template/youtube.png'))}}" alt="youtube"/></a>
    <a target="_blank" href="{{getSettingDataHelper('pinterest')}}"><img src="{{$message->embed(asset('/images/email-template/pinterest.png'))}}" alt="pinterest"/></a> --}}


</p>
<div class="footer-text footer-cell">
    <p>Copyright © {{ date('Y')}} Mustafai. All rights reserved.</p>
</div>
</td>
</tr>
</table>
</td>
</tr>
