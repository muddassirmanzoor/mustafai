<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create(
            [
                'role_id' => 1,
                'first_name' => 'admin',
                'last_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@admin.com',
                'password' =>  bcrypt('123456789'),
                'origional_password' => '123456789',
                'dob' => '1995-5-24',
                'status' => 1,
                'remember_token' => Str::random(10),

            ]
        );
    }
}
