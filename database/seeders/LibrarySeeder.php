<?php

namespace Database\Seeders;

use App\Models\Admin\Library;
use App\Models\Admin\LibraryType;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LibraryType::create([
            'title_english' => 'image library',
            'title_urdu' => 'تصویری لائبریری',
            'title_arabic' => 'مكتبة الصور',
            'icon' => '/images/library-types-icon/image-icon.png',
            'content_english' => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna. Etiam nec rhoncus neque. Sed quis ultrices eros. Curabitur sit amet eros eu arcu consectetur pulvinar. Aliquam sodales, turpis eget tristique tempor, sapien lacus facilisis diam, molestie efficitur sapien velit nec magna. Maecenas interdum efficitur tempor. Quisque scelerisque id odio nec dictum. Donec sed pulvinar tortor. m.',
            'content_urdu' => ' لوریم اِپسم پرنٹنگ اور ٹائپسیٹنگ انڈسٹری کا صرف ڈمی متن ہے۔ لورینم ایپسم 1500s کے بعد سے ہی اس صنعت کا معیاری ڈمی متن رہا ہے',
            'content_arabic' => 'الأخبار والتحليلات من الشرق الأوسط والعالم ، الوسائط المتعددة والتفاعلات ، الآراء ، الأفلام الوثائقية ، البودكاست ، القراءات الطويلة وجدول البث.',
            'status' => 1,
        ]);

        LibraryType::create([
            'title_english' => 'video library',
            'title_urdu' => 'ویڈیو لائبریری',
            'title_arabic' => 'مكتبة الفيديو',
            'icon' => '/images/library-types-icon/video-icon.png',
            'content_english' => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna. Etiam nec rhoncus neque. Sed quis ultrices eros. Curabitur sit amet eros eu arcu consectetur pulvinar. Aliquam sodales, turpis eget tristique tempor, sapien lacus facilisis diam, molestie efficitur sapien velit nec magna. Maecenas interdum efficitur tempor. Quisque scelerisque id odio nec dictum. Donec sed pulvinar tortor. m.',
            'content_urdu' => ' لوریم اِپسم پرنٹنگ اور ٹائپسیٹنگ انڈسٹری کا صرف ڈمی متن ہے۔ لورینم ایپسم 1500s کے بعد سے ہی اس صنعت کا معیاری ڈمی متن رہا ہے',
            'content_arabic' => 'الأخبار والتحليلات من الشرق الأوسط والعالم ، الوسائط المتعددة والتفاعلات ، الآراء ، الأفلام الوثائقية ، البودكاست ، القراءات الطويلة وجدول البث.',
            'status' => 1,
        ]);
        LibraryType::create([
            'title_english' => 'audio library',
            'title_urdu' => 'آڈیو لائبریری',
            'title_arabic' => 'مكتبة الصوت',
            'icon' => '/images/library-types-icon/audio-icon.png',
            'content_english' => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna. Etiam nec rhoncus neque. Sed quis ultrices eros. Curabitur sit amet eros eu arcu consectetur pulvinar. Aliquam sodales, turpis eget tristique tempor, sapien lacus facilisis diam, molestie efficitur sapien velit nec magna. Maecenas interdum efficitur tempor. Quisque scelerisque id odio nec dictum. Donec sed pulvinar tortor. m.',
            'content_urdu' => ' لوریم اِپسم پرنٹنگ اور ٹائپسیٹنگ انڈسٹری کا صرف ڈمی متن ہے۔ لورینم ایپسم 1500s کے بعد سے ہی اس صنعت کا معیاری ڈمی متن رہا ہے',
            'content_arabic' => 'الأخبار والتحليلات من الشرق الأوسط والعالم ، الوسائط المتعددة والتفاعلات ، الآراء ، الأفلام الوثائقية ، البودكاست ، القراءات الطويلة وجدول البث.',
            'status' => 1,
        ]);
        LibraryType::create([
            'title_english' => 'book library',
            'title_urdu' => 'کتاب کی لائبریری',
            'title_arabic' => 'مكتبة الكتاب',
            'icon' => '/images/library-types-icon/book-library-icon.png',
            'content_english' => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna. Etiam nec rhoncus neque. Sed quis ultrices eros. Curabitur sit amet eros eu arcu consectetur pulvinar. Aliquam sodales, turpis eget tristique tempor, sapien lacus facilisis diam, molestie efficitur sapien velit nec magna. Maecenas interdum efficitur tempor. Quisque scelerisque id odio nec dictum. Donec sed pulvinar tortor. m.',
            'content_urdu' => ' لوریم اِپسم پرنٹنگ اور ٹائپسیٹنگ انڈسٹری کا صرف ڈمی متن ہے۔ لورینم ایپسم 1500s کے بعد سے ہی اس صنعت کا معیاری ڈمی متن رہا ہے',
            'content_arabic' => 'الأخبار والتحليلات من الشرق الأوسط والعالم ، الوسائط المتعددة والتفاعلات ، الآراء ، الأفلام الوثائقية ، البودكاست ، القراءات الطويلة وجدول البث.',
            'status' => 1,
        ]);
        LibraryType::create([
            'title_english' => 'document library',
            'title_urdu' => 'دستاویز لائبریری',
            'title_arabic' => 'مكتبة المستندات',
            'icon' => '/images/library-types-icon/file-icon.png',
            'content_english' => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna. Etiam nec rhoncus neque. Sed quis ultrices eros. Curabitur sit amet eros eu arcu consectetur pulvinar. Aliquam sodales, turpis eget tristique tempor, sapien lacus facilisis diam, molestie efficitur sapien velit nec magna. Maecenas interdum efficitur tempor. Quisque scelerisque id odio nec dictum. Donec sed pulvinar tortor. m.',
            'content_urdu' => ' لوریم اِپسم پرنٹنگ اور ٹائپسیٹنگ انڈسٹری کا صرف ڈمی متن ہے۔ لورینم ایپسم 1500s کے بعد سے ہی اس صنعت کا معیاری ڈمی متن رہا ہے',
            'content_arabic' => 'الأخبار والتحليلات من الشرق الأوسط والعالم ، الوسائط المتعددة والتفاعلات ، الآراء ، الأفلام الوثائقية ، البودكاست ، القراءات الطويلة وجدول البث.',
            'status' => 1,
        ]);
    }
}
