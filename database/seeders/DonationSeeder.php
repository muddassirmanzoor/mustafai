<?php

namespace Database\Seeders;

use App\Models\Admin\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Donation::create([
            'title_english' => '"Give charity without delay, for it stands in the way of calamity.',
            'title_urdu' => 'صدقہ بلا تاخیر کرو کیونکہ یہ مصیبت کے راستے میں حائل ہے۔',
            'title_arabic' => 'صدقہ بلا تاخیر کرو کیونکہ یہ مصیبت کے راستے میں حائل ہے۔',
            'description_english' => '<p class="text-c-1 graish-color">No Child Should Be Subjected to Poverty and Lack……</p>',
            'description_urdu' => '<p class="text-c-1 graish-color">کسی بچے کو غربت اور تنگدستی کا نشانہ نہیں بنایا جانا چاہیے....</p>',
            'description_arabic' => '<p class="text-c-1 graish-color">لا ينبغي أن يتعرض أي طفل للفقر والافتقار ........</p>',
            'price' => "300.00",
            'is_featured' => 1,
            'donation_type' => 1,
            'status' => 1,
        ]);
    }
}
