<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\Models\User\PermanentAddress;
use App\Models\User\SecondaryPhone;
use App\Helper\UserFee;
use App\Models\User;

class CommonController extends Controller
{
    /**
     * store summernote text editor images.
    */
    function uploadEditoImage(Request $request)
    {
        $path = 'editor-images' . '/' . $request->path;
        // $imageName = 'editor-image' . time() . '.' . $request->image->extension();
        // if ($request->image->move(public_path($path), $imageName)) {
        //     $path =  $path . '/' . $imageName;
        // }
        $path = uploadS3File($request , $path ,"image","editorImage",$filename = null);
        return getS3File($path);
    }

    /**
     * remove summernote text editor images.
    */
    public function deleteEditoImage(Request $request)
    {
        
        $path = 'editor-images' . '/' . $request->path;
        $image_name_array = explode("/", $request->image);
        $image_name = end($image_name_array);
        $image_url = $path . '/' . $image_name;
        deleteS3File($image_url);
    }

    /**
     *Import user in database
    */
    public function importUsers(Request $request)
    {
        ini_set('max_execution_time', '0');
        $array = Excel::toArray([], $request->file('users'));
        foreach ($array as $arr) {
            foreach ($arr as $key => $nesArr) {
                if($key==0) continue;
                // if ($key <= 5357) continue;
                $phoneNumbers = explode(',', $nesArr[4]);
                 // Create and save the user
                $user = new User();
                $user->user_name_urdu = $nesArr[1];
                $user->phone_number = (count($phoneNumbers) == 1) ? $nesArr[4] : ($phoneNumbers[0]);
                $user->country_id = $nesArr[5];
                $user->province_id = $nesArr[6];
                $user->division_id = $nesArr[8];
                $user->district_id = $nesArr[9];
                $user->tehsil_id = $nesArr[10];
                $user->address_urdu = $nesArr[13];
                $user->save();
                dd('foo');
                $userFee = new UserFee();
                $userFee->subscribeUser($user, false, '', false);

                // Now, you have the user ID of the newly created user
                $user_id = $user->id;

                // Save permanent user data
                if(!empty($nesArr[26]) && isset($nesArr[26])){
                    $permanentAddress = new PermanentAddress();
                    $permanentAddress->user_id = $user_id;
                    // $permanentAddress->province_id_permanent = $nesArr[19];
                    // $permanentAddress->city_id_permanent = $nesArr[20];
                    // $permanentAddress->division_id_permanent = $nesArr[21];
                    // $permanentAddress->district_id_permanent = $nesArr[22];
                    // $permanentAddress->tehsil_id_permanent = $nesArr[23];
                    $permanentAddress->permanent_address_urdu = $nesArr[26];
                    $permanentAddress->save();
                }

                // Save secondary phone numbers in the SecondaryPhone table
                if((count($phoneNumbers) > 1)){
                    foreach ($phoneNumbers as $key=>$phoneNumber) {
                        if($key==0) continue;
                        $secondaryPhone = new SecondaryPhone();
                        $secondaryPhone->user_id = $user_id;
                        $secondaryPhone->phone_number = $phoneNumber;
                        $secondaryPhone->save();
                    }
                }
                
            }
            
        }
        return back()->with('success', 'User Imported Successfully');
    }
}

