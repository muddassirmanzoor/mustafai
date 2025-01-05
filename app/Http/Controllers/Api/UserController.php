<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidation;
use App\Http\Resources\ActivityResource;
use App\Models\User\Activity;
use App\Models\User\ApplyJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     *get user activities api
    */
    public function getUserActivities(UserValidation $request)
    {
        $data = Activity::where('user_id', $request->userId)
            // ->where('id', '>', $request->lastActivityId ?? 0)
            ->latest()
            // ->limit($request->limit ?? 8)
            // ->get();
            ->paginate($request->limit ?? 8);

        $data = ActivityResource::collection($data);
        return response()->json([
            'status'  => 1,
            'message' => "success",
            'data' => $data,
        ]);
    }

    /**
     * APPLY FOR JOB
    */

    public function applyForJob(Request $request)
    {
        /**VALIDATION**/
        $validation_rules = array(
            'userId'       => 'required',
            'postId' => 'required',
            'resume'  => 'required',

        );
        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()]);
        }

        $user = User::where('id', $request->input('userId'))->first();
        if ($user) {
            $path='';
            /**INSERT IN DB**/
            if ($request->hasFile('resume')){
                // $resume = $request->file('resume');
                // $fileName = 'post' . time() . rand(1, 100) . '.' . $resume->extension();
                // if ($resume->move(public_path('job-files'), $fileName)) {
                //     $path =  'job-files/' . $fileName;
                // }
                $path = uploadS3File($request , "job-files" ,"resume","post",$filename = null);
                
            }
            if(isset($path) && empty($path)){
                $path=$request->is_resume == 'on' ? $user->resume : '';
            }

            DB::beginTransaction();
            try {
                ApplyJob::create([
                    'user_id' => $request->input('userId'),
                    'job_post_id' => $request->input('postId'),
                    'name' => $request->input('fullName'),
                    'experience' => $request->input('yourExperience'),
                    'age' => $request->input('yourAge'),
                    'resume' =>  $path,
                ]);
                DB::commit();
                /* */
                return response()->json([
                    'status' => 1,
                    'message' => 'Job applied successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 0,
                    'message' =>   $e->getMessage()
                ]);
            }
        } else {

            $path='';
            /**INSERT IN DB**/
            if ($request->hasFile('resume')){
                // $resume = $request->file('resume');
                // $fileName = 'post' . time() . rand(1, 100) . '.' . $resume->extension();
                // if ($resume->move(public_path('job-files'), $fileName)) {
                //     $path =  'job-files/' . $fileName;
                // }

                $path = uploadS3File($request , "job-files" ,"resume","post",$filename = null);
                //dd($recieptPath);
                
            }
            if(isset($path) && empty($path)){
                $path=$request->is_resume == 'on' ? $user->resume : '';
            }

            DB::beginTransaction();
            try {
                ApplyJob::create([
                    'user_id' => !empty(auth()->user()) ? auth()->user()->id : null,
                    'ip' => !auth()->check() ? $request->ip() : null,
                    'job_post_id' => $request->input('postId'),
                    'name' => $request->input('fullName'),
                    'experience' => $request->input('yourExperience'),
                    'age' => $request->input('yourAge'),
                    'resume' =>  $path,
                ]);
                DB::commit();
                /* */
                return response()->json([
                    'status' => 1,
                    'message' => 'Job applied successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 0,
                    'message' =>   $e->getMessage()
                ]);
            }

            return response()->json(['status' => 0, 'message' => "User not Exists"]);
        }
    }
}
