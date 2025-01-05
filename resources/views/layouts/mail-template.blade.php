<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title></title>
        <!--font awsome-->
        <link href="{{ asset('assets/home/css/font-awesome.min.css') }}" rel="stylesheet" />
    <style type="text/css" media="screen">
        /* /  Force Hotmail to display emails at full width  / */
        .ExternalClass {
            display: block !important;
            width: 100%;
        }
       /* / Force Hotmail to display normal line spacing  / */
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
            text-align:left;
        }
        body,
        p,
        td {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333333;
            line-height: 1.5em;
        }
        h1 {
            font-size: 24px;
            font-weight: normal;
            line-height: 24px;
        }
        body,
        p {
            margin-bottom: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        img {
            line-height: 100%;
            outline: none;
            text-decoration: none;
            margin-top:10px;
            -ms-interpolation-mode: bicubic;
        }
        a img {
            border: none;
        }
        .background {
            background-color: #f4f5f5;
        }
        table.background {
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
        .block-img {
            display: block;
            line-height: 0;
        }
        a {
            color: white;
            text-decoration: none;
        }
        a,
        a:link {
            color: #2A5DB0;
            text-decoration: underline;
        }
        table td {
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }
        .wrap {
            width: 700px;
        }
        .wrap-cell {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .header-cell,
        .body-cell,
        .footer-cell {
            padding-left: 20px;
            padding-right: 20px;
        }
        .header-cell {
            background-color: #fff;
            font-size: 24px;
            color: #ffffff;
            text-align: center;
            padding:15px;
        }
        .header-cell img{ max-height:50px;}
        .body-cell {
            background-color: #ffffff;
            padding-top: 30px;
            padding-bottom: 34px;
        }
        .footer-cell {
            background-color: #32a337;
            text-align: center;
            font-size: 13px;
            padding-top: 15px;
            padding-bottom: 15px;
            color: #fff;
        }
        .footer-top-cell{
            background-color: #d3f4d4;
            text-align: center;
            font-size: 13px;
            padding-top: 15px;
            padding-bottom: 15px;
            color: #fff;
            justify-content: center;
            align-items:center;
            margin: 0 auto;
            display: table;
            width: 100%;
        }
        p.footer-top-cell{
            text-align:center;
            width: 100%;
            display: block;
        }
        .card {
            width: 400px;
            margin: 0 auto;
        }
        .data-heading {
            text-align: right;
            padding: 10px;
            background-color: #ffffff;
            font-weight: bold;
        }
        .data-value {
            text-align: left;
            padding: 10px;
            background-color: #ffffff;
        }
        .force-full-width {
            width: 100% !important;
        }
        .im {
            color: #333333 !important;
        }

        .footer-text{ color:#fff; text-align:center; font-size:12px; }
        /*width: 100%;*/
        .footer-text a, .footer-text p {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .footer-text p {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .social-media a{
            color: #fff;
            width: 30px;
            height: 30px;
            text-align: center;
            border: 1px solid #32a337;
            border-radius: 50%;
            display: inline-block;
            margin: 5px 4px 5px 0;
            font-size: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            display: inline-block;
        }
        .social-media a img{
            margin: 0px !important;
            width: 18px;
            padding: 5px;
        }
        .footer-img {
            float: right;
            width: 70px;
        }
        .social-media {
            display: flex;
            justify-content: center;

        }
        .social-media a > img {
    /* margin: 0px !important; */
            width: 18px;
            padding: 5px;
            margin: 0 auto !important;
            height: 18px;
            vertical-align: bottom;
        }
    </style>
    <style type="text/css" media="only screen and (max-width: 700px)">
        @media only screen and (max-width: 700px) {
            table[class="card"] {
                width: auto !important;
            }

            td[class="data-heading"],
            td[class="data-value"] {
                display: block !important;
            }

            td[class="data-heading"] {
                text-align: left !important;
                padding: 10px 10px 0;
            }

            table[class="wrap"] {
                width: 100% !important;
            }

            td[class="wrap-cell"] {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }
    </style>

</head>
{{-- {{dd($details);}} --}}
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="" class="background">
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" class="background">
        <tr>
            <td align="center" valign="top" width="100%" class="background">
        <center>
            <table cellpadding="0" cellspacing="0" width="700" class="wrap">
                <tr>
                    <td valign="top" class="wrap-cell" style="padding-top:30px; padding-bottom:30px;">
                        <table cellpadding="0" cellspacing="0" class="force-full-width">
                            <tr>
                                <td height="60" valign="top" class="header-cell" >
                                    <img alt="" class="img-responsive" src="{{asset('assets/home/images/site-logo.png')}}" />


                                </td>
                            </tr>
                            <tr>
                                <td valign="top" class="body-cell">
                                    <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
                                        <tr>
                                            <td valign="top" style="padding-bottom:20px; background-color:#ffffff;">
                                                <!-- message go there --> <h3> Dear {{$user_name}},</h3>
                                                @if ( isset($content))
                                                    {!!  $content , $links !!}
                                                @endif

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" class="body-cell">
                                    <b>Best Regards,</b>
                                    <p>Team Mustafai</p>
                                    <p><a href="https://mustafai.pk/">mustafai.pk</a></p>
                                </td>
                            </tr>
                            <tr>

                                <td valign="top">
                                    <p class="text-center social-media footer-top-cell">
                                        {{-- @if(!empty(getSettingDataHelper('facebook'))) --}}
                                        <a target="_blank" href="{{getSettingDataHelper('facebook')}}"><img src="{{asset('/images/email-template/facebook.png')}}" alt="facebook"/></a>
                                        {{-- @endif --}}
                                        {{-- @if(!empty(getSettingDataHelper('twitter'))) --}}
                                        <a target="_blank" href="{{getSettingDataHelper('twitter')}}"><img src="{{asset('/images/email-template/twitter.png')}}" alt="twitter"/></a>
                                        {{-- @endif --}}
                                        {{-- @if(!empty(getSettingDataHelper('linkedin'))) --}}
                                        <a target="_blank" href="{{getSettingDataHelper('linkedin')}}"><img src="{{asset('/images/email-template/linkedin.png')}}" alt="linkedIn"/></a>
                                        {{-- @endif --}}
                                        {{-- @if(!empty(getSettingDataHelper('youtube'))) --}}
                                        <a target="_blank" href="{{getSettingDataHelper('youtube')}}"><img src="{{asset('/images/email-template/youtube.png')}}" alt="youtube"/></a>
                                        <a target="_blank" href="{{getSettingDataHelper('pinterest')}}"><img src="{{asset('/images/email-template/pinterest.png')}}" alt="pinterest"/></a>
                                        {{-- @endif --}}
                                    </p>
                                    <div class="footer-text footer-cell">
                                        <p>Copyright ©2023 Mustafai. All rights reserved.</p>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </td>
</tr>
</table>
</body>
</html>
