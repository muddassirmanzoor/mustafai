<?php

namespace App\Http\Controllers\User;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationReceipt;
use DataTables;
use Illuminate\Http\Request;

class DonationDetailController extends Controller
{
    /**
     *Shows the donation details page if the user has permission.
    */
    public function showDonationDetails()
    {
        if (!have_permission('View-My-Donations')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        return view('user.donation-details');
    }

    /**
     *Retrieves a specific partial view based on the type of donation (e.g., my-donation, donation-cause, pending-payments, advance-payments, reports).
    */
    public function getDonationPartial(Request $request)
    {
        $type = $request->type;
        switch ($type) {
            case "my-donation":
                // $donationReciept = DonationReceipt::wiht('Donation')->where('user_id', auth()->user()->id)->get();
                return view('user.partials.donation-details')->render();
                break;
            case "donation-cause":
                return "donation-cause";
                break;
            case "pending-payments":
                return "pending-payments";
                break;
            case "advance-payments":
                return "advance-payments";
                break;
            case "reports":
                return "reports";
                break;
            default:
                return "no record found";
        }
    }

    /**
     *Retrieves the donation receipts for the authenticated user and prepares the data for DataTables display. It includes formatting the status, donation title, created date, and adding a link to view the receipt.
    */
    public function getDonationTables(Request $request)
    {
        $db_record = DonationReceipt::where('user_id', auth()->user()->id)->latest()->get();
        $datatable = Datatables::of($db_record);
        $datatable = $datatable->addIndexColumn();
        $datatable = $datatable->editColumn('status', function ($row) {
            $status = '<span class="badge badge-danger" style="background-color: red;">' . __("app.pending") . '</span>';
            if ($row->status == 1) {
                $status = '<span class="badge badge-success" style="background-color: #39bb39;">' . __("app.approve") . '</span>';
            }
            return $status;
        });
        $datatable = $datatable->editColumn('statusHidden', function ($row) {
            $status = __("app.pending");
            if ($row->status == 1) {
                $status =  __("app.approve") ;
            }
            return $status;
        });
        $datatable = $datatable->editColumn('donation_id', function ($row) {
            return Donation::where('id', $row->donation_id)->value('title_' . App::getLocale());
        });
        $datatable = $datatable->editColumn('created_at', function ($row) {
            // return    Carbon::createFromFormat('m/d/Y',  $row->created_at);
            return $row->created_at->format('M d Y');
        });
        $datatable = $datatable->addColumn('action', function ($row) {
            $receipt = '<a target ="_blank" href="' . getS3File($row->receipt) . '">' . __("app.view-receipt") . '</a>';
            return $receipt;
        });
        $datatable = $datatable->rawColumns(['donation_id', 'created_at', 'status','statusHidden', 'action']);
        $datatable = $datatable->make(true);
        return $datatable;
    }
}
