<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonorResource;
use App\Models\Admin\Donor;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class DonorController extends Controller
{
    /**
     *get blood donor list
     */
    public function getBloodDonor(Request $request)
    {
        $city = $request->city;
        $city = ($city && $city == 'my') ? Auth::user()->city_id : '';

        $blood_group = $request->bloodGroup;
        // dd($city_id);
        $donorData = Donor::query()
            ->where('status', 1)
            ->when($city, function ($query, $city) {
                if ($city) {
                    return $query->where('city_id', $city);
                }
            })
            ->when($blood_group, function ($query, $blood_group) {
                return $query->where('blood_group', $blood_group);
            })
            ->where(function ($query) {
                $query->whereDate('eligible_after', '<', date('Y-m-d'))
                    ->orWhereNull('eligible_after');
            })
            ->latest()
            ->paginate($request->limit ?? 10);
        // ->limit($request->limit ?? 8)
        // ->get();
        $data = DonorResource::collection($donorData);

        return response()->json([
            'status'  => 1,
            'message' => 'Success',
            'data'    =>  $data,

        ], 200);
    }
    /**
     *save blood donor detail
     */
    public function saveDonor(Request $request)
    {
        $input = $request->all();
        if (auth('sanctum')->user()) {
            $input['user_id']  = auth('sanctum')->user()->id;
        } else {
            unset($input['user_id']);
        }
        $modal = new Donor();

        if ($request->hasFile('image')) {
            // $imagePath = $this->uploadimage($request);
            $imagePath = uploadS3File($request, 'images/group-icons', 'image', 'donor', $filename = null);
            $input['image'] = $imagePath;
        }



        $modal->fill($input);
        $modal->save();
        return response()->json([
            'status'  => 1,
            'message' => 'Data Save Successfully!!',
        ], 200);
    }
}
