<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(
            [
                'id' => 1,
                'name_english' => 'super admin',
                'right_ids' => 1,
                'status' => 1,
            ]
        );
    }
}
