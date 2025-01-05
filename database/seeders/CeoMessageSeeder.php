<?php

namespace Database\Seeders;

use App\Models\Admin\CeoMessage;
use Illuminate\Database\Seeder;

class CeoMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CeoMessage::create([
            'admin_id' => 1,
            'message_title' => 'al-mustafai message title',
            'image' => 'images/ceo-message/ameer-ceo.png',
            'message_english' => '<h3 class="text-white">Message from Ameer (CEO)</h3> <p class="text-white mt-md-4 mt-3">Life is getting difficult day by day. The blood is growing thinner and thinner. The hatred is growing in the hearts. The brother is an enemy of his brother. The friend cheats on his friend, and humankind is preying upon each other. Everybody worships himself.</p><p class="text-white mt-md-4 mt-3">The brother is an enemy of his brother. The friend cheats on his friend, and humankind is preying upon each other. Everybody worships himself.</p><div class="block-component"> <blockquote class="callout quote EN">Nobody is concerned about others. Nobody pays if someone is hungry or ill or distressed or is in some trouble. Everyone seems to be self-centered.</blockquote> </div>',
            'message_urdu' => '<h3 class="text-white">امیر (سی ای او) کا پیغام </h3> <p class="text-white mt-md-4 mt-3">زندگی دن بہ دن مشکل ہوتی جا رہی ہے۔ خون پتلا اور پتلا ہوتا جا رہا ہے۔ دلوں میں نفرت بڑھ رہی ہے۔ بھائی اپنے بھائی کا دشمن ہے۔ دوست اپنے دوست کو دھوکہ دیتا ہے، اور انسان ایک دوسرے کا شکار کر رہے ہیں۔ ہر کوئی اپنی عبادت کرتا ہے۔</p><p class="text-white mt-md-4 mt-3">بھائی اپنے بھائی کا دشمن ہے۔ دوست اپنے دوست کو دھوکہ دیتا ہے، اور انسان ایک دوسرے کا شکار کر رہے ہیں۔ ہر کوئی اپنی عبادت کرتا ہے۔ .</p><div class="block-component"> <blockquote class="callout quote EN">کسی کو دوسروں کی فکر نہیں۔ اگر کوئی بھوکا یا بیمار یا پریشان ہو یا کسی پریشانی میں ہو تو کوئی بھی ادائیگی نہیں کرتا ہے۔ ہر کوئی خود غرض لگتا ہے۔ </blockquote> </div>',
            'message_arabic' => '<h3 class="text-white">   رسالة أمير (الرئيس التنفيذي) </h3> <p class="text-white mt-md-4 mt-3"> الحياة تزداد صعوبة يوما بعد يوم. ينمو الدم أرق وأرق. الكراهية تنمو في القلوب. الأخ عدو لأخيه. الصديق يخون صديقه ، والبشرية تتغذى على بعضها البعض. الجميع يعبد نفسه </p><p class="text-white mt-md-4 mt-3">الأخ عدو لأخيه. الصديق يخون صديقه ، والبشرية تتغذى على بعضها البعض. الجميع يعبد نفسه  .</p><div class="block-component"> <blockquote class="callout quote EN">لا أحد يهتم بالآخرين. لا يدفع أحد إذا كان الشخص جائعًا أو مريضًا أو مكتئبًا أو في مشكلة ما. يبدو أن الجميع أناني </blockquote> </div>',
            'status' => 1,
        ]);
    }
}
