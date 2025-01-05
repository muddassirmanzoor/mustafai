<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OccupationResource;
use App\Models\Admin\Occupation;
use Illuminate\Http\Request;
use App\Models\User\UserOccupation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OccupationController extends Controller
{
    /**
     *get parent professions api
    */
    public function getOccupations(Request $request)
    {
        $lang =$request->lang;
        $query = array_merge(getQuery($lang, ['title']),['id','slug']);
        $data = Occupation::select($query)->where('status',1)->where('parent_id',null)
                ->when($request->id,function($query) use ($request){
                    $query->where('id',$request->id);
                })
                ->get();
        $data =OccupationResource::collection($data);

        return response()->json([
            'status'  =>   1,
            'message' => 'success',
            'data'    =>  $data,
        ]);
    }
    /**
     *get user  professions api
    */
    public function userOccupation(Request $request)
    {
        $cols =  array_merge(getQuery($request->lang, ['title']), ['id','parent_id','status']);
        $child_col =  array_merge(getQuery($request->lang, ['title']), ['id','parent_id','status']);
        $data=[];
        $data['occupations'] = Occupation::select($cols)->with(['subProfession'=>function ($q) use($child_col){
            $q->select($child_col)->get();
        },])->where('parent_id', '=', null)->where('status',1)->get();
        $array = UserOccupation::where('user_id',$request->user_id)->pluck('occupation_id')->toArray();
         $data['userOccupationIds'] = array_map(function ($value) {
            return intval($value);
        }, $array);
        $data['user_id']=$request->user_id;
        return response()->json([
            'status'  =>   1,
            'message' => 'success',
            'data'    =>  $data,
        ]);
    }
    /**
     *store user  professions api
    */
    public function addOccupation(Request $request)
    {
        $occu = [];
        if (!empty($request->ids)) {
            for ($i = 0; $i < count($request->ids); $i++) {
                $occu[] = array(
                    'occupation_id' => $request->ids[$i],
                    'user_id' =>auth()->user()->id,
                    'status'=>1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                );
            }
        }
        if (!empty($request->ids) ||$request->has('other_profession') && !empty($request->other_profession)) {
            UserOccupation::where('user_id', auth()->user()->id)->delete();
            $user_occupation = UserOccupation::insert($occu);
            if (!is_null($request->other_profession)) {
            $occupation_exists=Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->exists();
            }
            else{
                $occupation_exists=false;
            }
            if(!$occupation_exists){
                if (!is_null($request->other_profession)) {
                    $occupation = new Occupation([
                        'title_english' => $request->other_profession,
                        'title_urdu' => $request->other_profession,
                        'slug' => strtolower(preg_replace('/\s+/', '-', $request->other_profession)),
                        'status'=>1
                    ]);

                    if($occupation->save()){
                        $user_other_occupation = new UserOccupation([
                            'occupation_id' => $occupation->id,
                            'user_id' => auth()->user()->id,
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $user_other_occupation->save();
                    }
                }
            }
            else{
                $occupation_exists_id=Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->pluck('id')->first();
                $user_other_occupation = new UserOccupation([
                    'occupation_id' => $occupation_exists_id,
                    'user_id' => auth()->user()->id,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user_other_occupation->save();
            }
        } else {
            UserOccupation::where('user_id', auth()->user()->id)->delete();
        }
        return response()->json(['status' => 200, 'message' =>'data save Successfully!']);
    }

}
