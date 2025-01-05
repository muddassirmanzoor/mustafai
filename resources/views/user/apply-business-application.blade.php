@extends('user.layouts.layout')
@push('styles')
<style>
    .bussiness-collapse-row.d-flex.justify-content-between.align-items-center.mt-3.field-error {
        color: red;
    }

    .fondation-pdf-form label.error {
        color: transparent;
        display: none;
    }

    .fondation-pdf-form label.error:after {
        content: '*';
        color: red;
        display: none;
    }

    .invalid-field {
        border-bottom: 1px solid red !important;
        background-color: #e8c1c1;
    }

</style>
@endpush
@section('content')
<form id="business-palns-form">
    <h3 class="text-center my-2">{{ __('app.application-form') }}</h3>
    <div class="bussiness-collapse-row d-flex justify-content-between align-items-center mt-3">
        <h5>{{ __('app.fill-form') }}</h5>
        <button class="btn add-more-btn sec-col-btn" type="button" data-bs-toggle="collapse" data-bs-target="#fillForm" aria-expanded="false" aria-controls="fillForm" data-txt="+" onclick="btnText($(this))">
            +
        </button>
    </div>
    <div class="collapse collapse-rows fondation-pdf-form" id="fillForm">
        <div class="row mt-4  dash-common-card">
            <div class="col-sm-12">
                <h2 class="text-center">مواخات فاؤنڈیشن</h2>
                <h6 class="text-center mb-5">درخواست فارم برائے بزنس بوسٹر</h6>
                <div class="row business-booster-1">
                    <div class="col-lg-6">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">سیریل نمبر</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_serial_number" placeholder="سیریل نمبر" readonly value="{{$serial_number}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">تاریخ</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_date" placeholder="تاریخ" readonly value="{{$def_date}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">رابطہ نمبر</label>
                            <input class="bp-input do-not-ignore form-control " type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="form_contact_number" placeholder="رابطہ نمبر" required value="{{ auth()->check() ? auth()->user()->phone_number : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">شناختی کارڈ</label>
                            <input class="bp-input do-not-ignore form-control " data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X" type="text" name="form_nic_number" placeholder="شناختی کارڈ" required value="{{auth()->check() ? auth()->user()->cnic  : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">نام</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_full_name" placeholder="نام" required value="{{auth()->check() ? auth()->user()->user_name_english : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ولدیت یا زوجیت</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_guardian_name" placeholder="ولدیت یا زوجیت" required value="{{(!empty($application))?$application->form_guardian_name : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">کاروبار کی نوعیت</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_business_coessentiality" placeholder="کاروبار کی نوعیت" required value="{{(!empty($application))?$application->form_business_coessentiality : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">بزنس بوسٹر کی غرض</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_plan_reason" placeholder="بزنس بوسٹر کی غرض" required value="{{(!empty($application))?$application->form_plan_reason : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">عارضی پتہ</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_temp_address" placeholder="عارضی پتہ" required value="{{auth()->check() ? auth()->user()->address_english : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">مستقل ایڈریس</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_permanent_address" placeholder="مستقل ایڈریس" required value="{{!empty(auth()->user()->permanentAddress->permanent_address_english )?auth()->user()->permanentAddress->permanent_address_english : ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex">
                        <div class="f-business-input-image-file">

                            <figure>

                                <span class="user_identification_image">
                                    <input type="hidden" name="form_old_image" value="{{ (!empty($application) && isset($application->form_image)) ? $application->form_image:'' }}">
                                    <label class="custom-file-upload">
                                        <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                        <input type="file" class="bp-input do-not-ignore form-control " name="form_image" id="form-image" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))" {{(isset($application->form_image)) ? '':'required'}} >
                                        <img src="{{ getS3File((isset($application->form_image)) ? $application->form_image:'./images/avatar.png') }}" alt="profile" class="img-fluid">

                                        <label  id="form-image-error" class="error text-danger " for="form-image"></label>
                                    </label>
                                </span>

                            </figure>
                        </div>
                        {{-- <div class="f-business-input-image-file">
                            <figure>
                                <span class="user_identification_image">

                                    <img src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : './images/user-round-img.png' }}" alt="profile" class="img-fluid">
                        </span>
                        </figure>
                    </div> --}}
                </div>
            </div>
            <div class="d-flex">
                <p>
                    میں مواخات فاؤنڈیشن کے زیر انتظام چلنے والے "بزنس بوسٹر پروگرام " کے کلب ({{$plan->{'name_english'} }}) کا حصہ جس کا روزانہ کا مواخانہ مبلغ({{$plan->invoice_amount}})روپے کا حصہ بنا چاہ رہا ہوں۔ میں آپ کو یقین دلاتا ہوں کے میں سال کے 365 دن مواخانہ کی ادائیگی بر وقت کروں گا اور کسی قسم کے پس و پیش سے کام نہ لوں گا۔ میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کر وہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کوتاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہوگی۔
دستخط درخواست دہندہ بمعہ نشان انگوٹھا

                </p>

            </div>
            <div class="d-flex justify-content-lg-end mt-1">
                <label>دستخط بمعہ نشان انگوٹھا</label>
                <input class="bp-input do-not-ignore" type="text" readonly>
            </div>
            <div class="row business-booster-2 mt-3 py-3 family-terms">
                <h6 class="text-center">خاندان سے ضامن</h3>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <p></p>
                            <label for="">نام</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_name" placeholder="نام" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->name : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ولدیت یا زوجیت</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_guardian" placeholder="ولدیت یا زوجیت" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->guardian_name : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">شناختی کارڈ</label>
                            <input class="bp-input do-not-ignore" type="text"  name="form_witness1_nic" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->nic : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">رشتہ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_relation" placeholder="رشتہ" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->relation : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">کاروبار</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_business" placeholder="کاروبار" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->business : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">رابطہ نمبر</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_contact_number" placeholder="رابطہ نمبر" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->contact_number : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="d-flex mb-2 align-items-center">
                            <p>

                                میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں، اس بات کا یقین دلاتا / دلاتی ہوں کہ مواخانہ اوپر بیان کردہ مقصد پہ ہی خرچ ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں بروقت ادائیگی کا / کی ذمہ دار ہوں۔ نیز کسی بھی کو تاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہو گی۔

                            </p>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ اگلی سائیڈ</label>
                            <input type="hidden" name="witness1_nic_front_old" value="{{ (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input class="do-not-ignore" type="file" name="form_witness1_nic_front" id="witness1_nic_front_old" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))" {{(isset($witnesses[0]->nic_front)) ? '':'required'}} >
                                <img src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                <label class="error text-danger" for="witness1_nic_front_old" id="witness1_nic_front_old-error"></label>
                            </label>
                        </div>
                        {{-- <div> --}}
                        {{-- </div> --}}
                    </div>

                    <div class="col-xl-6">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ پچھلی سائیڈ</label>
                            <input type="hidden" name="witness1_nic_back_old" value="{{ (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input class="do-not-ignore"  type="file" name="form_witness1_nic_back" id="form_witness1_nic_back" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))" {{(isset($witnesses[0]->nic_back)) ? '':'required'}} >
                                <img src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                <label class="error text-danger" for="form_witness1_nic_back" id="form_witness1_nic_back-error"></label>
                        </div>
                        {{-- <div> --}}
                        {{-- </div> --}}
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-1">
                        <label>دستخط بمعہ نشان انگوٹھا</label>
                        <input class="bp-input do-not-ignore" type="text" readonly>
                    </div>

            </div>
            <div class="row business-booster-2 mt-3 py-3 outer-family-terms">
                <h6 class="text-center">بیرونی ضامن</h3>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <p></p>
                            <label for="">نام</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_name" placeholder="نام" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->name : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ولدیت یا زوجیت</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_guardian" placeholder="ولدیت یا زوجیت" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->guardian_name : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">شناختی کارڈ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_nic" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->nic : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">رشتہ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_relation" placeholder="رشتہ" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->relation : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">کاروبار</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_business" placeholder="کاروبار" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->business : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">رابطہ نمبر</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_contact_number" placeholder="رابطہ نمبر" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->contact_number : '' }}">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="d-flex mb-2 align-items-center">
                           <p>

                            میں اس بات کا اقرار کرتا / کرتی ہوں کہ اوپر دی گئی معلومات درست ہیں ، اس بات کا یقین دلا تا / دلاتی ہوں کہ مواخانہ اوپر بیان کردہ مقصد پہ ہی خرچ
ہو گا اور مواخانہ کی عدم ادائیگی کی صورت میں جتنی رقم واجب الادا ہو گی میں اپنے پاس سے جمع کرانے کا / کی ذمہ دار ہوں۔ نیز کسی بھی کوتاہی کی صورت میں ادارہ میرے خلاف قانونی چارہ جوئی کا حق رکھتا ہے۔ جس کے تمام تر چار جز کی بھی ذمہ داری مجھ پر ہو گی۔

                           </p>
                        </div>
                    </div>


                    <div class="col-xl-6 mt-1">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ اگلی سائیڈ</label>
                            <input type="hidden" name="witness2_nic_front_old" value="{{ (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input class="do-not-ignore" type="file" name="form_witness2_nic_front" id="form_witness2_nic_front" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))"{{(isset($witnesses[1]->nic_front)) ? '':'required'}} >
                                <img src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                <label  id="form_witness2_nic_front-error" class="error text-danger " for="form_witness2_nic_front"></label>

                            </label>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-1">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ پچھلی سائیڈ</label>
                            <input type="hidden" name="witness2_nic_back_old" value="{{ (isset($witnesses[1]) && !empty($witnesses[1]->nic_back)) ? $witnesses[1]->nic_back : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input class="do-not-ignore" type="file" name="form_witness2_nic_back" id="form_witness2_nic_back" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))" {{(isset($witnesses[1]->nic_back)) ? '':'required'}} >
                                <img src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_back)) ? $witnesses[1]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                                <label  id="form_witness2_nic_back-error" class="error text-danger " for="form_witness2_nic_back"></label>

                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-1">
                        <label>دستخط بمعہ نشان انگوٹھا</label>
                        <input class="bp-input do-not-ignore" type="text" readonly>
                    </div>
            </div>
            <div class="d-flex mt-3 justify-content-lg-around flex-lg-row flex-column">
                <div class="d-flex align-items-center">
                    <label class="ms-2">دستخط مقامی زمہ دار</label>
                    <input class="bp-input do-not-ignore" type="text" readonly>
                </div>
                <div class="d-flex align-items-center">
                    <label class="ms-2">دستخط ضلعی زمہ دار</label>
                    <input class="bp-input do-not-ignore" type="text" readonly>
                </div>
            </div>
            <div class="row business-booster-2 mt-3 py-3 pronote-terms">
                <h6 class="text-center">پرونوٹ</h6>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">نام</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_name" placeholder="نام" required value="{{ (isset($pronote)) ? $pronote->name:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">ولد یا زوجہ</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_guardian" placeholder="ولد یا زوجہ" required value="{{ (isset($pronote)) ? $pronote->guardian_name:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">شناختی کارڈ</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_nic" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X" required value="{{ (isset($pronote)) ? $pronote->nic:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">ساکن</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_address" placeholder="ساکن" required value="{{ (isset($pronote)) ? $pronote->address:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">تحصیل</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_tehcil" placeholder="تحصیل" required value="{{ (isset($pronote)) ? $pronote->tehcil:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label for="">ضلع</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_district" placeholder="ضلع" required value="{{ (isset($pronote)) ? $pronote->district:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center pronote-terms-lable">
                        <label>کہ مبلغ</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_ammount" placeholder="کہ مبلغ" required value="{{ (isset($pronote)) ? $pronote->amount:''  }}">
                        <label>روپے</label>
                    </div>
                </div>
                <div class="col-xl-12">
                    <p>
                        <span>نصف جن کے</span>
                        <span><input class="bp-input do-not-ignore" type="text" name="form_pronote_ammount_half" required value="{{ (isset($pronote)) ? $pronote->amount_half:''  }}"></span>
                        <span> ہوتے ہیں۔ ازاں مواخات فاؤنڈیشن سے مواخانہ لے کر وعدہ کرتا ہوں کہ مبلغات بالا مع سروس چارج </span>
                        <span><input class="bp-input do-not-ignore" type="text" name="form_pronote_charge" required value="{{ (isset($pronote)) ? $pronote->service_charges:''  }}"></span>
                        <span> شمار کر کے روزانہ / یکمشت عند الطلب مواخات فاؤنڈیشن یا جس کا حکم دیں، ادا کروں گا / گی۔ لہذا پر دونوٹ بقائمی ہوش و حواس لکھ دی ہے تاکہ سند رہے۔</span>
                    </p>
                </div>
                <div class="col-xl-4 pronote-terms-lable">
                    <div class="d-flex mb-2 align-items-center">
                        <label>العبد</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_pronote_alabd" placeholder="العبد" required value="{{ (isset($pronote)) ? $pronote->alabd:''  }}">
                    </div>
                </div>
                <div class="col-xl-4 pronote-terms-lable">
                    <div class="d-flex mb-2 align-items-center">
                        <label>دستخط بمعہ نشان انگوٹھا</label>
                        <input class="bp-input do-not-ignore" type="text" readonly>
                    </div>
                </div>
                <div class="col-xl-4 pronote-terms-lable">
                    <div class="d-flex mb-2 align-items-center">
                        <label for="">تاریخ</label>
                        <input class="bp-input do-not-ignore" type="date" name="form_pronote_date" max="9000-12-31" placeholder="تاریخ" max="9000-12-31" required value="{{ (isset($pronote)) ? date('Y-m-d',$pronote->date):''  }}">
                    </div>
                </div>
            </div>

            <div class="row business-booster-2 mt-3 py-3 slip-pronotes">
                <h6 class="text-center">رسید پرونوٹ</h6>
                <h6 class="text-center">باعث تحریر آنکہ</h6>
                <div class="col-xl-12">
                    <div class="d-flex mb-2 align-items-center">
                        <p>
                            <span>جو کہ مبلغ</span>
                            <span><input type="text" class="bp-input do-not-ignore" name="form_raseed_pronote_amount" value="{{ (isset($raseed_pronote)) ? $raseed_pronote->amount:''  }}"></span>
                            <span>روپے نصف جن کے</span>
                            <span><input type="text" class="bp-input do-not-ignore" name="form_raseed_pronote_amount_half" value="{{ (isset($raseed_pronote)) ? $raseed_pronote->amount_half:''  }}"></span>
                            <span>روپے ہوتے ہیں بطور مواخانہ مواخات فاؤنڈیشن بزریعہ چیک نمبر</span>
                            <span><input type="text" class="bp-input do-not-ignore" name="form_raseed_pronote_check_number" value="{{ (isset($raseed_pronote)) ? $raseed_pronote->check_number:''  }}"></span>
                            <span>مورخہ</span>
                            <span><input type="date" max="9000-12-31" class="bp-input do-not-ignore" name="form_raseed_pronote_check_date" value="{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}"></span>
                            <span>مالیتی</span>
                            <span><input type="text" class="bp-input do-not-ignore" name="form_raseed_pronote_owner" value="{{ (isset($raseed_pronote)) ? $raseed_pronote->owner:''  }}"></span>
                            <span>بینک</span>
                            <span><input type="text" class="bp-input do-not-ignore" name="form_raseed_pronote_bank" value="{{ (isset($raseed_pronote)) ? $raseed_pronote->bank:''  }}"></span>
                            <span>روبرو ضامنان زیل وصول پا لیےہیں۔ لہذاٰ یہ رسید وصولی کی لکھ دی ہے تاکہ سند رہے۔</span>
                        </p>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center slip-pronotes-lable">
                        <label>العبد</label>
                        <input class="bp-input do-not-ignore" type="text" name="form_raseed_pronote_alabad" placeholder="العبد" required value="{{ (isset($raseed_pronote)) ? $raseed_pronote->alabd:''  }}">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center slip-pronotes-lable">
                        <label>دستخط بمعہ نشان انگوٹھا</label>
                        <input class="bp-input do-not-ignore" type="text" readonly>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center slip-pronotes-lable">
                        <label for="">تاریخ</label>
                        <input class="bp-input do-not-ignore" type="date" max="9000-12-31" name="form_raseed_pronote_date" placeholder="تاریخ" required value="{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}">
                    </div>
                </div>
            </div>

            @php
                if(isset($application->zaminan_json)){
                    $arrayData = json_decode($application->zaminan_json, true);
                }
            @endphp
            <div class="row business-booster-2 mt-3 py-3 close-family-terms">
                <h6 class="text-center">ضامنان</h6>

                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label>نام بمعہ ولدیت اور زوجیت</label>
                        <input class="bp-input" name="zaminan_name_1" value="{{ !empty($arrayData['zaminan_name_1']) ? $arrayData['zaminan_name_1'] : '' }}" type="text">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label>شناختی کارڈ نمبر</label>
                        <input class="bp-input" name="zaminan_cnic_1" value="{{ !empty($arrayData['zaminan_cnic_1']) ? $arrayData['zaminan_cnic_1'] : '' }}" type="text" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label for="">دستخط</label>
                        <input class="bp-input" name="zaminan_signature_1" value="{{ !empty($arrayData['zaminan_signature_1']) ? $arrayData['zaminan_signature_1'] : '' }}" type="text">
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label>نام بمعہ ولدیت اور زوجیت</label>
                        <input class="bp-input " name="zaminan_name_2" value="{{ !empty($arrayData['zaminan_name_2']) ? $arrayData['zaminan_name_2'] : '' }}" type="text">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label>شناختی کارڈ نمبر</label>
                        <input class="bp-input" name="zaminan_cnic_2" value="{{ !empty($arrayData['zaminan_cnic_2']) ? $arrayData['zaminan_cnic_2'] : '' }}" type="text" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex mb-2 align-items-center">
                        <label for="">دستخط</label>
                        <input class="bp-input" name="zaminan_signature_2" value="{{ !empty($arrayData['zaminan_signature_2']) ? $arrayData['zaminan_signature_2'] : '' }}" type="text">
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    <div class="bussiness-collapse-row d-flex justify-content-between align-items-center mt-3">
        <h5>{{ __('app.available-date') }}</h5>
        <button class="btn add-more-btn sec-col-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" data-txt="+" onclick="btnText($(this))">
            +
        </button>
    </div>
    <div class="row dates-boxes-wraper dash-common-card collapse collapse-rows" id="collapseExample2">
        <span><label id="selected_date-error" class="error" for="selected_date"></label></span>
        <input type="hidden" name="plan_id" value="{{hashEncode($plan->id)}}">
        <input type="hidden" name="application_id" value="{{$application_id}}">
        <input type="hidden" name="action" value="{{$action}}">

        @php
        $index=0;
        $tabsHeader = '';
        $tabsContent = '';
        @endphp
        @foreach($dates as $key => $tab)

        @php
        if($index == 0)
        {
        $active = 'active';
        $show = 'show';
        }
        else {
        $active = '';
        $show = '';
        }
        $tabsHeader .= '<button class="nav-link '. $active .'" id="v-pills-'.$index.'-tab" data-bs-toggle="pill" data-bs-target="#v-pills-'.$index.'" type="button" role="tab" aria-controls="v-pills-'.$index.'" aria-selected="true">'.$key.'</button>';

        $tabsContent .= '<div class="row mt-3 tab-pane fade '. $show . ' ' .$active .'" id="v-pills-'.$index.'" role="tabpanel" aria-labelledby="v-pills-'.$index.'-tab">';
            foreach($dates[$key] as $date)
            {
            $class = 'active-date-lable';
            $checked = '';
            if($action == 'edit' && $date == date('d-m-Y',$application->selected_date))
            {
            $checked = 'checked';
            }
            else if(in_array($date,$applied))
            {
            $class = 'non-active-date-lable';
            }

            $tabsContent .=
            '

            <div class="col-sm-2 date-boxes">
                <label class="labl">
                    <input type="radio" name="selected_date" class="bp-input do-not-ignore" '.$checked.' value="'.$date.'" required />
                    <div class="'.$class.'">'.$date.'</div>
                </label>
            </div>
            ';
            }
            $tabsContent .= '</div>';

        @endphp
        @php $index++; @endphp

        @endforeach

        <div class="row">
            <div class="col-12">
                <div class="nav nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    {!! $tabsHeader !!}
                </div>
            </div>
            <div class="col-12">
                <div class="business-plan-calendar tab-content" id="v-pills-tabContent">
                    {!! $tabsContent !!}
                </div>
            </div>
        </div>
    </div>

    <div class="bussiness-collapse-row d-flex justify-content-between align-items-center mt-3">
        <h5>{{ __('app.accounts-forms') }}</h5>
        <button class="btn add-more-btn sec-col-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" data-txt="+" onclick="btnText($(this))">
            +
        </button>

    </div>
    <div class="collapse collapse-rows" id="collapseExample">
        <div class="row mt-4 acount-details dash-common-card">
            <div class="col-sm-6">
                <h6>{{ __('app.sending-account') }} <span><label id="sending_payment_method_ids[]-error" class="error" for="sending_payment_method_ids[]"></label></span></h6>
                <div class="form-control">
                    <label for=""><b>{{ __('app.payment-method-business-plan') }} <span class="text-red">*</span></b></label>
                    @foreach($payment_methods as $key => $pmethod)
                    @php
                    $checked = '';
                    if(isset($accounts['sending']) && in_array($pmethod->id,$accounts['sending']['payment_method_id']))
                    {
                    $checked = 'checked';
                    }
                    @endphp
                    <div class="form-group">
                        <input type="checkbox" class="pmethods-input-sending bp-input do-not-ignore" name="sending_payment_method_ids[]" required value="{{$pmethod->id}}" onchange="paymentMethods('pmethods-input-sending')" {{ $checked }}>
                        {{$pmethod->{'method_name_'.App::getLocale()} }}
                    </div>
                    @foreach($pmethod->paymentDetails as $detail)
                    <div class="form-group pmethods-input-sending-{{$pmethod->id}}" style="display: {{(empty($checked)) ? 'none;':'block;'}}">
                        <label for="">{{$detail->{'method_fields_'.App::getLocale()} }}</label>
                        <input type="text" class="form-control custom-file-input bp-input" placeholder="{{__('app.enter') .$detail->{'method_fields_'.App::getLocale()} }}" name="sending_details[{{$pmethod->id}}][{{$detail->id}}]" value="{{ (isset($accounts['sending']['payment_method_field_'.$detail->id])) ? $accounts['sending']['payment_method_field_'.$detail->id]:'' }}" required>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </div>

            <div class="col-sm-6">
                <h6>{{ __('app.recieving-account') }} <span><label id="recieving_payment_method_ids[]-error" class="error" for="recieving_payment_method_ids[]"></label></span></h6>
                <div class="form-control">
                    <label for=""><b>{{ __('app.payment-method-business-plan') }} <span class="text-red">*</span></b></label>
                    @foreach($payment_methods as $key => $pmethod)
                    @php
                    $checked = '';
                    if(isset($accounts['recieving']) && in_array($pmethod->id,$accounts['recieving']['payment_method_id']))
                    {
                    $checked = 'checked';
                    }
                    @endphp
                    <div class="form-group ">
                        <input type="checkbox" class="pmethods-input-recieving bp-input do-not-ignore" name="recieving_payment_method_ids[]" required value="{{$pmethod->id}}" onchange="paymentMethods('pmethods-input-recieving')" {{ $checked }}>
                        {{$pmethod->{'method_name_'.App::getLocale()} }}
                    </div>
                    @foreach($pmethod->paymentDetails as $detail)
                    <div class="form-group pmethods-input-recieving-{{$pmethod->id}}" style="display: {{(empty($checked)) ? 'none;':'block;'}}">
                        <label for="">{{$detail->{'method_fields_'.App::getLocale()} }}</label>
                        <input type="text" class="form-control custom-file-input bp-input" placeholder="{{__('app.enter') .$detail->{'method_fields_'.App::getLocale()} }}" name="recieving_details[{{$pmethod->id}}][{{$detail->id}}]" value="{{ (isset($accounts['recieving']['payment_method_field_'.$detail->id])) ? $accounts['recieving']['payment_method_field_'.$detail->id]:'' }}" required>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-3"><button type="button" class="green-hover-bg theme-btn" onclick="submitForm()">{{ __('app.submit') }}</button></div>
</form>
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script>
    $(":input").inputmask();
</script>
<script>
    $(function() {
        $(document).on('change', '.bp-input', function() {
            var errorElem = $(this).parents('.collapse-rows').find('label.error:visible');
            if (!errorElem.length || errorElem.text() == '') {
                $(this).parents('.collapse-rows').prev('div').removeClass("field-error");
            }
        })
    })

    function paymentMethods(_class) {
            $("." + _class).each(function(index) {
                if ($(this).is(':checked')) {
                    $('.' + _class + '-' + $(this).val()).css('display', 'block');
                } else {
                    $('.' + _class + '-' + $(this).val()).css('display', 'none');
                }
            });
        }

        function btnText(_this) {
            if ($(_this).attr('data-txt') == "+") {
                $(_this).attr('data-txt', '-');
            } else {
                $(_this).attr('data-txt', '+');
            }

            $(_this).text($(_this).attr('data-txt'));
        }
        function goInvoices(applicationID) {
            window.location = "{{ URL('user/invoices') }}/" + applicationID;
        }

        function submitForm() {
            $('#business-palns-form').validate({
                ignore: ':hidden:not(.do-not-ignore)',
                rules: {
                    form_pronote_ammount: {
                        min: 100
                    },
                    form_pronote_ammount_half: {
                        min: 100
                    },
                    form_pronote_charge: {
                        min: 100
                    },
                    form_raseed_pronote_amount: {
                        min: 100
                    },
                    form_raseed_pronote_amount_half: {
                        min: 100
                    },
                    form_contact_number: {
                        maxlength: 15
                    },
                    form_witness1_contact_number: {
                        maxlength: 15
                    },
                    form_witness2_contact_number: {
                        maxlength: 15
                    },
                },
                highlight: function(element) {
                    $(element).addClass('invalid-field');
                    $(element).parents('.collapse-rows').prev('div').addClass("field-error");
                },
                unhighlight: function(element) {
                    $(element).removeClass('invalid-field');
                    // if($(element).parents('.collapse-rows').find('label.error:visible').length == "")
                    // {
                    //     $(element).parents('.collapse-rows').prev('div').removeClass("field-error");
                    // }
                }
            })

            if ($('#business-palns-form').valid()) {
                var formData = new FormData($('#business-palns-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.submit.plan-application') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.preloader').show();
                    },
                    success: function(data) {
                        if (data.status) {
                            var planId = data.plan_id;
                            $('#apply-now').modal('hide');
                            $('.apply-now-modal-' + planId).addClass('applied');
                            $('.apply-now-modal-' + planId).text('{{ __('app.applied') }}');
                            $('.apply-now-modal-' + planId).attr('onclick', '');

                            Swal.fire({
                                icon: 'success',
                                text: data.message,
                                type: 'success'
                            })

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: data.message,
                                type: 'error'
                            })
                        }
                        $('.preloader').hide();
                    }
                });
            }
        }

        function selectPlanImages(input) {
            var file = $(input).get(0).files[0];

            if (file) {
                var reader = new FileReader();
                console.log(reader);
                reader.onload = function() {
                    $(input).next('img').attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            } else {
                $(input).next('img').attr('src', "{{ asset('images/avatar.png') }}");
            }
        }

        function dateChangeRequest(applicationID = '',_this = '') {

            if (applicationID) {

                $('#req_change_application_id').val(applicationID);
                $('#application-date-change').modal('show');
            } else {
                if ($('#req-date-form').valid()) {
                    var formData = new FormData($('#req-date-form')[0]);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.submit.plan-application-date-request') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        beforeSend: function() {
                            $('.preloader').show();
                        },
                        success: function(data) {

                            $('#application-date-change').modal('hide');
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    text: data.message,
                                    type: 'success'
                                })

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: data.message,
                                    type: 'error'
                                })
                            }
                            $('.preloader').hide();

                        }
                    });
                }
            }
        }

</script>
@endpush
@endsection
