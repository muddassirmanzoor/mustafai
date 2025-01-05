<script>
    //Read more content

function showDesciption(_this){
    $(".desciption-lib-details-modal").remove();
    var descriptionLibb=_this.attr('data-description');
    if (descriptionLibb.trim() === '') {
        descriptionLibb = "{{__('app.no-data-available')}}";
    }
    var details  =   "{{__('app.details')}}";
    var modal = `
                <div class="modal fade common-model-style desciption-lib-details-modal" id="desciption-lib-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title text-green" id="exampleModalLabel">${details}</h4>
                        </div>
                        <div class="modal-body mt-3">
                        ${descriptionLibb}
                        </div>
                        <div class="modal-footer">
                            <button type="button"  data-bs-dismiss="modal" aria-label="Close" class=" green-hover-bg theme-btn">{{__('app.close')}}</button>
                        </div>

                    </div>
                    </div>
                </div>
                `;
    $('body').append(modal);
    $('.desciption-lib-details-modal').modal('show');

}
function readMore(id) {
    var dots = document.getElementById("dots_" + id);
    var moreText = document.getElementById("more_" + id);
    var btnText = document.getElementById("myBtn_" + id);

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        btnText.innerHTML = "{{ __('app.read-more') }}";
        moreText.style.display = "none";
    } else {
        dots.style.display = "none";
        btnText.innerHTML = "{{ __('app.read-less') }}";
        moreText.style.display = "inline";
    }
}
function readMoreString(_this){
		var moreString=_this.attr('data-moreString');
		var lessString=_this.attr('data-lessString');
		var dataType=_this.attr('data-type');
		if(dataType =='more'){
			var newString = `${moreString} <a href="javascript:void(0)" data-moreString="${moreString}" data-lessString="${lessString}" data-type="less"  onClick="readMoreString($(this))">Read Less</a>`;
			_this.parent().html(newString);

		}else{
			var newString = `${lessString} ... <a href="javascript:void(0)" data-moreString="${moreString}" data-lessString="${lessString}" data-type="more"  onClick="readMoreString($(this))">Read More</a>`;
			_this.parent().html(newString);
		}
	}
    // $(function() {
    var language = `{{ App::getLocale() }}`
    var arry = [];
    //______________for user  Pdfs____________//
    function dataCustomizetbale(title, docWidth, columnsss,filename) {
        // if(language == 'english'){
        return arry = [{
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Admin Users"></i>&nbsp;{{ __('app.export-pdf') }}',
                title: title,
                filename: filename,
                sCharSet: 'utf32',
                sBom: true,
                dirrection: 'ltr',
                orientation: 'landscape',
                alignment: "center",
                exportOptions: {
                    columns: columnsss,
                    alignment: "center",
                    orthogonal: "PDF",
                    modifier: {
                        order: 'index',
                        page: 'current'
                    },
                    format: {
                        body: function(data, row, column, node) {
                            const arabic =
                                /[\u0600-\u06FF-\u077F-\uFB50-\uFDFF-\uFE70‌-\uFEFF]/;

                            if (arabic.test(data)) {
                                var splitDataString = data.split(' ');

                                var dataStringNew = '';
                                var inc = 0;

                                var wordsArray = [];

                                $.each(splitDataString.reverse(), function(index, value) {
                                    inc = inc + 1;
                                    dataStringNew = dataStringNew + ' ' + value;

                                    if (inc > 25) {
                                        dataStringNew = dataStringNew + ' \n';
                                        inc = 0;
                                        wordsArray.push(dataStringNew);
                                        dataStringNew = '';
                                    } else if (typeof(splitDataString[index + 1]) == "undefined") {
                                        dataStringNew = dataStringNew + ' \n';
                                        wordsArray.push(dataStringNew);
                                    }
                                });

                                var returnData = '';
                                $.each(wordsArray.reverse(), function(index, value) {
                                    returnData = returnData + value;
                                });

                                return returnData;
                                // return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        },
                        header: function(data, row, column, node) {
                            const arabic =
                                /[\u0600-\u06FF-\u0750-\u077F-\uFB50-\uFDFF-\uFE70‌-\uFEFF]/;

                            if (arabic.test(data)) {
                                return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        },
                    }
                },
                customize: function(doc) {
                    doc.defaultStyle.font = 'Arial';
                    doc.content[1].table.widths = docWidth;
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    doc.content.splice(1, 0, {
                        margin: [0, -70, 0, 20],
                        alignment: 'right',
                        image: GlobalVar.image_logo,
                    });
                    doc.styles.title = {
                        // color: 'red',
                        fontSize: '45',
                        // margin: [ 120, 0, 0, -120],
                        // background: 'blue',
                        alignment: 'left'
                    }
                }
            },
            {
                extend: 'excelHtml5',
                title: filename,
                text: '<i class="fa fa-file-excel-o" aria-hidden="true" title="Filtered User"></i>&nbsp;{{ __('app.export-as-csv') }}',
                exportOptions: {
                    columns: columnsss,
                },
            },
        ];
        // }
        // else{
        //     return arry = [
        //         {
        //             extend: 'print',
        //             title: title,
        //             text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Filtered User"></i>&nbsp;{{ __('app.export-pdf') }}',
        //             exportOptions: {
        //                     columns:columnsss,
        //                 },
        //                 customize: function ( win ) {
        //                     $(win.document.body).find('h1').css('text-align', 'right');
        //                     $(win.document.body).find('th ').css('font-size', '20px');
        //                     $(win.document.body)
        //                         .css( 'font-size', '10pt' )
        //                         .prepend(

        //                             // '<img src='+imge+' style="position:absolute; top:0; left:0;" />',
        //                             '<img src="https://mustafaidev.server18.arhamsoft.info/images/logo/logo1663245377.png" style="position:absolute; top:0; left:0;bottom: 10px; width:120px;" />'

        //                         );

        //                         $(win.document.body).find( 'table' )
        //                         .addClass( 'compact' )
        //                         .css( 'font-size', 'inherit' );
        //                 }

        //         }

        //     ];
        // }

    }



    switch (language) {
        case 'english':

            //___________Alert Messages On Local____________________//

            var AlertMessage = {
                success: "Your Record Is Successfully Saved",
                subscription: "You have been subscribed to Mustafai Tahreek's portal successfully!!",
                wrong_fields: 'Wrong Fields',
                login_error: 'User Name Or Password Is Incorrect',
                event: "Event joined successfully!!",
                visibilityStatus: "Profile Status Is updated Successfully",
                done: "Done!",
                oops: 'Oops!',
                error: "something Went Wrong",
                info: "info",
                emailexist: "Your email is already exist",
                order_place: "Your order has been placed successfully!!",
                addCart: "The product is added to cart successfully!!",
                contactUs: "Your query is submitted successfully!!",
                comment_delete: "Your comment deleted successfully!!",
                empty_cart: "Your cart is empty right now!!",
                invalid_file: "Please select an image file.!",
                invalid_file_type: "Invalid file type.",
                invalid_file_message: "Please select a valid file.!",
                enter_somethong: "Please enter a valid input.!",
                search_empty: "You should add any text for search",


            }

            jQuery.validator.addMethod("onlyNumbers", function(value, element) {
                return this.optional(element) || /^\d+$/.test(value);
            }, "Please enter only numbers");
            //_________Add New Methods In Jquery Validation___________________///
            // jQuery.validator.addMethod("phoneNumber", function(phone_number, element) {
            //     phone_number = phone_number.replace(/\s+/g, "");
            //     return phone_number.length < 15 && phone_number.length > 9
            // }, "Please specify a valid phone number");
            jQuery.validator.addMethod("phoneNumber", function(phone_number, element) {
                phone_number = phone_number.replace(/\s+/g, "");
                return /^(?:\+\d{1,3})?\d{9,13}$/.test(phone_number);
            }, "Please specify a valid phone number");
            // jQuery.validator.addMethod("phoneNumber", function(phone_number, element) {
            //     phone_number = phone_number.replace(/\s+/g, "");
            //     return /^\d{10,14}$/.test(phone_number);
            // }, "Please specify a valid phone number");
            jQuery.validator.addMethod("isEmail", function(email, element) {
                email = email.replace(/\s+/g, "");
                return this.optional(element) || email.length > 9 &&
                    email.match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            }, "Please specify a valid email");
            jQuery.extend(jQuery.validator.messages, {
                alphanumeric:"User Name should be non white spaces alphanumeric string start from character Only e.g example123",
                characterOnly:"Full Name Should Be characters Only",
                signUpPhone:"Please specify a maximum 14 digits phone number without area code start with positive integer",
                required: "This field is required.",
                phoneAll: "Please specify a valid phone number",
                remote: "Please fix this field.",
                email: "Please enter a valid email address.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
                rangelength: jQuery.validator.format(
                    "Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
            });

            break;
        case 'urdu':

            jQuery.validator.addMethod("onlyNumbers", function(value, element) {
                return this.optional(element) || /^\d+$/.test(value);
            }, "صرف اعداد درج کریں");
            //_________Add New Methods In Jquery Validation___________________///
            jQuery.validator.addMethod("phoneNumber", function(phone_number, element) {
                phone_number = phone_number.replace(/\s+/g, "");
                return this.optional(element) || phone_number.length < 15 && phone_number.length > 10 &&
                    phone_number.match(
                        /(\+|00|0)(92|297|93|244|1264|358|355|376|971|54|374|1684|1268|61|43|994|257|32|229|226|880|359|973|1242|387|590|375|501|1441|591|55|1246|673|975|267|236|1|61|41|56|86|225|237|243|242|682|57|269|238|506|53|5999|61|1345|357|420|49|253|1767|45|1809|1829|1849|213|593|20|291|212|34|372|251|358|679|500|33|298|691|241|44|995|44|233|350|224|590|220|245|240|30|1473|299|502|594|1671|592|852|504|385|509|36|62|44|91|246|353|98|964|354|972|39|1876|44|962|81|76|77|254|996|855|686|1869|82|383|965|856|961|231|218|1758|423|94|266|370|352|371|853|590|212|377|373|261|960|52|692|389|223|356|95|382|976|1670|258|222|1664|596|230|265|60|262|264|687|227|672|234|505|683|31|47|977|674|64|968|92|507|64|51|63|680|675|48|1787|1939|850|351|595|970|689|974|262|40|7|250|966|249|221|65|500|4779|677|232|503|378|252|508|381|211|239|597|421|386|46|268|1721|248|963|1649|235|228|66|992|690|993|670|676|1868|216|90|688|886|255|256|380|598|1|998|3906698|379|1784|58|1284|1340|84|678|681|685|967|27|260|263)(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{4,20}$/
                );
            }, "براہ کرم ایک درست فون نمبر بتائیں");
            jQuery.validator.addMethod("isEmail", function(email, element) {
                email = email.replace(/\s+/g, "");
                return this.optional(element) || email.length > 9 &&
                    email.match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            }, "براہ کرم ایک درست ای میل کی وضاحت کریں");
            jQuery.extend(jQuery.validator.messages, {
                alphanumeric:"username123 صارف کا نام حروف عددی ہونا چاہئے صرف حرف سے شروع ہوتا ہے مثال کے طور پر",
                characterOnly:"مکمل نام صرف حروف کو قبول کرتا ہے",
                signUpPhone:"براہ کرم ایک درست فون نمبر کی وضاحت کریں جس کا آغاز مثبت عدد کے ساتھ ہو۔",
                phoneAll: "Pبراہ کرم ایک درست ای میل کی وضاحت کریں",
                required: "اس کو پر کرنا ضروری ہے",
                remote: "براہ کرم اس فیلڈ کو ٹھیک کریں۔",
                email: "برائے مہربانی قابل قبول ای میل ایڈریس لکھیں",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "براہ کرم وہی پاس ورڈ دوبارہ درج کریں.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),

                minlength: jQuery.validator.format("براہ کرم کم از کم {0} حروف درج کریں۔"),
                rangelength: jQuery.validator.format(
                    "Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("براہ کرم {0} سے زیادہ یا اس کے برابر کوئی رقم درج کریں۔")
            });
            //___________Alert Messages On Local____________________//
            var AlertMessage = {
                visibilityStatus: "پروفائل کی حیثیت کامیابی کے ساتھ اپ ڈیٹ ہو گئی ہے۔",
                success: "آپ کا ریکارڈ کامیابی کے ساتھ محفوظ ہو گیا ہے۔",
                subscription: "!!آپ نے مصطفائی تحریک کے پورٹل کو کامیابی سے سبسکرائب کر لیا ہے",
                wrong_fields: 'غلط فیلڈز',
                event: "!!ایونٹ کامیابی کے ساتھ شامل ہو گیا",
                done: "!ہو گیا",
                oops: '!معذرت',
                error: "کچھ غلط ہو گیا",
                info: "معلومات",
                emailexist: "آپ کا ای میل پہلے سے موجود ہے۔",
                login_error: 'صارف کا نام یا پاس ورڈ غلط ہے',
                order_place: "!!آپ کا آرڈر کامیابی کے ساتھ دیا گیا ہے",
                addCart: "!!پروڈکٹ کو کارٹ میں کامیابی کے ساتھ شامل کیا گیا ہے",
                contactUs: "!!آپ کا استفسار کامیابی سے جمع کر دیا گیا ہے",
                comment_delete: "!!آپ کا تبصرہ کامیابی سے حذف ہو گیا",
                empty_cart: "!!آپ کی ٹوکری اس وقت خالی ہے",
                signUpPhone:"براہ کرم ایک درست فون نمبر کی وضاحت کریں جس کا آغاز مثبت عدد کے ساتھ ہو۔",
                invalid_file: "براہ کرم ایک تصویری فائل منتخب کریں!۔",
                invalid_file_type: "غلط فائل کا انتخاب کیا گیا",
                invalid_file_message: "!براہ کرم ایک درست فائل منتخب کریں",
                enter_somethong: "!براہ کرم ایک درست ان پٹ درج کریں۔",
                search_empty: "آپ کو تلاش کے لیے کوئی بھی متن شامل کرنا چاہیے",







            }
            break;
        case 'arabic':
            //_________Add New Methods In Jquery Validation___________________///
            jQuery.validator.addMethod("phoneNumber", function(phone_number, element) {
                phone_number = phone_number.replace(/\s+/g, "");
                return this.optional(element) || phone_number.length < 15 && phone_number.length > 10 &&
                    phone_number.match(
                        /(\+|00|0)(92|297|93|244|1264|358|355|376|971|54|374|1684|1268|61|43|994|257|32|229|226|880|359|973|1242|387|590|375|501|1441|591|55|1246|673|975|267|236|1|61|41|56|86|225|237|243|242|682|57|269|238|506|53|5999|61|1345|357|420|49|253|1767|45|1809|1829|1849|213|593|20|291|212|34|372|251|358|679|500|33|298|691|241|44|995|44|233|350|224|590|220|245|240|30|1473|299|502|594|1671|592|852|504|385|509|36|62|44|91|246|353|98|964|354|972|39|1876|44|962|81|76|77|254|996|855|686|1869|82|383|965|856|961|231|218|1758|423|94|266|370|352|371|853|590|212|377|373|261|960|52|692|389|223|356|95|382|976|1670|258|222|1664|596|230|265|60|262|264|687|227|672|234|505|683|31|47|977|674|64|968|92|507|64|51|63|680|675|48|1787|1939|850|351|595|970|689|974|262|40|7|250|966|249|221|65|500|4779|677|232|503|378|252|508|381|211|239|597|421|386|46|268|1721|248|963|1649|235|228|66|992|690|993|670|676|1868|216|90|688|886|255|256|380|598|1|998|3906698|379|1784|58|1284|1340|84|678|681|685|967|27|260|263)(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{4,20}$/
                    );
            }, "الرجاء تحديد رقم هاتف صالح");
            jQuery.validator.addMethod("isEmail", function(email, element) {
                email = email.replace(/\s+/g, "");
                return this.optional(element) || email.length > 9 &&
                    email.match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            }, "الرجاء تحديد بريد إلكتروني صالح");
            jQuery.extend(jQuery.validator.messages, {
                alphanumeric:"username123 يجب أن يكون اسم المستخدم أبجديًا رقميًا يبدأ من حرف فقط على سبيل المثال ",
                characterOnly:"يقبل الاسم الكامل الأحرف فقط",
                signUpPhone:". الرجاء تحديد رقم هاتف صالح يبدأ بعدد صحيح موجب",
                required: "هذه الخانة مطلوبه",
                remote: "الرجاء تصحيح هذا الحقل",
                email: "يرجى إدخال عنوان بريد إلكتروني صالح",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
                rangelength: jQuery.validator.format(
                    "Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
            });
            //___________Alert Messages On Local____________________//
            var AlertMessage = {
                visibilityStatus: "تم تحديث حالة الملف الشخصي بنجاح",
                success: "تم حفظ السجل الخاص بك بنجاح",
                subscription: "!!لقد تم اشتراكك في بوابة مصطفي تحريك بنجاح",
                wrong_fields: 'الحقول الخاطئة',
                event: "!!انضم الحدث بنجاح",
                done: "!فعله",
                oops: '!تحذير',
                error: "هناك خطأ ما",
                info: "معلومات",
                emailexist: "بريدك الإلكتروني موجود بالفعل",
                login_error: 'اسم المستخدم أو كلمة المرور غير صحيحة',
                order_place: "!!تم وضع طلبك بنجاح",
                addCart: "!!تمت إضافة المنتج إلى عربة التسوق بنجاح",
                contactUs: "!!تم تقديم استفسارك بنجاح",
                comment_delete: "!!تم حذف تعليقك بنجاح",
                empty_cart: "!!عربة التسوق الخاصة بك فارغة الآن",
                enter_somethong: "!الرجاء إدخال إدخال صالح.",



            }
            break;
        default:
            jQuery.extend(jQuery.validator.messages, {
                required: "This field is required.",
                remote: "Please fix this field.",
                email: "Please enter a valid email address.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
                rangelength: jQuery.validator.format(
                    "Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
            });
            //___________Alert Messages On Local____________________//
            var AlertMessage = {
                success: "Your Record Is Successfully Saved",
                visibilityStatus: "Profile",
                done: "done",
                oops: 'Oops!',
                error: "something Went Wrong",
                info: "info",
                signUpPhone:". الرجاء تحديد رقم هاتف صالح يبدأ بعدد صحيح موجب",

            }

    }
    // });
</script>
