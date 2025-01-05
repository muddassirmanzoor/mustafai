<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'category_id' => 1,
            'vendor_id' => 1,
            'name_english' => ' Al-Jumuah ',
            'name_urdu' => '   الجمعہ',
            'name_arabic' => ' الجمعہ',
            'description_english' => ' Al-Jumuah ',
            'description_urdu' => ' الجمعہ ',
            'description_arabic' => ' الجمعہ ',
            'file_name' => 'files/product-files/myBook.pdf',
            'file_type' => 1,
            'featured' => 1,
            'new' => 1,
            'price' => 500,
            'status' => 1,
        ]);

        ProductImage::create([
            'product_id' => 1,
            'file_name' => 'images/products-images/product_image.png',
        ]);

        Product::create([
        'category_id' => 1,
        'vendor_id' => 1,
        'name_english' => ' Al-Jumuah 2 ',
        'name_urdu' => '   الجمعہ',
        'name_arabic' => ' الجمعہ',
        'description_english' => ' Al-Jumuah ',
        'description_urdu' => ' الجمعہ ',
        'description_arabic' => ' الجمعہ ',
        'file_name' => 'files/product-files/myBook.pdf',
        'file_type' => 1,
        'featured' => 0,
        'new' => 1,
        'price' => 500,
        'status' => 1,
        ]);

        ProductImage::create([
            'product_id' => 2,
            'file_name' => 'images/products-images/product165468245562a07357560c1.png',
        ]);
    }
}
