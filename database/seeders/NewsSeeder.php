<?php

namespace Database\Seeders;

use App\Models\Admin\Headline;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Headline::create([
            'title_english' => 'Sad to hear that ulla tempus lorem veli purus oreid pharetra hendrit nullasd leifed aeas cursus aenean petra magna aced placerat vestibuld',
            'title_urdu' => '  اللہ کے دین کے مطابق اسلام میں نکاح اتنا مشکل نہیں جتنا بنادیا گیا ہے۔آج ہماری خواہشات اور توقعات اس قدر بڑھ گئ ہیں کہ ہم کہیں بھی کسی بھی معاملے میں سمجھوتہ کرنا ہی نہیں چاہتے۔',
            'title_arabic' => 'بحسب دين الله ، الزواج في الإسلام ليس بالصعوبة التي يجب أن يكون عليها ، واليوم ازدادت رغباتنا وتوقعاتنا لدرجة أننا لا نريد المساومة في أي أمر في أي مكان. ',
            'status' => 1,

        ]);
    }
}
