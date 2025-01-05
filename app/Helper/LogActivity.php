<?php


namespace App\Helper;
use App\Models\Admin\LogActivity as LogActivityModel;


class LogActivity
{
    public static function addToLog($subject,$model)
    {
        // dd($model->getTable());
    	$log = [];
    	$log['subject'] = $subject;
    	$log['form_data'] = json_encode($model);
    	$log['record_id'] = $model->id;
    	$log['table_name'] = $model->getTable();
    	$log['url'] = request()->fullUrl();
    	$log['method'] = request()->method();
    	$log['ip'] = request()->ip();
    	$log['agent'] = request()->header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    	LogActivityModel::create($log);
    }


    public static function logActivityLists($table_name,$record_id)
    {
    	return LogActivityModel::where('table_name',$table_name)->where('record_id',$record_id)->latest()->get();
    }


}