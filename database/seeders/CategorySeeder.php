<?php

namespace Database\Seeders;

use App\Models\Admin\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name_english' => '"Our Magzine.',
            'name_urdu' => '  مجلة اوت',
            'name_arabic' => '   آؤٹ میگزین  ',
            'status' => 1,
        ]);
    }
}
