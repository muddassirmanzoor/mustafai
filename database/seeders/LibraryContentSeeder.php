<?php

namespace Database\Seeders;

use App\Models\Admin\Library;
use Illuminate\Database\Seeder;

class LibraryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Library::insert(
            array(
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-1.png',
                    'description' => 'Ahmad',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-2.png',
                    'description' => 'Ahmad',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-3.png',
                    'description' => 'Ahmad',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-4.png',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-5.png',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-6.png',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-7.png',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 1,
                    'file' => 'images/library_images/library-img-8.png',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 2,
                    'file' => 'images/library_images/dummy-video1.mp4',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 2,
                    'file' => 'images/library_images/dummy-video2.mp4',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 3,
                    'file' => 'images/library_images/dummy-audio1.wav',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 3,
                    'file' => 'images/library_images/dummy-audio2.wav',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 4,
                    'file' => 'images/library_images/dummy-book1.pdf',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 4,
                    'file' => 'images/library_images/dummy-book2.pdf',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 5,
                    'file' => 'images/library_images/dummy-document1.pdf',
                    'description' => 'dummy',
                    'status' => 1,
                ),
                array(
                    'type_id' => 5,
                    'file' => 'images/library_images/dummy-document2.pdf',
                    'description' => 'dummy',
                    'status' => 1,
                ),
            )
        );
    }
}
