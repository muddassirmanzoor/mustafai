<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>MUustafai business booster</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<style>
			.preloader {
				position: relative;
				display: block;
				background-color: #f4f6f9;
				height: 100vh;
				width: 100%;
				transition: height 200ms linear;
				position: fixed;
				left: 0;
				top: 0;
				z-index: 9999;
				opacity: 0.9;
			}
			.preloader img{
				position:absolute;
				top:20%;
				left:45%
			}
		</style>
	</head>
	<body style="background:#f7f5f5;">
		 <!-- Preloader -->
		 <div class="preloader flex-column justify-content-center align-items-center">
			<img class="animation__shake" src="{{ asset('assets/admin/dist/img/pre-loader.png') }}" alt="Mustafai Dashboard">
		</div>
        <div id="invoice">
            <div class="report-parent business-palns-form" style="width:1090px;margin:0 auto;padding:5px 0 0;padding:10px 15px;background:#fff;margin-top: 5;direction: rtl;">
                <h2 style="width:100%;font-size:20px;font-weight:bold;text-align:center;margin-bottom: 5px;">مواخات فاؤنڈیشن</h2>
                <p style="width:100%;font-size:20px;font-weight:400;text-align:center;margin-top: 0;">درخواست فارم برائے بزنس بوسٹر</p>
                <table cellspacing="0" cellpadding="0" style="width:100%;">
                    <tbody>
                        <tr>
                            <td style="width:2%;vertical-align: top;"></td>
                            <td style="width:98%;vertical-align: top;">
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:58%;vertical-align: top;">
                                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width:15%;font-size:14px;">سیریل نمبر:</td>
                                                                            <td style="width:70%;font-size:14px;"><span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{$serial_number}}</span></td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:15%;font-size:14px;">تاریخ:</td>
                                                                            <td style="width:70%;font-size:14px;"><span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{date('d-m-Y',$def_date)}}</span></td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:15%;font-size:14px;">رابطہ نمبر:</td>
                                                                            <td style="width:70%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_contact_number : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;">شناختی کارڈ: </td>
                                                                            <td style="width:70%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_nic_number : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;">نام :</td>
                                                                            <td style="width:70%;font-size:14px;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_full_name : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;vertical-align:top;">ولدیت یا زوجیت :</td>
                                                                            <td style="width:70%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_guardian_name : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;vertical-align:top;">کاروبار کی نوعیت :</td>
                                                                            <td style="width:70%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_business_coessentiality : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;vertical-align:top;">بزنس بوسٹر کی غرض :</td>
                                                                            <td style="width:70%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_plan_reason : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:10%;font-size:14px;vertical-align:top;">عارضی پتہ :</td>
                                                                            <td style="width:70%;font-size:14px;">
                                                                                <span style="width:100%;border-bottom:1px solid #ced4da;display:inline-block;vertical-align:bottom;">{{(!empty($application))?$application->form_temp_address : ''}}</span>
                                                                            </td>
                                                                            <td style="width:15%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="height:15px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:15%;font-size:14px;vertical-align:top;">مستقل ایڈریس :</td>
                                                                            <td style="width:70%;font-size:14px;">
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
                                                                <img width="100" height="100" src="{{ asset((isset($application->form_image)) ? $application->form_image:'./images/avatar.png') }}" alt="profile" class="img-fluid">
                                                                </span>
                                                            </td>
                                                            <td style="width:20%;"></td>
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
                <P style="width:100%;font-size:16px;font-weight:400;margin-top: 0;">
                    میں مواخات فاؤنڈیشن کے زیر انتظام چلنے والے "بزنس بوسٹر پروگرام " کے کلب <span> ( {{$plan->{'name_urdu'} }} ) </span> کا حصہ جس کا روزانہ کا مواخانہ مبلغ  <span> ({{$plan->invoice_amount}})</span>روپے کا حصہ بنا چاہ رہا ہوں۔ میں آپ کو یقین دلاتا ہوں کے میں سال کے 365 دن مواخانہ کی ادائیگی بر وقت کروں گا اور کسی قسم کے پس و پیش سے کام نہ لوں گا۔ میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کر وہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کوتاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہوگی ۔
                    دستخط درخواست دہندہ بمعہ نشان انگوٹھا
                </P>
                <table cellspacing="0" cellpadding="0" style="width:100%;">
                    <tbody>
                        <tr>
                            <td style="width:68%;"></td>
                            <td style="width:12%;font-size:14px;">
                                دستخط بمعہ نشان انگوٹھا: </td>
                            <td style="font-size:14px;width: 20%;border-bottom:1px solid #000;"></span></td>
                        </tr>
                        <tr>
                            <td style="height:25px;"></td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="0" style="width:100%;border:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;">خاندان سے ضامن</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">نام</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">ولدیت یا زوجیت</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">شناختی کارڈ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($witnesses[0])) ? $witnesses[0]->nic : '' }}</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">رشتہ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[0])) ? $witnesses[0]->relation : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">کاروبار</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[0])) ? $witnesses[0]->business : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">رابطہ نمبر</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[0])) ? $witnesses[0]->contact_number : '' }}</td>
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
                            <td style="font-size:16px;padding-left: 10px;padding-right: 10px;">میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کردہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کو تاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہو گی۔</td>
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
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:top;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ asset( (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
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
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:top;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ asset( (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
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
                                            <td style="width:68%;"></td>
                                            <td style="width:12%;font-size:14px;">
                                                دستخط بمعہ نشان انگوٹھا: </td>
                                            <td style="font-size:14px;width: 20%;border-bottom:1px solid #000;"></td>
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
                <span style="width:100%;display:block;page-break-before: always;"></span>
                <tr>
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
                <table cellspacing="0" cellpadding="0" style="width:100%;border:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;"> بیرونی ضامن</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">نام</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">ولدیت یا زوجیت</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">شناختی کارڈ</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">رشتہ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[1])) ? $witnesses[1]->relation : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">کاروبار</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[1])) ? $witnesses[1]->business : '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">رابطہ نمبر
                                                            </td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($witnesses[1])) ? $witnesses[1]->contact_number : '' }}</td>
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
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:top;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ asset( (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
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
                                                            <td style="width:40%;border-bottom:1px solid #000;text-align: center;"><span style="display:inline-block;vertical-align:top;border:1px solid #ced4da;padding:45px 15px;">
                                                                <img width="100" height="100" src="{{ asset( (isset($witnesses[1]) && !empty($witnesses[1]->nic_back)) ? $witnesses[1]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
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
                            <td style="height:25px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:68%;"></td>
                                            <td style="width:12%;font-size:14px;">
                                                دستخط بمعہ نشان انگوٹھا: </td>
                                            <td style="font-size:14px;width: 20%;border-bottom:1px solid #000;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height:25px;"></td>
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
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:1%"></td>
                                    <td style="width:38%">
                                        <table cellspacing="0" cellpadding="0" style="width:100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:30%;font-size:14px;vertical-align: top;">دستخط مقامی زمہ دار</td>
                                                    <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;"></td>
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
                                                    <td style="width:30%;font-size:14px;vertical-align: top;">دستخط ضلعی زمہ دار</td>
                                                    <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;"></td>
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
                <span style="width:100%;display:block;page-break-before: always;"></span>
                <tr>
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:25px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </tr>

                <table cellspacing="0" cellpadding="0" style="width:100%;border:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;">پرونوٹ</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">نام</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">ولدیت یا زوجیت</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">شناختی کارڈ</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->nic:''  }}</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">ساکن</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? $pronote->address:''  }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">تحصیل</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? $pronote->tehcil:''  }}</td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">ضلع</td>
                                                            <td style="width:75%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? $pronote->district:''  }}</td>
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
                                                            <td style="width:25%;font-size:14px;vertical-align: top;">کہ مبلغ</td>
                                                            <td style="width:70%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? $pronote->amount:''  }}</td>
                                                            <td style="width:1%;font-size:14px;vertical-align: top;"></td>
                                                            <td style="width:4%;vertical-align: top;">روپے</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;font-size:14px;vertical-align: top;"></td>
                                                            <td style="width:75%;font-size:14px;vertical-align: top;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:25%;"></td>
                                                            <td style="width:75%"></td>
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
                            <td style="font-size:14px;vertical-align: top;padding-left: 5px;padding-right: 5px;">نصف جن کے <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->amount_half:''  }}</span>ہوتے ہیں۔ ازاں مواخات فاؤنڈیشن سے مواخانہ لے کر وعدہ کرتا ہوں کہ مبلغات بالا مع سروس چارج<span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($pronote)) ? $pronote->service_charges:''  }}</span>
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
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">العبد</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? $pronote->alabd:''  }}</td>
                                                            <td style="width:2%;vertical-align: top;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">دستخط بمعہ نشان انگوٹھا</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;"></td>
                                                            <td style="width:2%;vertical-align: top;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">تاریخ</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($pronote)) ? date('Y-m-d',$pronote->date):''  }}</td>
                                                            <td style="width:2%;vertical-align: top;"></td>
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
                <tr>
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </tr>

                <table cellspacing="0" cellpadding="0" style="width:100%;border:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;">رسید پرونوٹ</td>
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
                            <td style="font-size:14px;vertical-align: top;padding-left: 5px;padding-right: 5px;">جو کہ مبلغ <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->amount:''  }}</span>روپے نصف جن کے <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->amount_half:''  }}</span>روپے ہوتے ہیں بطور مواخانہ مواخات فاؤنڈیشن بزریعہ چیک نمبر
                            <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->check_number:''  }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px;vertical-align: top;padding-left: 5px;padding-right: 5px;">مورخہ<span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}</span>مالیتی <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->owner:''  }}</span>بینک <span style="width:180px;display:inline-block;vertical-align:middle;border-bottom:1px solid #000;">{{ (isset($raseed_pronote)) ? $raseed_pronote->bank:''  }}</span> روبرو ضامنان زیل وصول پا لیےہیں۔ لہذاٰ یہ رسید وصولی کی لکھ دی ہے تاکہ سند رہے۔
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
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">العبد</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($raseed_pronote)) ? $raseed_pronote->alabd:''  }}</td>
                                                            <td style="width:2%;vertical-align: top;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">دستخط بمعہ نشان انگوٹھا</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;"></td>
                                                            <td style="width:2%;vertical-align: top;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%"></td>
                                            <td style="width:32%">
                                                <table cellspacing="0" cellpadding="0" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:33%;font-size:14px;vertical-align: top;">تاریخ</td>
                                                            <td style="width:1%"></td>
                                                            <td style="width:64%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}</td>
                                                            <td style="width:2%;vertical-align: top;"></td>
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
                <tr>
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
                @php
                if(isset($application->zaminan_json)){
                    $arrayData = json_decode($application->zaminan_json, true);
                }
                @endphp
                <table cellspacing="0" cellpadding="0" style="width:100%;border:3px solid #000;">
                    <tbody>
                        <tr>
                            <td style="height:30px;"></td>
                        </tr>
                        <tr>
                            <td style="width:100%;height:10px;font-size:16px;font-weight:bold;text-align:center;"> ضامنان</td>
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;">نام بمعہ ولدیت اور زوجیت</td>
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;">شناختی کارڈ نمبر
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;">دستخط</td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size: 14px;">{{ !empty($arrayData['zaminan_signature_1']) ? $arrayData['zaminan_signature_1'] : '' }}</td>
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;">نام بمعہ ولدیت اور زوجیت
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;"> شناختی کارڈ نمبر
                                                            </td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">
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
                                                            <td style="width:35%;font-size:14px;vertical-align: top;font-size: 14px;">دستخط</td>
                                                            <td style="width:65%;border-bottom:1px solid #000;font-size:14px;vertical-align: top;">{{ !empty($arrayData['zaminan_signature_2']) ? $arrayData['zaminan_signature_2'] : '' }}</td>
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
                <tr>
                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="height:35px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
            </div>
        </div>


        <!-- Bootstrap Bundle with Popper -->
        <script src="{{ url('user/js/jquery.js') }}"></script>
        <script src="{{ url('user/js/bootstrap-5.min.js') }}"></script>

        <script src="{{ asset('user/js/canvas.min.js') }}"></script>
        <script src="{{ asset('user/js/canvas2image.js') }}"></script>
        <script src="{{ asset('user/js/jspdf.debug.js') }}"></script>
        <script>
            var formBaseCode = '';
            $(function(){
                download();
            })
            function download()
            {
				$('.preloader').css('display','block');
                var form = $(".business-palns-form").get(0);
                html2canvas(form).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF({
                    orientation: 'p',
                    unit: 'px',
                    format: [canvas.width, canvas.height]
                    });
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    let imgWidth = canvas.width;
                    let imgHeight = canvas.height;
                    let ratio = 1;
                    if (imgWidth > pageWidth) {
                    ratio = pageWidth / imgWidth;
                    imgWidth *= ratio;
                    imgHeight *= ratio;
                    }
                    let remainingHeight = imgHeight;
                    let currentPage = 1;
                    while (remainingHeight > 0) {
                    pdf.addImage(imgData, 'PNG', 0, -(currentPage - 1) * pageHeight, imgWidth, imgHeight);
                    remainingHeight -= pageHeight;
                    currentPage++;
                    if (remainingHeight > 0) {
                        pdf.addPage();
                    }
                    }
                    pdf.save('business-booster-form.pdf');
                });

                setTimeout(function(){
                        window.close();
                    }, 5000);

                // html2canvas(form).then(function(canvas)
                // {
                //     var canvasWidth = canvas.width;
                //     var canvasHeight = canvas.height;
                //     if(canvasWidth != 0 && canvas != 0)
                //     {
                //         var img = Canvas2Image.convertToImage(canvas, canvasWidth, canvasHeight);
                //         formBaseCode = img;

                //         var pdf = new jsPDF();

                //         var width = 200;//pdf.internal.pageSize.getWidth();
                //         var height = pdf.internal.pageSize.getHeight();
                //         pdf.setTextColor('#000000');

                //         // then put image on top of texts (so texts are not visible)
                //         pdf.addImage(
                //             formBaseCode,
                //             'PNG',
                //             0,
                //             0,
                //             width,
                //             350
                //         );
                //         pdf.save('business-booster-form.pdf');


                //         // var a = document.createElement("a"); //Create <a>
                //         // a.href = img; //Image Base64 Goes here
                //         // a.download = "form.png"; //File name Here
                //         // a.click(); //Downloaded file
                //     }
                //     else
                //     {
                //         alert('Something went wrong');
                //     }
                //     setTimeout(function(){
                //         window.close();
                //     }, 3000);
                // })
            }
        </script>
	</body>
</html>
