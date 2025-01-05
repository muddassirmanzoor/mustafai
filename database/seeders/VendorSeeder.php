<?php

namespace Database\Seeders;

use App\Models\Admin\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::create([
            'name_english' => 'al-mustafai vendor name',
            'name_urdu' => 'al-mustafai vendor name',
            'name_arabic' => 'al-mustafai vendor content',
            'status' => 1,
        ]);
    }
}
