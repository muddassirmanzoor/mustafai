<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Admin\Setting;

class SettingController extends Controller
{
    /**
     * listing the Setting
    */
    public function index()
    {
        if (!have_right('Edit-Site-Setting')) {
            access_denied();
        }

        $result = DB::table('settings')->get()->toArray();
        $row = [];
        foreach ($result as $value) {
            $row[$value->option_name] = $value->option_value;
        }
        $data['settings'] = $row;
        $data['roles'] = Role::where(['type' => 2, 'status' => 1])->get();
        return view('admin.settings')->with($data);
    }

    /**
     * storing the Setting
    */
    public function store(Request $request)
    {
        if (!have_right('Edit-Site-Setting')) {
            access_denied();
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'email' => 'required|string|max:200',
            'phone' => 'required|string|max:200',
        ]);
        if ($validator->fails()) {
            return redirect('admin/site-setting')->with('error', $validator->messages());
        }
        if (session()->has('settingsArray')) {
            session()->forget('settingsArray');
        }
        $input = $request->all();
        unset($input['_token']);
        // $input['logo'] = 'adsfds0';
        
        if (isset($input['logo'])) {
            //$imagePath = $this->uploadImage($request);
            $imagePath = uploadS3File($request , "images/logo" ,"logo","logo",$filename = null);
            $input['logo'] = $imagePath;
        }
        if (isset($input['video'])) {
            //$vidPath = $this->uploadVideo($request);
            $vidPath = uploadS3File($request , "videos/video" ,"video","video",$filename = null);
            $input['video'] = $vidPath;
        }
        foreach ($input as $key => $value) {
            $result = DB::table('settings')->where('option_name', $key)->get();

            if ($result->isEmpty()) {
                DB::table('settings')->insert(['option_name' => $key, 'option_value' => $value]);
            } else {
                DB::table('settings')->where('option_name', $key)->update(['option_value' => $value]);
                $settingsdata = Setting::all()->toArray();
                $sortedArray = array_column($settingsdata, 'option_value', 'option_name');
                session()->put('settingsArray', $sortedArray);
            }
        }
        Session::flash('flash_success', 'Site Settings has been updated successfully.');
        return redirect()->back()->with('message', 'Site Settings has been updated successfully.');
    }

    /**
     * uploading image the Setting
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->logo) {
            $imageName = 'logo' . time() . '.' . $request->logo->extension();
            if ($request->logo->move(public_path('images/logo'), $imageName)) {
                $path = 'images/logo/' . $imageName;
            }
        }
        return $path;
    }

    /**
     * uploading video the Setting
    */
    public function uploadVideo(Request $request)
    {
        $path = '';
        if ($request->video) {
            $videoName = 'video' . time() . '.' . $request->video->extension();
            if ($request->video->move(public_path('videos/video'), $videoName)) {
                $path = 'videos/video/' . $videoName;
            }
        }
        return $path;
    }
}
