<?php



use App\Models\Admin\Setting;

use App\Models\Admin\Language;

use App\Models\Admin\Page;

use App\Models\Chat\Group;

use App\Models\User;

use Hashids\Hashids;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Services\FCMService;





/**

 * get user rights.

*/



function rights($type)

{

    $result = DB::table('rights')

        ->select('rights.name as right_name', 'modules.name as module_name')

        ->join('modules', 'rights.module_id', '=', 'modules.id')

        ->where(['rights.status' => 1])

        ->where(['modules.type' => $type])

        ->get()

        ->toArray();



    $array = [];



    for ($i = 0; $i < count($result); $i++) {

        $array[$result[$i]->module_name][] = $result[$i];

    }

    return $array;

}



/**

 * get active language.

*/



function activeLangs(){

    return Language::where('status',1)->get();

}



/**

 * check user rights.

*/



function have_right($right_id)
{
    $user = \Auth::user();

    // Check if user has a designation
    if (!empty($user['designation_id'])) {
        // Check designation rights and ignore role if designation exists
        $result = \DB::table('designations')
            ->where('id', $user['designation_id'])
            ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
            ->first();

        if (!empty($result)) {
            return true;
        }

        // If the designation exists but doesn't have the right, deny access
        return false;
    }

    // If no designation exists, fallback to role check
    if ($user['role_id'] == 1) {  // Assuming role_id 1 is super-admin with all permissions
        return true;
    } else {
        $result = \DB::table('roles')
            ->where('id', $user['role_id'])
            ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
            ->first();

        if (!empty($result)) {
            return true;
        }
    }

    // Default deny if neither designation nor role has the right
    return false;
}


/**
 * For user Side Role And Permitions
*/
function have_permission($right_id)
{
    if (Auth::check()) {
        $user = Auth::user();
        $subscriptionFallBackRoleId = (int) $user->subscription_fallback_role_id;

        // Check if user has a designation
        // $hasActiveSubscription = $user->updateUserSubscriptionStatus();

        // Check if user has a designation and an active subscription
        if (!empty($user->designation_id) && (empty($subscriptionFallBackRoleId))) {
            // Check designation rights
            $result = DB::table('designations')
                ->where('id', $user->designation_id)
                ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
                ->first();
            if (!empty($result)) {
                return true;
            }

            return false; // Deny access if no right is found in the designation
        }

        // Fallback to role check if no designation exists
        $authUserRole = (empty($subscriptionFallBackRoleId)) ? (int) $user->login_role_id : $subscriptionFallBackRoleId;

        $result = DB::table('roles')
            ->where('id', $authUserRole)
            ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
            ->first();

        if (!empty($result)) {
            return true;
        }

        // Deny access if neither designation nor role has the right
        return false;
    } else {
        return true; // Default for unauthenticated users, can be adjusted
    }
}



/**
 * For user Side Role And Permitions for api
*/

function have_permission_for_api($user, $right_id)
{
    $subscriptionFallBackRoleId = (int) $user->subscription_fallback_role_id;

    // Check if user has a designation
    // $hasActiveSubscription = $user->updateUserSubscriptionStatus();

    // Check if user has a designation and an active subscription
    if (!empty($user->designation_id) && (empty($subscriptionFallBackRoleId))) {
        // Check designation rights
        $result = DB::table('designations')
            ->where('id', $user->designation_id)
            ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
            ->first();

        if (!empty($result)) {
            return true;
        }
        return false; // Deny access if no right is found in the designation
    }

    // Fallback to role check if no designation exists
    $authUserRole = (empty($subscriptionFallBackRoleId)) ? (int) $user->login_role_id : $subscriptionFallBackRoleId;

    $result = DB::table('roles')
        ->where('id', $authUserRole)
        ->whereRaw("find_in_set('" . $right_id . "', right_ids)")
        ->first();

    return !empty($result);
}


/**

 * check access denied status

*/



function access_denied()

{

    abort(403, 'You have no right to perform this action.');

}



/**

 * checkout access status

*/



function getQuery($locale, $cols)

{

    $columns = [];

    foreach ($cols as $key => $col) {

        $columns[$key] = $col . '_' . $locale . ' as ' . $col;

    }

    return $columns;

}



/**

 *  Helper for Con Gro image

*/



function getConGroImg($type, $id)

{

    if ($type == 'contact') {

        $contact = User::find($id);

        return (!empty($contact->profile_image)) ? getS3File($contact->profile_image) : asset('images/avatar.png');

    } else if ($type == 'group') {

        $group = Group::find($id);

        return (!empty($group->icon)) ? getS3File($group->icon) : asset('images/avatar.png');

    }

}

/**

 *  Helper for setting Data

*/





function getSettingDataHelper($key)

{

    $settingsArray = Session::get('settingsArray');

    if (!empty($settingsArray[$key])) {

        return $settingsArray[$key];

    } else {

        return '';

    }

}



/**

 *  Helper for active footer

*/



function isActiveFooter($slug)

{

    $isActive = Page::where('url',$slug)->pluck('status')->first();

    if ($isActive==1) {

        return true;

    } else {

        return false;

    }

}



/**

 * get setting data api

*/



function getSettingDataHelperapi($key)

{

    $settingsdata = \App\Models\Admin\Setting::all()->toArray();

    $settingsArray = array_column($settingsdata, 'option_value', 'option_name');

    if (!empty($settingsArray[$key])) {

        return $settingsArray[$key];

    } else {

        if($key == 'logo'){

             return 'assets/home/images/site-logo.png';

        }

        return '';

    }

}



/**

 * funtion for get dummy image of library if not set by admin

*/



function libDummyThumbnail($type, $img_thumb_nail)

{

    switch ($type) {

        case 1:

            return asset($img_thumb_nail);

            break;

        case 2:

            return asset('images/thumbnails/video.png');

            break;

        case 3:

            return asset('images/thumbnails/audio.png');

            break;

        case 4:

            return asset('images/thumbnails/books.png');

            break;

        case 5:

            return asset('images/thumbnails/documents.png');

            break;

        default:

            echo "please give right type";

    }

}



/**

 * function for select nav bar of home

*/



function activeNav($menuLink, $dropdown_id = null, $submenus = null)

{

    return '';

    // $menuLink = str_replace("/", "", $menuLink);

    // $url_1 = request()->segment(1);

    // // $url_1 = request()->segment(2);

    // //___ This Is For Simple Menu Request____________//

    // if ($url_1 == $menuLink) {

    //     return "active";

    // }

    // //___ This Is For Drop Down Of library____________//

    // if (!empty($dropdown_id) && ) {

    //     $headerSettings=HeaderSetting::where('parent_id',$dropdown_id)->get();

    //     return "active";

    // }

    // //___ This Is For More Pages Of Header____________//

    // $url = "/" . $url_1;

    // $order = HeaderSetting::where('link', $url)->where('parent_id', null)->pluck('order');

    // if (!$order->isEmpty()) {

    //     if ($menuLink == "more" && $order[0] > 4) {

    //         return "active";

    //     }

    // }

}



    /**

     * helper For Emmail

    */



    function saveEmail($email, $details)

{

    $data = [];

    $data['email'] = $email;

    $data['details'] = json_encode($details);

    $data['created_at'] = date('Y-m-d h:i:s');

    try {

        DB::table('triger_emails')->insert($data);

    } catch (Exception $e) {

        //catch code

    }

}



/**

 *  Helper for send Email

*/



function sendEmail($userEmail, $details = array())

{

    // try {

    \Mail::to($userEmail)->send(new \App\Mail\CommonMail($details));

    // } catch (Exception $e) {

    // dd($e);

    //catch code

    // }

}



/**

 *  Helper for available Field

*/



function availableField($originalCol, $col1, $col2, $col3)

{

    if ($originalCol != null) {

        return $originalCol;

    }



    $col = current(array_filter([$col1, $col2, $col3]));



    if ($col === false) {

        $col = null;

    }



    return $col;

}



/**

 *  Helper for has encode

*/



function hashEncode($id)

{

    $hashids = new Hashids('', 10);

    return $hashids->encode($id);

}



/**

 *  Helper for has decode

*/



function hashDecode($id)

{

    $hashids = new Hashids('', 10);

    $decode = $hashids->decode($id);

    if (isset($decode[0])) {

        return $decode[0];

    }

    return '';

}



/**

 *  Helper for encode decode

*/



function encodeDecode($id){

    if(is_numeric($id)){

        return hashEncode($id);

    }else{

       return  hashDecode($id);

    }

}



/**

 *  Helper for read more string

*/



function readMoreString($string)

{

    $moreString = $string;

    $string = strip_tags($string);

    if (strlen($string) > 300) {

        $trimstring = substr($string, 0, 300) . '... <a href="javascript:void(0)" data-type="more" data-moreString="' . $moreString . '" data-lessString="' . substr($string, 0, 300) . '" onClick="readMoreString($(this))"> Read More </a>';

    } else {

        $trimstring = $string;

    }

    return $trimstring;

}



/**

 *  Helper for time difference

*/



function differentTime($NmazTime)

{

    $start = \Carbon\Carbon::createFromFormat('H:s:i', $NmazTime);

    $timeNow = \Carbon\Carbon::now();



    return $start->diff($timeNow)->format('%H:%I:%S');

}



/**

 *  Helper for pdf view flip book

*/



function flipBookPdf($path)

{

    return '<iframe  src="http://flowpaper.com/flipbook/' . $path . '" width="100%" height="500vh" style="border: none;" allowFullScreen></iframe>';

}



/**

 *  Helper for setting value

*/



function settingValue($key)

{

    $settingValue = Setting::where('option_name', $key)->first();

    if (!empty($settingValue)) {

        return $settingValue->option_value;

    }

    return '';

}



/**

 *  Helper for Bread Crums

*/



function getBreadCrums($id)

{

    $albumBreadCrumbs = [];

    $b = 'uncomplted';

    while ($b != 'completed') {

        $album = LibraryAlbum::find($id);

        if (!empty($album->parent_id)) {

            $parent_album = LibraryAlbum::where(['id' => $album->parent_id])->first();

            if (!isset($albumBreadCrumbs[$album->id])) {

                $albumBreadCrumbs[$album->id] = $album->title_english;

            }

            if (!isset($albumBreadCrumbs[$parent_album->id])) {

                $albumBreadCrumbs[$parent_album->id] = $parent_album->title_english;

            }

            $id = $parent_album->id;

        } else {

            if (!isset($albumBreadCrumbs[$album->id])) {

                $albumBreadCrumbs[$album->id] = $album->title_english;

            }

            $b = 'completed';

        }

    }

    return $albumBreadCrumbs;

}



/**

 *  Helper for Bread Crums

*/



function getALbumChilds($parent_id){



    if($parent_id == ''){

        return '';

    }else{

        $libraryAlbum = App\Models\Admin\LibraryAlbum::where('id',$parent_id)->first();

        $childrens =(empty($libraryAlbum->childrens))?'':$libraryAlbum->childrens->toArray();

        // dd($childrens);

        if(!empty($childrens)){



            $ids=implode(",",array_column($childrens, 'id'));

            return $ids;

        }else{

            return '';

        }

    }





}



/**

 *  Helper for assign album

*/



function isAssignAlbum($empid,$albumId){

    $issAssign = App\Models\Admin\EmployeeSectionLibraryAlbum::where('employee_section_id',$empid)->where('library_album_id',$albumId)->count();

    if($issAssign > 0 ){

        return true;

    }else{

        return false;

    }



}



/**

 *  Helper for delete file

*/



function delefile($path){

    if(!empty($path)){

        if (file_exists(public_path($path))) {



            dd(unlink(public_path($path)));

            // exit;

            return true;

        }

        dd("no ok");

        return true;

    }

    return true;

}



/**

 *  Helper for language

*/



function lang(){

    return App::getLocale();

}



/**

 *  Helper for checking auth token

*/



function checkAuthToken(){

    // $guard = $request->has('api_token');

    // dd($guard);

    if(request()->route()->getPrefix() === 'api'){

        if(auth()->user()){

            return true;



        }

        else {

            return false;

        }

    }

    else {

        if(auth()->user()){

            return true;



        }

        else {

            return false;

        }

    }

}



/**

 *  Helper for upload file on s3 server

*/

function uploadS3File($request, $folder,$file,$title,$filename = null){

    if ($request->hasFile($file)) {

        $file = $request->file($file);

        $extension = $file->getClientOriginalExtension();

        if (!$filename) {

            $filename = $title . '_' . time() . '.' . $extension;

        }

        $path = $file->storeAs($folder, $filename, 's3');

        return $path;

    }

    return null;

}



/**

 *  Helper for get file on s3 server

*/

function getS3File($filePath){

    if ($filePath) {

        $file= 'https://mustafaipks3bucket.s3.ap-southeast-1.amazonaws.com/'.$filePath;

        return $file;

    }

    return '';

}



/**

 *  Helper for delete file on s3 server

*/

function deleteS3File($filePath){

    if (Storage::disk('s3')->exists($filePath)) {

        Storage::disk('s3')->delete($filePath);

        return true;

    }

    return false;

}



/**

 *  Helper for send notification to user app

*/

function sendNotificationrToUser($id,$title,$body,$type,$key1,$val1,$key2,$val2,$key3,$val3)

{

    $firebaseToken = User::whereNotNull('fcm_token')->where('id',$id)->pluck('fcm_token');



    $SERVER_API_KEY = env('FCM_SERVER_KEY');

    $data = [

        "registration_ids" => $firebaseToken,

        "notification" => [

            "title" => $title,

            "body" => $body,

        ],

        "data" => [

            "type" => $type,

            $key1 => $val1,

            $key2 => $val2,

            $key3 => $val3,

            // Add more custom data fields here

        ]

    ];

    $dataString = json_encode($data);



    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];



    $ch = curl_init();



    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);



    $response = curl_exec($ch);



    // dd($response);



}



/**

 *  Helper for send notification to group users

*/

function sendNotificationrToGroupUser($ids,$title,$body,$type,$key1,$val1,$key2,$val2,$key3,$val3,$key4,$val4)

{

    $firebaseToken = User::whereNotNull('fcm_token')->whereIn('id',$ids)->pluck('fcm_token');



    $SERVER_API_KEY = env('FCM_SERVER_KEY');

    $data = [

        "registration_ids" => $firebaseToken,

        "notification" => [

            "title" => $title,

            "body" => $body,

        ],

        "data" => [

            "type" => $type,

            $key1 => $val1,

            $key2 => $val2,

            $key3 => $val3,

            $key4 => $val4,

            // Add more custom data fields here

        ]

    ];

    $dataString = json_encode($data);



    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];



    $ch = curl_init();



    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);



    $response = curl_exec($ch);



    // dd($response);



}



/**

 *  Helper for send namaz notification

*/

function sendNamazNotificationrToUsers($tokens,$title,$body,$type)

{

    $SERVER_API_KEY = env('FCM_SERVER_KEY');

    $data = [

        "registration_ids" => $tokens,

        "notification" => [

            "title" => $title,

            "body" => $body,

        ],

        "data" => [

            "type" => $type,

        ]

    ];

    $dataString = json_encode($data);



    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];



    $ch = curl_init();



    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);



    $response = curl_exec($ch);

    \Log::error('FCM Prayer Response: ' . $response);

}

function sendPaymentNotificationrToUsers($firebaseToken,$title,$body,$type)

{

    $SERVER_API_KEY = env('FCM_SERVER_KEY');

    $data = [

        "registration_ids" => $firebaseToken,

        "notification" => [

            "title" => $title,

            "body" => $body,

        ],

        "data" => [

            "type" => $type,

        ]

    ];

    $dataString = json_encode($data);



    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];



    $ch = curl_init();



    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);



    $response = curl_exec($ch);

    \Log::error('FCM Payment Response: ' . $response);

}

