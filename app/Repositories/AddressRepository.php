<?php

namespace App\Repositories;


use App;
use App\Http\Resources\AddressResource;
use App\Models\Admin\CountryCode;

class AddressRepository implements AddressRepositoryInterface
{

    /**
     *Getting Country Codes and passing to Address Resource
     */
    public function getCountryCodes()
    {
        $countryCodes = CountryCode::all();
        $data = AddressResource::collection($countryCodes);
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => $data,
        ], 200);
    }
}
