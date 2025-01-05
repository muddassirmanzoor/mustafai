<?php

namespace Database\Seeders;

use App\Models\Admin\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::insert(
            array(
                array(
                    'id' => 1,
                    'name_english' => 'English',
                    'name_urdu' => 'انگریزی',
                    'name_arabic' => 'إنجليزي',
                    'key' => 'english',
                    'status' => 1,
                ),
                array(
                    'id' => 2,
                    'name_english' => 'Urdu',
                    'name_urdu' => 'اردو',
                    'name_arabic' => 'الأردية',
                    'key' => 'urdu',
                    'status' => 1,
                ),
                array(
                    'id' => 3,
                    'name_english' => 'Arabic',
                    'name_urdu' => 'عربی',
                    'name_arabic' => 'عربي',
                    'key' => 'arabic',
                    'status' => 1,
                ),
            )
        );
    }
}
