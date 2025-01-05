<?php

namespace App\Helper;

use App\Models\Posts\PostFile\PostFile;
use Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimize
{
    public static function optimize($file, $post)
    {
        // video file types
         $videoFileTypes = ['mp4', 'mov', 'wmv', 'avi', 'flv', 'swf', 'mkv', 'webm'];
        // generate random file name
        $fileName = 'post' . time() . rand(1, 100) . '.' . $file->extension();
        if (in_array($file->extension(), $videoFileTypes)) {
            self::uploadVideo($file, $post, $fileName);
            return;
        }
        // given file types
        $mimeTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/svg', 'image/gif', 'image/webp'];
        // get image instance
        $img = Image::make($file->getRealPath());
        // get size of file
        $size = $img->filesize();
        // get type of file
        $mime = $img->mime();
        // if file is image and size >= 1mb
        // if (in_array($mime, $mimeTypes) && $size >= 1000000) {
        if (in_array($mime, $mimeTypes)) {
            $img->resize(900, 800, function ($constraint) {
                $constraint->aspectRatio();
            });
            // ->save(public_path('post-images/' . $fileName));
            $path=$file->storeAs(
                'post-images',
                $fileName,
                's3'
            );
        } else {
            $path=$file->storeAs(
                'post-images',
                $fileName,
                's3'
            );
            // $file->move(public_path('post-images'), $fileName);
        }
        // dd($path);
        // specify database path
        // $path = 'post-images/' . $fileName;
        // save in database
        PostFile::create(['file' => $path, 'post_id' => $post->id]);
    }

    /**
     * @param $file
     * @param $dirName
     * @return string
     */
    public static function improve($file, $dirName): string
    {
        // generate random file name
        // $fileName = time() . uniqid() . '.' . rand(1, 100) . '.' . $file->extension();
        $fileName = time() . uniqid() . '.' . rand(1, 100) . '.' . 'webp';
        // given file types
        $mimeTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/svg', 'image/gif', 'image/webp'];
        // get image instance
        $img = Image::make($file->getRealPath());
        // get size of file
        $size = $img->filesize();
        // get type of file
        $mime = $img->mime();
        // if file is image
        if (in_array($mime, $mimeTypes)) {
            $img->resize(900, 800, function ($constraint) use ($dirName) {
                $constraint->aspectRatio();
                // create directory if does'nt exist
                // if (!file_exists($dirName)) {
                //     mkdir($dirName, 0777, true);
                // }
            });
            // ->save(public_path($dirName . '/' . $fileName));
            $path=$file->storeAs(
                $dirName,
                $fileName,
                's3'
            );

            // return $dirName . '/' . $fileName;
            return $path;
        } else {
            // create directory if does'nt exist
            // if (!file_exists($dirName)) {
            //     mkdir($dirName, 777, true);
            // }
            // $file->move(public_path($dirName), $fileName);

            // return $dirName . '/' . $fileName;
            $path=$file->storeAs(
                $dirName,
                $fileName,
                's3'
            );
            return $path;
        }
    }

    public static function improveSlider($file, $dirName): string
    {
        // generate random file name
        $fileName = time() . uniqid() . '.' . rand(1, 100) . '.' . 'webp';
        // given file types
        $mimeTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/svg', 'image/gif', 'image/webp'];
        // get image instance
        $img = Image::make($file->getRealPath());
        // get size of file
        $size = $img->filesize();
        // get type of file
        $mime = $img->mime();
        // if file is image
        if (in_array($mime, $mimeTypes)) {
            $img->resize(1920, 950, function ($constraint) use ($dirName) {
                $constraint->aspectRatio();
                // create directory if does'nt exist
                // if (!file_exists($dirName)) {
                //     mkdir($dirName, 0777, true);
                // }
            })->save(public_path($dirName . '/' . $fileName));

            return $dirName . '/' . $fileName;
        } else {
            // create directory if does'nt exist
            if (!file_exists($dirName)) {
                mkdir($dirName, 777, true);
            }
            $file->move(public_path($dirName), $fileName);

            return $dirName . '/' . $fileName;
        }
    }

    /**
     * @param $model
     * @return string
     */
    protected static function className($model): string
    {
        $name = explode("\\", $model);
        return strtolower(end($name));
    }

    public static function uploadVideo($file, $post, $fileName)
    {
        // $file->move(public_path('post-images'), $fileName);
        Storage::disk('s3')->put('post-images', $fileName);
        // specify database path
        $path = 'post-images/' . $fileName;
        // save in database
        PostFile::create(['file' => $path, 'post_id' => $post->id]);
    }
}
