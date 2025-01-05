<?php

namespace App\Repositories;

use App\Models\Admin\LibraryType;
use App\Models\Post;
use App;
use App\Http\Resources\DonationPaymentHistory;
use App\Http\Resources\LibraryDetails;
use App\Http\Resources\LibraryItemCollection;
use App\Models\Admin\DonationReceipt;
use App\Models\Admin\Library;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Validator;

class DonationpaymentHistoryRepository implements DonationpaymentHistoryRepositoryInterface
{

    /**
     * Getting Donation Payment History
     *
     * @return mixed
     */
    public function getDonationPaymentHistory($request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            // 'offset' => 'required|integer',
            'limit'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages(),

            ], 200);
        }
        $donationPaymentData = DonationReceipt::where('user_id', $request->userId)
        // ->where('id', '>', $request->offset)
        // ->limit($request->limit)
        // ->orderBy('created_at', 'DESC')
        // ->get();
        ->latest()
        ->paginate($request->limit ?? 10);
        if (!$donationPaymentData->isEmpty()) {
            $donationdata = DonationPaymentHistory::collection($donationPaymentData);
            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $donationdata,
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'No record found',

            ], 200);
        }
    }
}
