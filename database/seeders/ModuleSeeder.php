<?php

namespace Database\Seeders;

use App\Models\Admin\Module;
use App\Models\Admin\Right;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //_________________Dashboard Rights_____________//
        $dashboard = Module::create([
            'name' => 'Dashboard',
            'type' => '1',
            'status' => 1,
        ]);

        Right::create([
            'module_id' => $dashboard->id,
            'name' => 'View-Dashboard-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $dashboard->id,
            'name' => 'View-Dashboard-Donations',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $dashboard->id,
            'name' => 'View-Dashboard-Users',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $dashboard->id,
            'name' => 'View-Dashboard-Events',
            'status' => 1,
        ]);
        //_________________Admin Rights_____________//
        $admin_module = Module::create([
            'name' => 'Admin',
            'type' => '1',
            'status' => 1,
        ]);

        Right::create([
            'module_id' => $admin_module->id,
            'name' => 'View-Admin',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $admin_module->id,
            'name' => 'Create-Admin',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $admin_module->id,
            'name' => 'Edit-Admin',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $admin_module->id,
            'name' => 'Delete-Admin',
            'status' => 1,
        ]);

        // //_________________User Rights_____________//
        $user_rights_module = Module::create([
            'name' => 'User',
            'type' => '1',
            'status' => 1,
        ]);

        Right::create([
            'module_id' => $user_rights_module->id,
            'name' => 'View-User',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $user_rights_module->id,
            'name' => 'Create-User',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $user_rights_module->id,
            'name' => 'Edit-User',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $user_rights_module->id,
            'name' => 'Delete-User',
            'status' => 1,
        ]);
        // //_________________Roles Rights_____________//
        $roles_module = Module::create([
            'name' => 'Roles Managment',
            'type' => '1',
            'status' => 1,
        ]);

        Right::create([
            'module_id' => $roles_module->id,
            'name' => 'View-Roles-Managment',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $roles_module->id,
            'name' => 'Add-Admin-Roles-Managment',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $roles_module->id,
            'name' => 'Add-User-Roles-Managment',
            'status' => 1,
        ]);
        //_______________Header seeting in Home Settings____________//
        $header_setting_module = Module::create([
            'name' => 'Header Setting',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $header_setting_module->id,
            'name' => 'View-Header-Setting',
            'status' => 1,
        ]);
        //_______________Slider in Home Settings____________//
        $slider_module = Module::create([
            'name' => 'Slider',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $slider_module->id,
            'name' => 'View-Slider',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $slider_module->id,
            'name' => 'Create-Slider',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $slider_module->id,
            'name' => 'Edit-Slider',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $slider_module->id,
            'name' => 'Delete-Slider',
            'status' => 1,
        ]);
        //_______________ceo message in Home Settings____________//
        $ceo_message_module = Module::create([
            'name' => 'Ceo Message',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $ceo_message_module->id,
            'name' => 'View-Ceo-Message',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $ceo_message_module->id,
            'name' => 'Create-Ceo-Message',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $ceo_message_module->id,
            'name' => 'Edit-Ceo-Message',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $ceo_message_module->id,
            'name' => 'Delete-Ceo-Message',
            'status' => 1,
        ]);

        //_______________Contacts in Quries____________//
        $contacts_module = Module::create([
            'name' => 'Contacts',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $contacts_module->id,
            'name' => 'View-Contacts',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $contacts_module->id,
            'name' => 'Delete-Contacts',
            'status' => 1,
        ]);
        //_______________Subscription in Quries____________//
        $contacts_module = Module::create([
            'name' => 'Subscriptions',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $contacts_module->id,
            'name' => 'View-Subscriptions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $contacts_module->id,
            'name' => 'Delete-Subscriptions',
            'status' => 1,
        ]);
        //_______________Addresses Module____________//
        $address_module = Module::create([
            'name' => 'Address',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $address_module->id,
            'name' => 'View-Address',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $address_module->id,
            'name' => 'Create-Address',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $address_module->id,
            'name' => 'Edit-Address',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $address_module->id,
            'name' => 'Delete-Address',
            'status' => 1,
        ]);

        //_______________Library Image Module____________//
        $library_image_module = Module::create([
            'name' => 'Library Image',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $library_image_module->id,
            'name' => 'View-Library-Image',
            'status' => 1,
        ]);

        //_______________Library Video Module____________//
        $library_video_module = Module::create([
            'name' => 'Library Video',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $library_video_module->id,
            'name' => 'View-Library-Video',
            'status' => 1,
        ]);
        //_______________Library audio Module____________//
        $library_audio_module = Module::create([
            'name' => 'Library Audio',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $library_audio_module->id,
            'name' => 'View-Library-Audio',
            'status' => 1,
        ]);
        //_______________Library Book Module____________//
        $library_book_module = Module::create([
            'name' => 'Library Book',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $library_book_module->id,
            'name' => 'View-Library-Book',
            'status' => 1,
        ]);
        //_______________Library Document Module____________//
        $library_document_module = Module::create([
            'name' => 'Library Document',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $library_document_module->id,
            'name' => 'View-Library-Document',
            'status' => 1,
        ]);

        //_______________Pages Module____________//
        $pages_module = Module::create([
            'name' => 'Pages',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $pages_module->id,
            'name' => 'View-Pages',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $pages_module->id,
            'name' => 'Create-Pages',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $pages_module->id,
            'name' => 'Edit-Pages',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $pages_module->id,
            'name' => 'Delete-Pages',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $pages_module->id,
            'name' => 'Set-Featured-Pages',
            'status' => 1,
        ]);

        //_______________Events Module____________//
        $events_module = Module::create([
            'name' => 'Events',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $events_module->id,
            'name' => 'View-Events',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $events_module->id,
            'name' => 'Create-Events',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $events_module->id,
            'name' => 'Edit-Events',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $events_module->id,
            'name' => 'Delete-Events',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $events_module->id,
            'name' => 'Set-Attendence-Events',
            'status' => 1,
        ]);

        //_______________Blood Donors____________//
        $blood_donors_module = Module::create([
            'name' => 'Blood Donors',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $blood_donors_module->id,
            'name' => 'View-Blood-Donors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $blood_donors_module->id,
            'name' => 'Create-Blood-Donors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $blood_donors_module->id,
            'name' => 'Edit-Blood-Donors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $blood_donors_module->id,
            'name' => 'Delete-Blood-Donors',
            'status' => 1,
        ]);
        //_______________Employee Sections____________//
        $employee_sections_module = Module::create([
            'name' => 'Employee Sections',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $employee_sections_module->id,
            'name' => 'View-Employee-Sections',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $employee_sections_module->id,
            'name' => 'Create-Employee-Sections',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $employee_sections_module->id,
            'name' => 'Edit-Employee-Sections',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $employee_sections_module->id,
            'name' => 'Delete-Employee-Sections',
            'status' => 1,
        ]);
        //_______________Employee Sections____________//
        $testimonials_module = Module::create([
            'name' => 'Testimonials',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $testimonials_module->id,
            'name' => 'View-Testimonials',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $testimonials_module->id,
            'name' => 'Create-Testimonials',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $testimonials_module->id,
            'name' => 'Edit-Testimonials',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $testimonials_module->id,
            'name' => 'Delete-Testimonials',
            'status' => 1,
        ]);

        //_______________Doantions Sections____________//
        $doantions_module = Module::create([
            'name' => 'Doantions',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'View-Doantions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'Create-Doantions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'Edit-Doantions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'Delete-Doantions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'View-Reciepts-Doantions',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $doantions_module->id,
            'name' => 'Set-Featured-Doantions',
            'status' => 1,
        ]);

        //_______________Headlines Sections____________//
        $headlines_module = Module::create([
            'name' => 'Headlines',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $headlines_module->id,
            'name' => 'View-Headlines',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $headlines_module->id,
            'name' => 'Create-Headlines',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $headlines_module->id,
            'name' => 'Edit-Headlines',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $headlines_module->id,
            'name' => 'Delete-Headlines',
            'status' => 1,
        ]);

        //_______________ Posts Sections____________//
        $posts_module = Module::create([
            'name' => 'Posts',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $posts_module->id,
            'name' => 'View-Posts',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $posts_module->id,
            'name' => 'Create-Posts',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $posts_module->id,
            'name' => 'Edit-Posts',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $posts_module->id,
            'name' => 'Delete-Posts',
            'status' => 1,
        ]);

        //_______________ Cabinets Sections____________//
        $cabinets_module = Module::create([
            'name' => 'Cabinets',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $cabinets_module->id,
            'name' => 'View-Cabinets',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $cabinets_module->id,
            'name' => 'Create-Cabinets',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $cabinets_module->id,
            'name' => 'Edit-Cabinets',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $cabinets_module->id,
            'name' => 'Delete-Cabinets',
            'status' => 1,
        ]);

        //_______________ Vendors Sections____________//
        $vendors_module = Module::create([
            'name' => 'Vendors',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $vendors_module->id,
            'name' => 'View-Vendors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $vendors_module->id,
            'name' => 'Create-Vendors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $vendors_module->id,
            'name' => 'Edit-Vendors',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $vendors_module->id,
            'name' => 'Delete-Vendors',
            'status' => 1,
        ]);

        //_______________ Categories Sections____________//
        $categories_module = Module::create([
            'name' => 'Categories',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $categories_module->id,
            'name' => 'View-Categories',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $categories_module->id,
            'name' => 'Create-Categories',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $categories_module->id,
            'name' => 'Edit-Categories',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $categories_module->id,
            'name' => 'Delete-Categories',
            'status' => 1,
        ]);

        //_______________ Products Sections____________//
        $products_module = Module::create([
            'name' => 'Products',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'View-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'Create-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'Edit-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'Delete-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'Featured-Products',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $products_module->id,
            'name' => 'Create-Post-Products',
            'status' => 1,
        ]);

        //_______________ Orders Sections____________//
        $orders_module = Module::create([
            'name' => 'Orders',
            'type' => '1',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $orders_module->id,
            'name' => 'View-Orders',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $orders_module->id,
            'name' => 'View-Detail-Orders',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $orders_module->id,
            'name' => 'Ready-Orders',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $orders_module->id,
            'name' => 'Complete-Orders',
            'status' => 1,
        ]);
        Right::create([
            'module_id' => $orders_module->id,
            'name' => 'Delete-Orders',
            'status' => 1,
        ]);
    }
}
