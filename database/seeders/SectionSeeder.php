<?php

namespace Database\Seeders;

use App\Models\Admin\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create([
            'name_english' => 'Management',
            'name_urdu' => 'انتظام',
            'name_arabic' => 'إدارة',
            'content_english' => 'Management',
            'content_urdu' => 'انتظام',
            'content_arabic' => 'إدارة',
            'status' => 1,
        ]);
        Section::create([
            'name_english' => 'Our Proud',
            'name_urdu' => 'ہمارا فخر',
            'name_arabic' => 'فخورون',
            'content_english' => 'Our Proud',
            'content_urdu' => 'ہمارا فخر',
            'content_arabic' => 'فخورون',
            'status' => 1,
        ]);
        Section::create([
            'name_english' => 'Wo Jo Ham May Nahi Rahy',
            'name_urdu' => 'سابق ملازم',
            'name_arabic' => 'موظف سابق',
            'content_english' => 'Wo Jo Ham May Nahi Rahy',
            'content_urdu' => 'سابق ملازم',
            'content_arabic' => 'سابق ملازم',
            'status' => 1,
        ]);
    }
}
