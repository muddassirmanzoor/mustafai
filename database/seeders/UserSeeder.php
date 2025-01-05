<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arslan = User::create([
            'user_name' => 'Aamir Ali',
            'email' => 'aamir@gmail.com',
            'phone_number' => '03001112233',
            'password' =>  bcrypt('password'),
            'email_verified_at' => '2022-06-20 10:52:25',
            'original_password' => 'password',
            'status' => 1,
            'is_aproved' => 1,
            'welcome_mail_status' => 1,
        ]);

        $arslanPost = $arslan->posts()->create([
            'post_type' => 1,
            'title_english' => 'in the name of allah who is most merciful',
            'title_urdu' => 'in the name of allah who is most merciful',
            'title_arabic' => 'in the name of allah who is most merciful',
            'status' => 1,

        ]);


        $arslanPost->images()->create([
            'file' => 'post-images/document-1.png'
        ]);

        $arslanJobPost = $arslan->posts()->create([
            'post_type' => 2,
            'job_type' => 2,
            'occupation' => 'teacher',
            'experience' => '2 years',
            'skills' => 'teaching,developing,swimming',
            'title_english' => 'i need this job badly',
            'title_urdu' => 'i need this job badly',
            'title_arabic' => 'i need this job badly',
            'status' => 1
        ]);

        $arslanJobPost->files()->create([
            'file' => 'post-images/sample.pdf'
        ]);

        $mudassir = User::create([
            'user_name' => 'Ahmed Ramzan',
            'email' => 'ahmed@gmail.com',
            'phone_number' => '03002223344',
            'password' =>  bcrypt('password'),
            'email_verified_at' => '2022-06-20 10:52:25',
            'original_password' => 'password', 'status' => 1,
            'is_aproved' => 1,
            'is_public'=>1,
            'welcome_mail_status' => 1,

        ]);

        $mudassirPost = $mudassir->posts()->create([
            'post_type' => 1,
            'title_english' => 'do a hustle, your comfort zone will kill you. be productive in order to remain competition',
            'title_urdu' => 'do a hustle, your comfort zone will kill you. be productive in order to remain competition',
            'title_arabic' => 'do a hustle, your comfort zone will kill you. be productive in order to remain competition',
            'status' => 1
        ]);

        $mudassirPost->images()->create([
            'file' => 'post-images/document-2.png'
        ]);

        $mudassirJobPost = $mudassir->posts()->create([
            'post_type' => 2,
            'job_type' => 2,
            'occupation' => 'doctor',
            'experience' => '3 years',
            'skills' => 'death,life',
            'title_english' => 'i need this job badly',
            'title_urdu' => 'i need this job badly',
            'title_arabic' => 'i need this job badly',
            'status' => 1
        ]);

        $mudassirJobPost->files()->create([
            'file' => 'post-images/sample.pdf'
        ]);

        $umar = User::create([
            'user_name' => 'Saad Shaheen',
            'email' => 'saad@gmail.com',
            'phone_number' => '03003334455',
            'password' =>  bcrypt('password'),
            'email_verified_at' => '2022-06-20 10:52:25',
            'original_password' => 'password', 'status' => 1,
            'welcome_mail_status' => 1,
            'is_aproved' => 1,
            'is_public'=>1


        ]);

        $umarPost = $umar->posts()->create([
            'post_type' => 1,
            'title_english' => 'early success is a scam, great things take time. be humble and wait for your turn',
            'title_urdu' => 'early success is a scam, great things take time. be humble and wait for your turn',
            'title_arabic' => 'early success is a scam, great things take time. be humble and wait for your turn',
            'status' => 1
        ]);

        $umarPost->images()->create([
            'file' => 'post-images/document-3.png'
        ]);

        $umarJobPost = $umar->posts()->create([
            'post_type' => 2,
            'job_type' => 2,
            'occupation' => 'lawyer',
            'experience' => '3 years',
            'skills' => 'lying,truth',
            'title_english' => 'i need this job badly',
            'title_urdu' => 'i need this job badly',
            'title_arabic' => 'i need this job badly',
            'status' => 1
        ]);

        $umarJobPost->files()->create([
            'file' => 'post-images/sample.pdf'
        ]);

        $maria = User::create([
            'user_name' => 'Maria Akram',
            'email' => 'maria@gmail.com',
            'phone_number' => '03004445566',
            'password' =>  bcrypt('password'),
            'email_verified_at' => '2022-06-20 10:52:25',
            'original_password' => 'password', 'status' => 1,
            'is_aproved' => 1,
            'welcome_mail_status' => 1,
            'is_public'=>1

        ]);
    }
}
