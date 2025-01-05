<?php

namespace Database\Seeders;

use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post1 = Post::create([
            'admin_id' => 1,
            'post_type' => 1,
            'title_english' => 'Mustafai is awesome plateform, whats your point',
            'title_urdu' => 'Mustafai is awesome plateform, whats your point',
            'title_arabic' => 'Mustafai is awesome plateform, whats your point',
            'status' => 1,
        ]);

        PostFile::create([
            'post_id' => $post1->id,
            'file' => 'post-images/page1653989721.jpg'
        ]);

        $post2 = Post::create([
            'admin_id' => 1,
            'post_type' => 1,
            'title_english' => 'Happy to share that, i got a job',
            'title_urdu' => 'Happy to share that, i got a job',
            'title_arabic' => 'Happy to share that, i got a job',
            'status' => 1,
        ]);

    }
}
