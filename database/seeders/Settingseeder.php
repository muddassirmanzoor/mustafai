<?php

namespace Database\Seeders;

use App\Models\Admin\Setting;
use Illuminate\Database\Seeder;

class Settingseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'option_name' => 'title',
                'option_value' => 'Al-Mustafai',
            ],
            [
                'option_name' => 'email',
                'option_value' => 'mustafai@mustafai.com',
            ],
            [
                'option_name' => 'phone',
                'option_value' => '(+92) 3030457106',
            ],
            [
                'option_name' => 'whatsapp',
                'option_value' => '(+92) 3030457106',
            ],
            [
                'option_name' => 'address_english',
                'option_value' => 'Lahore Punjab',
            ],
            [
                'option_name' => 'address_urdu',
                'option_value' => 'Lahore Punjab',
            ],
            [
                'option_name' => 'address_arabic',
                'option_value' => 'Lahore Punjab',
            ],
            [
                'option_name' => 'opening_time',
                'option_value' => '05:22',
            ],
            [
                'option_name' => 'play_store',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'app_store',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'facebook',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'linkedin',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'pinterest',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'twitter',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'youtube',
                'option_value' => 'https://www.mustafai.com/',
            ],
            [
                'option_name' => 'logo',
                'option_value' => 'assets/home/images/site-logo.png',
            ],
            [
                'option_name' => 'video',
                'option_value' => 'videos/video/video1658834548.mp4',
            ],
        ];
        Setting::insert($data);
    }
}
