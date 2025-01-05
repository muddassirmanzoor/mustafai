<?php

namespace Database\Seeders;

use App\Models\Admin\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'title_english' => 'event in lahore for donations',
            'title_urdu' => 'لاہور میں عطیات کے لیے تقریب',
            'title_arabic' => 'حدث في لاهور للتبرعات',
            'content_english' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose',
            'content_urdu' => 'یہ ایک طویل عرصے سے قائم حقیقت ہے کہ ایک قاری کسی صفحے کے پڑھنے کے قابل مواد سے اس کی ترتیب کو دیکھ کر پریشان ہو جائے گا۔ Lorem Ipsum کو استعمال کرنے کا نقطہ یہ ہے کہ اس میں حروف کی کم یا زیادہ عام تقسیم ہے، جیسا کہ یہاں مواد، یہاں مواد استعمال کرنے کے برعکس، اسے پڑھنے کے قابل انگریزی کی طرح نظر آتا ہے۔ بہت سے ڈیسک ٹاپ پبلشنگ پیکجز اور ویب پیج ایڈیٹرز اب Lorem Ipsum کو اپنے ڈیفالٹ ماڈل ٹیکسٹ کے طور پر استعمال کرتے ہیں، اور Lorem Ipsum کی تلاش بہت سی ویب سائٹس کو اب بھی ان کے ابتدائی دور میں ہی کھول دے گی۔ کئی سالوں میں مختلف ورژن تیار ہوئے ہیں، کبھی حادثاتی طور پر، کبھی جان بوجھ کر',
            'content_arabic' => 'وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. الهدف من استخدام Lorem Ipsum هو أنه يحتوي على توزيع طبيعي -إلى حد ما- للأحرف ، بدلاً من استخدام المحتوى هنا ، والمحتوى هنا ، مما يجعله يبدو وكأنه إنجليزي قابل للقراءة. تستخدم العديد من حزم النشر المكتبي ومحرري صفحات الويب الآن Lorem Ipsum كنص نموذج افتراضي ، وسيكشف البحث عن lorem ipsum عن العديد من مواقع الويب التي لا تزال في مهدها. تطورت إصدارات مختلفة على مر السنين ، أحيانًا عن طريق الصدفة ، وأحيانًا عن قصد',
            'location_english' => 'lahore',
            'location_urdu' => 'لاہور',
            'location_arabic' => 'لاهور',
            'start_date_time' => '2022-12-28 08:31:00',
            'end_date_time' => '2022-06-29 08:31:00',
            'image' => 'images/library_images/event-1.png',
            'status' => 1,
        ]);

        Event::create([
            'title_english' => 'needy people needs support',
            'title_urdu' => 'ضرورت مند لوگوں کو مدد کی ضرورت ہے',
            'title_arabic' => 'المحتاجين يحتاجون إلى دعم',
            'content_english' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose',
            'content_urdu' => 'یہ ایک طویل عرصے سے قائم حقیقت ہے کہ ایک قاری کسی صفحے کے پڑھنے کے قابل مواد سے اس کی ترتیب کو دیکھ کر پریشان ہو جائے گا۔ Lorem Ipsum کو استعمال کرنے کا نقطہ یہ ہے کہ اس میں حروف کی کم یا زیادہ عام تقسیم ہے، جیسا کہ یہاں مواد، یہاں مواد استعمال کرنے کے برعکس، اسے پڑھنے کے قابل انگریزی کی طرح نظر آتا ہے۔ بہت سے ڈیسک ٹاپ پبلشنگ پیکجز اور ویب پیج ایڈیٹرز اب Lorem Ipsum کو اپنے ڈیفالٹ ماڈل ٹیکسٹ کے طور پر استعمال کرتے ہیں، اور Lorem Ipsum کی تلاش بہت سی ویب سائٹس کو اب بھی ان کے ابتدائی دور میں ہی کھول دے گی۔ کئی سالوں میں مختلف ورژن تیار ہوئے ہیں، کبھی حادثاتی طور پر، کبھی جان بوجھ کر',
            'content_arabic' => 'وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. الهدف من استخدام Lorem Ipsum هو أنه يحتوي على توزيع طبيعي -إلى حد ما- للأحرف ، بدلاً من استخدام المحتوى هنا ، والمحتوى هنا ، مما يجعله يبدو وكأنه إنجليزي قابل للقراءة. تستخدم العديد من حزم النشر المكتبي ومحرري صفحات الويب الآن Lorem Ipsum كنص نموذج افتراضي ، وسيكشف البحث عن lorem ipsum عن العديد من مواقع الويب التي لا تزال في مهدها. تطورت إصدارات مختلفة على مر السنين ، أحيانًا عن طريق الصدفة ، وأحيانًا عن قصد',
            'location_english' => 'Islamabad',
            'location_urdu' => 'اسلام آباد',
            'location_arabic' => 'اسلام آباد',
            'start_date_time' => '2022-12-12 06:31:00',
            'end_date_time' => '2022-07-15 09:31:00',
            'image' => 'images/library_images/event-2.png',
            'status' => 1,
        ]);

        Event::create([
            'title_english' => 'get books for child',
            'title_urdu' => 'بچوں کے لیے کتابیں حاصل کریں۔',
            'title_arabic' => 'احصل على كتب للأطفال',
            'content_english' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose',
            'content_urdu' => 'یہ ایک طویل عرصے سے قائم حقیقت ہے کہ ایک قاری کسی صفحے کے پڑھنے کے قابل مواد سے اس کی ترتیب کو دیکھ کر پریشان ہو جائے گا۔ Lorem Ipsum کو استعمال کرنے کا نقطہ یہ ہے کہ اس میں حروف کی کم یا زیادہ عام تقسیم ہے، جیسا کہ یہاں مواد، یہاں مواد استعمال کرنے کے برعکس، اسے پڑھنے کے قابل انگریزی کی طرح نظر آتا ہے۔ بہت سے ڈیسک ٹاپ پبلشنگ پیکجز اور ویب پیج ایڈیٹرز اب Lorem Ipsum کو اپنے ڈیفالٹ ماڈل ٹیکسٹ کے طور پر استعمال کرتے ہیں، اور Lorem Ipsum کی تلاش بہت سی ویب سائٹس کو اب بھی ان کے ابتدائی دور میں ہی کھول دے گی۔ کئی سالوں میں مختلف ورژن تیار ہوئے ہیں، کبھی حادثاتی طور پر، کبھی جان بوجھ کر',
            'content_arabic' => 'وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. الهدف من استخدام Lorem Ipsum هو أنه يحتوي على توزيع طبيعي -إلى حد ما- للأحرف ، بدلاً من استخدام المحتوى هنا ، والمحتوى هنا ، مما يجعله يبدو وكأنه إنجليزي قابل للقراءة. تستخدم العديد من حزم النشر المكتبي ومحرري صفحات الويب الآن Lorem Ipsum كنص نموذج افتراضي ، وسيكشف البحث عن lorem ipsum عن العديد من مواقع الويب التي لا تزال في مهدها. تطورت إصدارات مختلفة على مر السنين ، أحيانًا عن طريق الصدفة ، وأحيانًا عن قصد',
            'location_english' => 'Multan',
            'location_urdu' => 'ملتان',
            'location_arabic' => 'ملتان',
            'start_date_time' => '2022-12-21 05:31:00',
            'end_date_time' => '2022-07-23 09:31:00',
            'image' => 'images/library_images/event-2.png',
            'status' => 1,
        ]);
    }
}
