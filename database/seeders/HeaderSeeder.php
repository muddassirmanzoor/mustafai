<?php

namespace Database\Seeders;

use App\Models\Admin\HeaderSetting;
use App\Models\Admin\LibraryType;
use Illuminate\Database\Seeder;
use PharIo\Manifest\Library;

class HeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HeaderSetting::create([
            'order' => 1,
            'name_english' => 'Home',
            'name_urdu' => 'گھر',
            'name_arabic' => 'مسكن',
            'link' => '/',
        ]);

        HeaderSetting::create([
            'order' => 2,
            'name_english' => 'Library',
            'name_urdu' => 'کتب خانہ',
            'name_arabic' => 'مكتبة',
            'link' => 'mustlibrary',
        ]);
        HeaderSetting::create([
            'order' => 1,
            'parent_id' => 2,
            'name_english' => 'Image Library',
            'name_urdu' => 'تصویری لائبریری',
            'name_arabic' => 'مكتبة الصور',
            'link' => 'view-library/1',
        ]);

        HeaderSetting::create([
            'order' => 2,
            'parent_id' => 2,
            'name_english' => 'Video Library',
            'name_urdu' => 'ویڈیو لائبریری ',
            'name_arabic' => 'مكتبة الفيديو',
            'link' => 'view-library/2',
        ]);
        HeaderSetting::create([
            'order' => 3,
            'parent_id' => 2,
            'name_english' => 'Audio Library',
            'name_urdu' => 'آڈیو لائبریری',
            'name_arabic' => 'مكتبة الصوت',
            'link' => 'view-library/3',
        ]);
        HeaderSetting::create([
            'order' => 4,
            'parent_id' => 2,
            'name_english' => 'Book Library',
            'name_urdu' => 'کتب خانہ ',
            'name_arabic' => ' مكتبة الكتاب',
            'link' => 'view-library/4',
        ]);
        HeaderSetting::create([
            'order' => 5,
            'parent_id' => 2,
            'name_english' => 'Document Library',
            'name_urdu' => ' دستاویز لائبریری',
            'name_arabic' => ' مكتبة المستندات',
            'link' => 'view-library/5',
        ]);

        HeaderSetting::create([
            'order' => 3,
            'name_english' => 'Services',
            'name_urdu' => ' خدمات',
            'name_arabic' => 'خدمات',
            'link' => '/services',
        ]);

        HeaderSetting::create([
            'order' => 4,
            'name_english' => 'About Us',
            'name_urdu' => 'ہمارے بارے میں',
            'name_arabic' => 'معلومات عنا',
            'link' => '/about-us',
        ]);

        HeaderSetting::create([
            'order' => 1,
            'parent_id' => 4,
            'name_english' => 'Who we are',
            'name_urdu' => 'ہم کون ہیں',
            'name_arabic' => 'من نحن',
            'link' => '/who-we-are',
        ]);

        HeaderSetting::create([
            'order' => 2,
            'parent_id' => 4,
            'name_english' => 'Our Mission',
            'name_urdu' => 'ہمارا مقصد',
            'name_arabic' => 'مهمتنا',
            'link' => '/our-mission',
        ]);

        HeaderSetting::create([
            'order' => 5,
            'name_english' => 'Contact Us',
            'name_urdu' => ' ہم سے رابطہ کریں',
            'name_arabic' => 'اتصل بنا',
            'link' => '/contact-us',
        ]);

        HeaderSetting::create([
            'order' => 6,
            'name_english' => 'Products',
            'name_urdu' => 'مصنوعات',
            'name_arabic' => ' منتجات',
            'link' => '/mustafai-store',
        ]);
    }
}
