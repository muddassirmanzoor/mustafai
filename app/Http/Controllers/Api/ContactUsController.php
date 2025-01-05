<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\OfficeAddress;
class ContactUsController extends Controller
{
    /**
     *get Contact Info
    */
    public function getMustafaiContactInfo(Request $request){
        $lang=$request->lang;
        $data = [];
        $data['phone']=getSettingDataHelperapi('phone');
        $data['whatsapp']=getSettingDataHelperapi('whatsapp');
        $data['email']=getSettingDataHelperapi('email');
        // $data['address']=getSettingDataHelperapi('address_'.$lang);
        $cols =  array_merge(getQuery($lang, ['address']), ['address_link','featured','status']);
        $office_addresses=OfficeAddress::select( $cols)->where('status',1)->get();
        $data['address']=$office_addresses;

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => $data,
        ], 200);

    }
}
