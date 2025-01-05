<!DOCTYPE html>
<html lang="en">

	<head>
        <meta charset="UTF-8">
        <meta name="description" content="MUustafai business booster">
        <meta name="keywords" content="HTML, CSS">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>MUustafai business booster</title>
        <style>
            body{margin: 0; padding: 0;font-family: Arial; background-color: #ffffff;}
       </style>
	</head>
	<body>
        <div id="invoice">
            <div class="report-parent business-palns-form" style="width:1200px;margin:0 auto;padding:5px 0 0;;background:#fff;margin-bottom: 5;direction: rtl;">
                <h2 style="width:100%;font-size:20px;font-weight:bold;text-align:center;margin-bottom: 5px;">مواخات فاؤنڈیشن</h2>
                <p style="width:100%;font-size:20px;font-weight:400;text-align:center;margin-bottom: 0;">درخواست فارم برائے بزنس بوسٹر</p>
                <table cellspacing="0" cellpadding="0" style="width:100%;">
                    <tbody>
                        <tr>
                            <td style="height:50px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;vertical-align: bottom;">
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:78%;vertical-align: bottom;">
                                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;">سیریل نمبر:</td>
                                                                            <td style="width:65%;font-size:14px;"><span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{$serial_number}}</span></td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;">تاریخ:</td>
                                                                            <td style="width:65%;font-size:14px;"><span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{date('d-m-Y',$def_date)}}</span></td>
                                                                            <td style="width:15%"></td>

                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;">رابطہ نمبر:</td>
                                                                            <td style="width:65%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_contact_number : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;">شناختی کارڈ: </td>
                                                                            <td style="width:65%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_nic_number : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;">نام :</td>
                                                                            <td style="width:65%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_full_name : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;vertical-align:bottom;">ولدیت یا زوجیت :</td>
                                                                            <td style="width:65%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_guardian_name : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;vertical-align:bottom;">کاروبار کی نوعیت :</td>
                                                                            <td style="width:65%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_business_coessentiality : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;vertical-align:bottom;">بزنس بوسٹر کی غرض :</td>
                                                                            <td style="width:65%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_plan_reason : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;vertical-align:bottom;">عارضی پتہ :</td>
                                                                            <td style="width:65%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_temp_address : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:25%;font-size:14px;vertical-align:bottom;">مستقل ایڈریس :</td>
                                                                            <td style="width:65%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_permanent_address : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td style="width:7%;"></td>
                                                            <td style="width:15%;text-align: center;vertical-align: top;">
                                                                <span style="display:inline-block;vertical-align:top;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100px" height="100px" src="{{ getS3File((isset($application->form_image)) ? $application->form_image:'./images/avatar.png') }}" alt="profile" class="img-fluid">
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:25px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <P style="width:100%;font-size:16px;font-weight:400;margin-bottom: 0;">
                    میں مواخات فاؤنڈیشن کے زیر انتظام چلنے والے "بزنس بوسٹر پروگرام " کے کلب <span> ( {{$plan->{'name_urdu'} }} ) </span> کا حصہ جس کا روزانہ کا مواخانہ مبلغ  <span> ({{$plan->invoice_amount}})</span>روپے کا حصہ بنا چاہ رہا ہوں۔ میں آپ کو یقین دلاتا ہوں کے میں سال کے 365 دن مواخانہ کی ادائیگی بر وقت کروں گا اور کسی قسم کے پس و پیش سے کام نہ لوں گا۔ میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کر وہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کوتاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہوگی ۔
                    دستخط درخواست دہندہ بمعہ نشان انگوٹھا
                </P>
                <table cellspacing="0" cellpadding="0" style="width:100%;">
                    <tbody>
                        <tr>
                            <td style="height:250px;"></td>
                        </tr>
                        <tr>
                            <td style="width:49%;"></td>
                            <td style="width:30%;font-size:14px;">
                                دستخط بمعہ نشان انگوٹھا: </td>
                            <td style="font-size:14px;width: 30%;border-bottom:1px solid #000;"></span></td>
                            <td style="width:1%;"></td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                    </tbody>
                </table>
                <div style="page-break-before:always">&nbsp;</div>
                <table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:18px;font-weight:bold;text-align:center;">خاندان سے ضامن</td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">نام</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[0])) ? $witnesses[0]->name : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">ولدیت یا زوجیت</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[0])) ? $witnesses[0]->guardian_name : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">شناختی کارڈ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[0])) ? $witnesses[0]->nic : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">رشتہ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[0])) ? $witnesses[0]->relation : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">کاروبار</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[0])) ? $witnesses[0]->business : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">رابطہ نمبر</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[0])) ? $witnesses[0]->contact_number : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size:15px;padding-left: 10px;padding-right: 10px;">میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کردہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کو تاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہو گی۔</td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:48%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: middle;text-align: center;">شناختی کارڈ اگلی سائیڈ</td>
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:bottom;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                                                </span>
                                                            </td>
                                                            <td style="width:15%;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:48%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;text-align: center;vertical-align: middle;">ناختی کارڈ پچھلی سائیڈ</td>
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:bottom;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                                                </span>
                                                            <td style="width:15%;"></td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:59%;"></td>
                                            <td style="width:20%;font-size:14px;">
                                                دستخط بمعہ نشان انگوٹھا: </td>
                                            <td style="font-size:14px;width: 20%;border-bottom:1px solid #000;"></td>
                                            <td style="width:1%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height:25px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:10px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}
                <table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:18px;font-weight:bold;text-align:center;"> بیرونی ضامن</td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">نام</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[1])) ? $witnesses[1]->name : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">ولدیت یا زوجیت</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[1])) ? $witnesses[1]->guardian_name : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">شناختی کارڈ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[1])) ? $witnesses[1]->nic : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>

                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">رشتہ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[1])) ? $witnesses[1]->relation : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">کاروبار</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[1])) ? $witnesses[1]->business : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">رابطہ نمبر
                                                            </td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($witnesses[1])) ? $witnesses[1]->contact_number : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;padding-left: 10px;padding-right: 10px;"> میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں ، اس بات کا یقین دلا تا / دلاتی ہوں کہ مواخانہ اوپر بیان کردہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں جتنی رقم واجب الادا ہو گی میں اپنے پاس سے جمع کرانے کا / کی ذمہ دار ہوں۔ نیز کسی بھی کوتاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہو گی۔ </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                        <tr>
                            {{-- <td style="font-size:16px;padding-left: 10px;padding-right: 10px;">
                                پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں
                            </td> --}}
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:48%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: middle;text-align: center;">شناختی کارڈ اگلی سائیڈ</td>
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:bottom;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                                            </span>
                                                            </td>
                                                            <td style="width:35%;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:48%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;text-align: center;vertical-align: middle;">ناختی کارڈ پچھلی سائیڈ</td>
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:bottom;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_back)) ? $witnesses[1]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                                            </span>
                                                            <td style="width:35%;"></td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                      <tr>
                                            <td style="width:59%;"></td>
                                            <td style="width:20%;font-size:14px;">
                                                دستخط بمعہ نشان انگوٹھا: </td>
                                            <td style="font-size:14px;width: 20%;border-bottom:1px solid #000;"></td>
                                            <td style="width:1%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height:25px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                    </tbody>
                </table>

                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}
                {{-- <tr>
                    <td> --}}
                        <table cellspacing="0" cellpadding="0" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:1%"></td>
                                    <td style="width:38%">
                                        <table cellspacing="0" cellpadding="0" style="width:100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:30%;font-size:14px;vertical-align: bottom;">دستخط مقامی زمہ دار</td>
                                                    <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width:1%"></td>
                                    <td style="width:20%">
                                    </td>
                                    <td style="width:1%"></td>
                                    <td style="width:38%">
                                        <table cellspacing="0" cellpadding="0" style="width:100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:30%;font-size:14px;vertical-align: bottom;">دستخط ضلعی زمہ دار</td>
                                                    <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width:1%;font-size:14px;"></td>
                                </tr>
                            </tbody>
                        </table>
                    {{-- </td>
                </tr> --}}
                <div style="page-break-before:always">&nbsp;</div>

                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}
                <table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:18px;font-weight:bold;text-align:center;">پرونوٹ</td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">نام</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->name:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">ولدیت یا زوجیت</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->guardian_name:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">شناختی کارڈ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->nic:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">ساکن</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($pronote)) ? $pronote->address:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                           <td style="width:25%;font-size:14px;vertical-align: bottom;">تحصیل</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($pronote)) ? $pronote->tehcil:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: bottom;">ضلع</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($pronote)) ? $pronote->district:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100%">
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="height:10px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:24%;font-size:14px;vertical-align: bottom;">کہ مبلغ</td>
                                            <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($pronote)) ? $pronote->amount:''  }}</td>
                                            <td style="width:1%;font-size:14px;vertical-align: bottom;"></td>
                                            <td style="width:4%;vertical-align: bottom;">روپے</td>
                                        </tr>
                                        <tr>
                                            <td style="height:10px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                           <td>
                            <table cellspacing="0" cellpadding="0">
                                <tbody>

                                    <tr>
                                        <td style="font-size:14px;vertical-align: bottom;padding-left: 5px;padding-right: 5px;">نصف جن کے <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->amount_half:''  }}</span>ہوتے ہیں۔ ازاں مواخات فاؤنڈیشن سے مواخانہ لے کر وعدہ کرتا ہوں کہ مبلغات بالا مع سروس چارج<span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->service_charges:''  }}</span>
                                            <span style="vertical-align:middle;"> شمار کر کے روزانہ / یکمشت عند الطلب مواخات فاؤنڈیشن یا جس کا حکم دیں، ادا کروں گا / گی۔ لہذا پر دونوٹ بقائمی ہوش و حواس لکھ دی ہے تاکہ سند رہے۔
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:20px;"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td style="width:1%"></td>
                                                        <td style="width:32%">
                                                            <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width:33%;font-size:14px;vertical-align: bottom;">العبد</td>
                                                                        <td style="width:1%"></td>
                                                                        <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($pronote)) ? $pronote->alabd:''  }}</td>
                                                                        <td style="width:2%;vertical-align: bottom;"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td style="width:1%"></td>
                                                        <td style="width:32%">
                                                            <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width:33%;font-size:14px;vertical-align: bottom;">دستخط بمعہ نشان انگوٹھا</td>
                                                                        <td style="width:1%"></td>
                                                                        <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;"></td>
                                                                        <td style="width:2%;vertical-align: bottom;"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td style="width:1%"></td>
                                                        <td style="width:32%">
                                                            <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width:33%;font-size:14px;vertical-align: bottom;">تاریخ</td>
                                                                        <td style="width:1%"></td>
                                                                        <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}</td>
                                                                        <td style="width:2%;vertical-align: bottom;"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td style="width:1%;font-size:14px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:35px;"></td>
                                    </tr>
                                </tbody>
                            </table>
                           </td>
                        </tr>
                    </tbody>
                </table>

                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}

                <table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:18px;font-weight:bold;text-align:center;">رسید پرونوٹ</td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;">باعث تحریر آنکہ</td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px;vertical-align: bottom;padding-left: 5px;padding-right: 5px;">جو کہ مبلغ <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->amount:''  }}</span>روپے نصف جن کے <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->amount_half:''  }}</span>روپے ہوتے ہیں بطور مواخانہ مواخات فاؤنڈیشن بزریعہ چیک نمبر
                            <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->check_number:''  }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px;vertical-align: bottom;padding-left: 5px;padding-right: 5px;">مورخہ<span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}</span>مالیتی <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->owner:''  }}</span>بینک <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->bank:''  }}</span> روبرو ضامنان زیل وصول پا لیےہیں۔ لہذاٰ یہ رسید وصولی کی لکھ دی ہے تاکہ سند رہے۔
                            </td>
                        </tr>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: bottom;">العبد</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($raseed_pronote)) ? $raseed_pronote->alabd:''  }}</td>
                                                            <td style="width:2%;vertical-align: bottom;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: bottom;">دستخط بمعہ نشان انگوٹھا</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;"></td>
                                                            <td style="width:2%;vertical-align: bottom;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: bottom;">تاریخ</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}</td>
                                                            <td style="width:2%;vertical-align: bottom;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%;font-size:14px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                    </tbody>
                </table>
                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}
                @php
                if(isset($application->zaminan_json)){
                    $arrayData = json_decode($application->zaminan_json, true);
                }
                @endphp
                <table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:18px;font-weight:bold;text-align:center;"> ضامنان</td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:35%;font-size:14px;vertical-align: bottom;font-size: 14px;">نام بمعہ ولدیت اور زوجیت</td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size: 14px;">{{ !empty($arrayData['zaminan_name_1']) ? $arrayData['zaminan_name_1'] : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:35%;font-size:14px;vertical-align: bottom;font-size: 14px;">شناختی کارڈ نمبر
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size: 14px;">
                                                                {{ !empty($arrayData['zaminan_cnic_1']) ? $arrayData['zaminan_cnic_1'] : '' }}
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:34%;font-size:14px;vertical-align: bottom;font-size: 14px;">دستخط</td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size: 14px;">{{ !empty($arrayData['zaminan_signature_1']) ? $arrayData['zaminan_signature_1'] : '' }}</td>
                                                            <td style="width:1%"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:35%;font-size:14px;vertical-align: bottom;font-size: 14px;">نام بمعہ ولدیت اور زوجیت
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">
                                                                {{ !empty($arrayData['zaminan_name_2']) ? $arrayData['zaminan_name_2'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:35%;font-size:14px;vertical-align: bottom;font-size: 14px;"> شناختی کارڈ نمبر
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">
                                                                {{ !empty($arrayData['zaminan_cnic_2']) ? $arrayData['zaminan_cnic_2'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:34%;font-size:14px;vertical-align: bottom;font-size: 14px;"> دستخط
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: bottom;">
                                                                {{ !empty($arrayData['zaminan_signature_2']) ? $arrayData['zaminan_signature_2'] : '' }}
                                                            </td>
                                                            <td style="width:1%"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"></td>
                        </tr>
                    </tbody>
                </table>
                {{-- <tr> --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </tr> --}}
            </div>
        </div>
	</body>
</html>
