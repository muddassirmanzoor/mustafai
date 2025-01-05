<form id="business-palns-form">
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
                            {{$pmethod->method_name_english}}
                        </div>
                        @foreach($pmethod->paymentDetails as $detail)
                            <div class="form-group pmethods-input-sending-{{$pmethod->id}}" style="display: {{(empty($checked)) ? 'none;':'block;'}}">
                                <label for="">{{$detail->method_fields_english}}</label>
                                <input type="text" class="form-control custom-file-input bp-input" placeholder="Enter {{$detail->method_fields_english}}" name="sending_details[{{$pmethod->id}}][{{$detail->id}}]" value="{{ (isset($accounts['sending']['payment_method_field_'.$detail->id])) ? $accounts['sending']['payment_method_field_'.$detail->id]:'' }}" required>
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
                            {{$pmethod->method_name_english}}
                        </div>
                        @foreach($pmethod->paymentDetails as $detail)
                            <div class="form-group pmethods-input-recieving-{{$pmethod->id}}" style="display: {{(empty($checked)) ? 'none;':'block;'}}">
                                <label for="">{{$detail->method_fields_english}}</label>
                                <input type="text" class="form-control custom-file-input bp-input" placeholder="Enter {{$detail->method_fields_english}}" name="recieving_details[{{$pmethod->id}}][{{$detail->id}}]" value="{{ (isset($accounts['recieving']['payment_method_field_'.$detail->id])) ? $accounts['recieving']['payment_method_field_'.$detail->id]:'' }}" required>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>


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
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_contact_number" placeholder="رابطہ نمبر" required value="{{(!empty($application))?$application->form_contact_number : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">شناختی کارڈ</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_nic_number" placeholder="شناختی کارڈ" required value="{{(!empty($application))?$application->form_nic_number : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">نام</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_full_name" placeholder="نام" required value="{{(!empty($application))?$application->form_full_name : ''}}">
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
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_temp_address" placeholder="عارضی پتہ" required value="{{(!empty($application))?$application->form_temp_address : ''}}">
                        </div>
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">مستقل ایڈریس</label>
                            <input class="bp-input do-not-ignore form-control " type="text" name="form_permanent_address" placeholder="مستقل ایڈریس" required value="{{(!empty($application))?$application->form_permanent_address : ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex">
                        <div class="f-business-input-image-file">
                            <figure>
                                <span class="user_identification_image">
                                    <input type="hidden" name="form_old_image" value="{{ (!empty($application) && isset($application->form_image)) ? $application->form_image:'' }}">
                                    <label class="custom-file-upload">
                                    <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                        <input type="file" name="form_image" id="form-image" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))">
                                        <img src="{{ getS3File((isset($application->form_image)) ? $application->form_image:'./images/avatar.png') }}" alt="profile" class="img-fluid">
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
                        پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔
                    </p>
                </div>
                <div class="d-flex justify-content-lg-end mt-1">
                    <label>دستخط بمعہ نشان انگوٹھا</label>
                    <input class="bp-input do-not-ignore" type="text" readonly>
                </div>
                <div class="row business-booster-2 mt-3 py-3">
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
                            <input class="bp-input do-not-ignore" type="text" name="form_witness1_nic" placeholder="شناختی کارڈ" required value="{{ (isset($witnesses[0])) ? $witnesses[0]->nic : '' }}">
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
                            <p>الات/دالیت وہں ہک وم ااخہن ا و رپ ایبن رکدہ دصقم ہپ یہ رخچ ن
وہ اگا ورومااخہن یک دعم ادایگیئ یک وصرت ںیم ربوتق ادا یگیئ اک/ یک ذہم دا ر وہں۔ زین یسک یھب وکاتیہ یک وصرت ںیم ادا رہ الخف اقونین اچرہ وجیئ اک قح راتھک ےہ۔سج ےک امتم رت اچرزج یک یھب ذہم دا ری ھجم رپ وہ یگ۔
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ اگلی سائیڈ</label>
                            <input type="hidden" name="witness1_nic_front_old" value="{{ (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input type="file" name="form_witness1_nic_front" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))">
                                <img src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_front)) ? $witnesses[0]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ  پچھلی سائیڈ</label>
                            <input type="hidden" name="witness1_nic_back_old" value="{{ (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input type="file" name="form_witness1_nic_back" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))">
                                <img src="{{ getS3File( (isset($witnesses[0]) && !empty($witnesses[0]->nic_back)) ? $witnesses[0]->nic_back : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                        </div>
                    </div>


                    <div class="col-12 d-flex justify-content-end mt-1">
                        <label>دستخط بمعہ نشان انگوٹھا</label>
                        <input class="bp-input do-not-ignore" type="text" readonly>
                    </div>

                </div>
                <div class="row business-booster-2 mt-3 py-3">
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
                            <input class="bp-input do-not-ignore" type="text" name="form_witness2_nic" placeholder="شناختی کارڈ" required value="{{ (isset($witnesses[1])) ? $witnesses[1]->nic : '' }}">
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
                            <p>الات/دالیت وہں ہک وم ااخہن ا و رپ ایبن رکدہ دصقم ہپ یہ رخچ ن
وہ اگا ورومااخہن یک دعم ادایگیئ یک وصرت ںیم ربوتق ادا یگیئ اک/ یک ذہم دا ر وہں۔ زین یسک یھب وکاتیہ یک وصرت ںیم ادا رہ الخف اقونین اچرہ وجیئ اک قح راتھک ےہ۔سج ےک امتم رت اچرزج یک یھب ذہم دا ری ھجم رپ وہ یگ۔
                            </p>
                        </div>
                    </div>
                    <p>
                        پرنٹنگ اور ٹائپ سیٹنگ انڈسٹری کا محض ڈمی ٹیکسٹ ہے۔ Lorem Ipsum 1500 کی دہائی سے انڈسٹری کا معیاری ڈمی ٹیکسٹ رہا ہے، جب ایک نامعلوم پرنٹر نے قسم کی ایک گیلی لی اور اسے ایک قسم کے نمونے کی کتاب بنانے کے لیے گھسایا۔ یہ نہ صرف پانچ صدیوں تک زندہ رہا ہے بلکہ الیکٹرانک ٹائپ سیٹنگ میں بھی چھلانگ لگا ہوا ہے، بنیادی طور پر کوئی تبدیلی نہیں کی گئی۔ اسے 1960 کی دہائی میں Lorem Ipsum حصئوں پر مشتمل Letraset شیٹس کے اجراء کے ساتھ اور حال ہی میں Aldus PageMaker جیسے ڈیسک ٹاپ پبلشنگ سوفٹ ویئر کے ساتھ مقبول کیا گیا جس میں Lorem Ipsum کے ورژن بھی شامل ہیں۔
                    </p>

                    <div class="col-xl-6 mt-1">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ اگلی سائیڈ</label>
                            <input type="hidden" name="witness2_nic_front_old" value="{{ (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input type="file" name="form_witness2_nic_front" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))">
                                <img src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-1">
                        <div class="d-flex mb-2 align-items-center f-business-input-image-file">
                            <label for="">شناختی کارڈ  پچھلی سائیڈ</label>
                            <input type="hidden" name="witness2_nic_back_old" value="{{ (isset($witnesses[1]) && !empty($witnesses[1]->nic_back)) ? $witnesses[1]->nic_back : '' }}">
                            <label class="custom-file-upload">
                                <span class="camera-btn" type="btn"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <input type="file" name="form_witness2_nic_back" accept="image/png, image/gif, image/jpeg" onchange="selectPlanImages($(this))">
                                <img src="{{ getS3File( (isset($witnesses[1]) && !empty($witnesses[1]->nic_front)) ? $witnesses[1]->nic_front : './images/avatar.png' )  }}" alt="profile" class="img-fluid">
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
                        <input class="bp-input do-not-ignore" type="text"  readonly>
                    </div>
                </div>
                <div class="row business-booster-2 mt-3 py-3">
                    <h6 class="text-center">پرونوٹ</h6>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">نام</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_name" placeholder="نام" required value="{{ (isset($pronote)) ? $pronote->name:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ولد یا زوجہ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_guardian" placeholder="ولد یا زوجہ" required value="{{ (isset($pronote)) ? $pronote->guardian_name:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">شناختی کارڈ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_nic" placeholder="شناختی کارڈ" required value="{{ (isset($pronote)) ? $pronote->nic:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ساکن</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_address" placeholder="ساکن" required value="{{ (isset($pronote)) ? $pronote->address:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">تحصیل</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_tehcil" placeholder="تحصیل" required value="{{ (isset($pronote)) ? $pronote->tehcil:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">ضلع</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_district" placeholder="ضلع" required value="{{ (isset($pronote)) ? $pronote->district:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>کہ مبلغ</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_ammount" placeholder="کہ مبلغ" required value="{{ (isset($pronote)) ? $pronote->amount:''  }}">
                            <label>روپے</label>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <p>
                            <span>نصف جن کے </span>
                            <span><input class="bp-input do-not-ignore" type="text" name="form_pronote_ammount_half" required value="{{ (isset($pronote)) ? $pronote->amount_half:''  }}"></span>
                            <span> ہوتے ہیں۔ ازاں مواخات فاؤنڈیشن سے مواخانہ لے کر وعدہ کرتا ہوں کہ مبلغات بالا مع سروس چارج </span>
                            <span><input class="bp-input do-not-ignore" type="text" name="form_pronote_charge" required value="{{ (isset($pronote)) ? $pronote->service_charges:''  }}"></span>
                            <span> شمار کر کے روزانہ / یکمشت عند الطلب مواخات فاؤنڈیشن یا جس کا حکم دیں، ادا کروں گا / گی۔ لہذا پر دونوٹ بقائمی ہوش و حواس لکھ دی ہے تاکہ سند رہے۔  </span>
                        </p>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>العبد</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_pronote_alabd" placeholder="العبد" required value="{{ (isset($pronote)) ? $pronote->alabd:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>دستخط بمعہ نشان انگوٹھا</label>
                            <input class="bp-input do-not-ignore" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">تاریخ</label>
                            <input class="bp-input do-not-ignore" type="date" name="form_pronote_date" max="9000-12-31" placeholder="تاریخ" max="9000-12-31"  required value="{{ (isset($pronote)) ? date('Y-m-d',$pronote->date):''  }}">
                        </div>
                    </div>
                </div>

                <div class="row business-booster-2 mt-3 py-3">
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
                        <div class="d-flex mb-2 align-items-center">
                            <label>العبد</label>
                            <input class="bp-input do-not-ignore" type="text" name="form_raseed_pronote_alabad" placeholder="العبد" required value="{{ (isset($raseed_pronote)) ? $raseed_pronote->alabd:''  }}">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>دستخط بمعہ نشان انگوٹھا</label>
                            <input class="bp-input do-not-ignore" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">تاریخ</label>
                            <input class="bp-input do-not-ignore" type="date" name="form_raseed_pronote_date" placeholder="تاریخ" required value="{{ (isset($raseed_pronote)) ? date('Y-m-d',$raseed_pronote->date):''  }}">
                        </div>
                    </div>
                </div>

                <div class="row business-booster-2 mt-3 py-3">
                    <h6 class="text-center">ضامنان</h6>

                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>نام بمعہ ولدیت اور زوجیت</label>
                            <input class="bp-input" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>شناختی کارڈ نمبر</label>
                            <input class="bp-input" type="text"readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">دستخط</label>
                            <input class="bp-input" type="text" readonly>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>نام بمعہ ولدیت اور زوجیت</label>
                            <input class="bp-input " type="text" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label>دستخط بمعہ نشان انگوٹھا</label>
                            <input class="bp-input" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="d-flex mb-2 align-items-center">
                            <label for="">دستخط</label>
                            <input class="bp-input" type="text" readonly >
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
