<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use App\Models\Admin\EmployeeSection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            CountryCodesTableSeeder::class,
            AdminUserSeeder::class,
            RoleSeeder::class,
            EventSeeder::class,
            SliderSeeder::class,
            CeoMessageSeeder::class,
            SectionSeeder::class,
            LibrarySeeder::class,
            CountryCodesTableSeeder::class,
            VendorSeeder::class,
            HeaderSeeder::class,
            PageSeeder::class,
            EmployeeSectionSeeder::class,
            TestimonialSeeder::class,
            LibraryContentSeeder::class,
            PostSeeder::class,
            NewsSeeder::class,
            UserSeeder::class,
            DonationSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ModuleSeeder::class,
            Settingseeder::class,
            PaymentMethodSeeder::class,
            DonationRecieptSeeder::class,
            ShipmentChargesSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
