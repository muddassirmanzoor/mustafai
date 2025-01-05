<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\HeaderSetting;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;


class HeaderSettingController extends Controller
{
    /**
     * listing Header-settings.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Header-Setting'))
            access_denied();

        $data = [];
        $data['headerSettings'] = HeaderSetting::where('parent_id', null)->orderBy('order', 'ASC')->get();
        return view('admin.header-setting.form', $data);
    }

    /**
     * storing Header-settings.
    */
    public function store(Request $request)
    {
        if (!have_right('Update-Header-Setting'))
            access_denied();
        $input = $request->all();
        HeaderSetting::truncate();
        $resArray = [];
        foreach ($input['name_english'] as $key => $row) {
            $resArray['order'] = $input['order'][$key];
            $resArray['name_english'] = $row;
            $resArray['name_urdu'] = isset($input['name_urdu'][$key]) ? $input['name_urdu'][$key] : '';
            $resArray['name_arabic'] = isset($input['name_arabic'][$key]) ? $input['name_arabic'][$key] : '';
            $resArray['link'] = $input['link'][$key];

            $model = new HeaderSetting();

            $lastInsertedRecord = $model->create($resArray);

            if (isset($input['child_order'][$key])) {
                foreach ($input['child_order'][$key] as $key1 => $child) {
                    HeaderSetting::create([
                        'order' => $child,
                        'parent_id' => $lastInsertedRecord->id,
                        'name_english' => $input['child_name_english'][$key][$key1],
                        'name_urdu' => isset($input['child_name_urdu'][$key][$key1]) ? $input['child_name_urdu'][$key][$key1] : '',
                        'name_arabic' => isset($input['child_name_arabic'][$key][$key1]) ? $input['child_name_arabic'][$key][$key1] : '',
                        'link' => $input['child_link'][$key][$key1],
                    ]);
                }
            }
        }

        return redirect('admin/header-settings')->with('message', 'Setting updated Successfully');
    }
}
