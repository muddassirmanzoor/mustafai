<?php

namespace Database\Seeders;

use App\Models\Admin\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Slider::create([
            'admin_id' => 1,
            'image' => 'images/banner_images/banner-bg.png',
            'title' => 'al-mustafai title',
            'content_english' => ' <h1 class="text-captilize text-white text-center main-heading">And <span>Allah</span> Invites To The HOME OF PEACE</h1><div class="banner-video-content d-flex justify-content-center align-items-center"><a class="white-hover-bg theme-btn" href="/about-us">Learn more</a><div class="modal fade library-detail common-model-style banner-video-modal" id="playvideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-green">Video</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body" id="modal-img"><div class="row"><div class="col-12"><div class="image-outer-wrap"><video width="100%" id="model_video" src="" controls></video></div></div></div></div></div></div></div><button class="paly-video-btn" data-bs-toggle="modal" role="button"><span class="paly-btn"></span> PLAY VIDEO</button></div>',
            'content_urdu' => ' <h1 class="text-captilize text-white text-center main-heading">اور <span>اللہ </span> امن کے گھر کی دعوت دیتا ہے۔</h1> <div class="banner-video-content d-flex justify-content-center align-items-center"> <a class="theme-btn white-hover-bg" href="/about-us">اورجانیے</a> <div class="modal fade library-detail common-model-style banner-video-modal" id="playvideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-green">ویڈیو </h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body" id="modal-img"><div class="row"><div class="col-12"><div class="image-outer-wrap"><video width="100%" id="model_video" src="" controls></video></div></div></div></div></div></div></div><button class="paly-video-btn" data-bs-toggle="modal" role="button"><span class="paly-btn"></span> ویڈیو چلائیں۔</button></div>',
            'content_arabic' => '<h1 class="text-captilize text-white text-center main-heading">و <span>اللہ </span> يدعو الى بيت السلام</h1> <div class="banner-video-content d-flex justify-content-center align-items-center"> <a class="theme-btn white-hover-bg" href="/about-us">يتعلم أكثر</a> <div class="modal fade library-detail common-model-style banner-video-modal" id="playvideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-green">شغل</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body" id="modal-img"><div class="row"><div class="col-12"><div class="image-outer-wrap"><video width="100%" id="model_video" src="" controls></video></div></div></div></div></div></div></div><button class="paly-video-btn" data-bs-toggle="modal" role="button"><span class="paly-btn"></span> شغل الفيديو</button></div>',
            'status' => 1,
        ]);
    }
}
