<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationReceipt;
use App\Models\Admin\EmployeeSection;
use App\Models\Admin\Notification;
use App\Models\Admin\Section;
use App\Models\Subscription\NewSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Admin\DonationCategory;

class DonationController extends Controller
{
    /**
     * listing donations.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Donations'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';
            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';
            $db_record = Donation::orderBy('created_at', 'DESC')->get();
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
            $db_record = $db_record->when($from_date, function ($q, $from_date) {
                $startDate = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';
                return $q->where('created_at', '>=', $startDate);
            });
            $db_record = $db_record->when($to_date, function ($q, $to_date) {
                $endDate = date('Y-m-d', strtotime($to_date)) . ' 23:59:00';
                // dd($endDate);
                return $q->where("created_at", '<=', $endDate);
                // return $q->whereRaw("(date(created_at) <='" . $endDate . "')");
            });
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('statusColumn', function ($row) {
                if ($row->status == 1) {
                    $status = 'Active';
                } else {
                    $status = 'Disable';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('featured', function ($row) {
                if ($row->is_featured == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Set-Featured-Donations')) {
                    $featured = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="is_featured" onclick="is_featured($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $featured;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions white-space">';

                if (have_right('View-Reciepts-Donations')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/donations/reciepts/" . $row->id) . '" title="Reciepts"><i class="far fa-eye"></i></a>';
                }

                if (have_right('Edit-Donations')) {
                    $actions .= '<a class="btn btn-primary" style="margin-left:02px;" href="' . url("admin/donations/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Donations')) {
                    $actions .= '<form method="POST" action="' . url("admin/donations/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action', 'featured']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.donation.listing', $data);
    }

    /**
     * creating donations.
    */
    public function create()
    {
        if (!have_right('Create-Donations'))
            access_denied();
        $data = [];
        $data['row'] = new Donation;
        $data['action'] = 'add';
        $data['donation_categories'] = DonationCategory::where('status', 1)->get();
        return View('admin.donation.form', $data);
    }

    /**
     * storing donations.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title_english' => 'required',
            'description_english' => 'required',
            'price' => 'required',
            'donation_type' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            if (!have_right('Create-Donations'))
                access_denied();

            if (isset($input['file'])) {
                $imagePath = uploadS3File($request , "images/donation" ,"image","donations",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['file'] = $imagePath;
            }
            $model = new Donation();
            $model->fill($input);
            $model->save();
            $subscribe_user = NewSubscription::where('status', 1)->get();
            $details = [
                'subject' => "New Donation Added",
                'user_name' => " Subscribe User",
                'content' => "<p>A new donation is added on Mustafai Portal called " . $model->title_english . ".",
                'links' => "<a href='" . url('/user/donate') . "'>Click here </a> to view donation details and donate.",
            ];
            foreach ($subscribe_user as $val) {
                // sendEmail($val, $details);
                saveEmail($val->email, $details);
            }

            return redirect('admin/donations')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Donations'))
                access_denied();

            $id = $input['id'];
            $model = Donation::find($id);

            // @for delete images
            if (isset($input['file'])) {

                deleteS3File($model->image);
                $imagePath = uploadS3File($request , "images/donation" ,"image","donations",$filename = null);

                //$imagePath = $this->uploadImage($request);
                $input['file'] = $imagePath;
                $image_url =  $model->file;
                if (file_exists(public_path($image_url))) {
                    unlink($image_url);
                }
            } else {
                unset($input['image']);
            }
            // @for delete images
            $model->fill($input);
            $model->update();
            return redirect('admin/donations')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit donations.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Donations'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        // $id = Hashids::decode($id)[0];
        $data['row'] = Donation::find($id);
        $data['action'] = 'edit';
        $data['donation_categories'] = DonationCategory::where('status', 1)->get();
        return View('admin.donation.form', $data);
    }

    /**
     * getting receipts for donations.
    */
    function reciepts($id, Request $request)
    {
        if (!have_right('View-Reciepts-Donations'))
            access_denied();

        $db_record = DonationReceipt::where('donation_id', $id)->orderBy('created_at', 'DESC')->get();

        $data = [];
        if ($request->ajax()) {
            $db_record = DonationReceipt::where('donation_id', $id)->orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $featured = '<label class="switch"> <input type="checkbox" name="status" onclick="statusChange($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                return $featured;
            });

            $datatable = $datatable->editColumn('note', function ($row) {
                return readMoreString($row->note);
            });

            $datatable = $datatable->editColumn('receipt', function ($row) {
                $status = '<a target="_blank" href="' . getS3File($row->receipt) . '">receipt</a>';
                return $status;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Blood-Donors')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="javascript:void(0)" title="Payment Details" onclick="get_tranfer_payment_details(' . $row->id . ')"><i class="far fa-eye"></i></a>';
                }
                if (have_right('Doantion-Edit-Note')) {
                    $actions .= '&nbsp;<a data-toggle="modal" data-target="#showDonationNoteModal" class="btn btn-secondary show_donation_note" href="javascript:void(0)" data-reciept-id="' . $row->id . '" title="Show"><i class="fa fa-sticky-note"></i></a>';
                }
                if (have_right('Delete-Donations')) {
                    $actions .= '<form method="POST" action="' . url("admin/donations/reciept-delete/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'receipt', 'note', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        $data['id'] = $id;
        return view('admin.donation.reciepts-listing', $data);
    }

    /**
     * listing specific donations.
    */
    public function show(Request $request)
    {
        if ($request->isMethod('get')) {
            $reciept = DonationReceipt::find($request->id);
            $html = view('admin.partial.show-reciept-note', get_defined_vars())->render();

            return response()->json(['html' => $html, 'status' => 200]);
        }
        if ($request->isMethod('post')) {
            $reciept = DonationReceipt::find($request->reciept_id)->update(['note' => $request->note]);
            if ($reciept) {
                $response['status'] = 'success';
                $response['message'] = 'Your Note submitted successfully!!';
            }
            echo json_encode($response);
            exit();
        }
    }

    /**
     * deleting the receipts of donations.
    */
    function recieptDelete($id)
    {
        if (!have_right('delete-reciept'))
            access_denied();

        $model = DonationReceipt::find($id);
        $image_url =  $model->reciept;
        if (!empty($image_url)) {
            // if (file_exists(public_path($image_url))) {
            //     unlink($image_url);
            // }
            deleteS3File($image_url);
        }
        $data = [];
        $data['row'] = DonationReceipt::destroy($id);
        return redirect()->back()->with('message', 'Data deleted Successfully');
    }

    /**
     * show status of donations.
    */
    function recieptStatus($id)
    {
        $status = $_GET['status'];
        if ($status == 0) {
            $donationStatus = "is Rejected";
            $donationStatusUrdu = "مسترد کر دی گئی ہے ";
            $donationStatusArabic = "مرفوض";
        } else {
            $donationStatus = "is Approved";
            $donationStatusUrdu = "قبول کر لیا گیا ہے ";
            $donationStatusArabic = "تم قبوله ";
        }
        $donationModel = DonationReceipt::where('id', $id)->first();
        DonationReceipt::where('id', $id)->update(['status' => $_GET['status']]);
        if (!empty($donationModel->user_id) && $status != 0) {
            $details = [
                'subject' => "Donation received",
                'user_name' => $donationModel->user->user_name,
                'content'  => "<p>Your donation has been received successfully.</p><p>Thanks for your contribution.</p><p>Donation Details: " . $donationModel->Donation->title_english . "</p>",
                'links' => "<a href='" . url('/') . "'>Click here to donate more</a>",
            ];
            // notification to user
            $user_id = $donationModel->user->id;
            $notification = Notification::create([
                'title' => 'Your donation ' . Str::words($donationModel->Donation->title_english, '4', '...') . ' is ' . $donationStatus,
                'title_english' => 'Your donation ' . Str::words($donationModel->Donation->title_english, '4', '...') . ' is ' . $donationStatus,
                'title_urdu' =>  ' آپ کا عطیہ ' . Str::words($donationModel->Donation->title_urdu, '4', '...') . $donationStatusUrdu,
                'title_arabic' => $donationStatusArabic . Str::words($donationModel->Donation->title_arabic, '4', '...') . 'تبرعك',
                'module_id' => 43,
                'right_id' => 186,
                'ip' => request()->ip()

            ]);
            $notification->users()->attach($user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin
            // sendEmail($donationModel->user->email, $details);
            saveEmail($donationModel->user->email, $details);
        } else {
            if (!empty($donationModel->email) && $status != 0) {

                $details = [
                    'subject' => 'Donation Approved Successfully',
                    'user_name' => (!empty($donationModel->name) ? $donationModel->name : $donationModel->email),
                    'content'  => "<p>Your donation has been approved successfully.</p><p>Thanks for your contribution</p>",
                    'links' => "<a href='" . url('/') . "'>Click here to donate more</a>",
                ];
                // sendEmail($donationModel->email, $details);
                saveEmail($donationModel->email, $details);
            }
        }

        if ($status == 0) {
            $details = [
                'subject' => 'Donation rejected',
                'user_name' => (!empty(($donationModel->user_id)) ? $donationModel->user->user_name : $donationModel->email),
                'content'  => "<p>Unfortunately your donation has been rejected for certain reasons. .</p><p>Apologize for the inconvenience</p>",
                'links' => "<a href='" . url('/') . "'>Click here</a> to donate again",
            ];
            // sendEmail((!empty($donationModel->user_id) ? $donationModel->user->email : $donationModel->email), $details);
            saveEmail((!empty($donationModel->user_id) ? $donationModel->user->email : $donationModel->email), $details);
        }
        echo true;
        exit();
    }

    /**
     * delete the donations.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Donations'))
            access_denied();

        $model = Donation::find($id);
        $image_url =  $model->file;
        // dd(public_path($image_url));
        // if (!empty($image_url)) {
        //     if (file_exists(public_path($image_url))) {
        //         unlink($image_url);
        //     }
        // }
        deleteS3File($image_url);
        $data = [];
        $data['row'] = Donation::destroy($id);
        return redirect('admin/donations')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading donations image.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->file) {
            $imageName = 'donation' . time() . '.' . $request->file->extension();
            if ($request->file->move(public_path('images/donation/'), $imageName)) {
                $path =  'images/donation/' . $imageName;
            }
        }
        return $path;
    }

    /**
     * featured donations status.
    */
    public function setFeaturedDonation($id = null)
    {
        Donation::where('is_featured', 1)->update(['is_featured' => 0]);
        $update_donations = Donation::where('id', $id)->update(['is_featured' => $_GET['status']]);
        if ($update_donations) {
            echo true;
            exit();
        }
    }

    /**
     * payment transfer information.
    */
    public function showTransferPayment(Request $request)
    {
        $donationRecieptId = $request->donationRecieptId;
        $data['donationRecieptData'] = DonationReceipt::with('donationRecieptDetails.paymentMethod', 'donationRecieptDetails.paymentMethodDetail', 'donationPaymentMethod.donationPaymentMethodDetails', 'donationPaymentMethod.paymentMethod')
            ->where('donation_receipts.id', $donationRecieptId)
            ->first();
        // dd($data['donationRecieptData']);
        $html = (string)View('admin.partial.donation-reciept-details-partial', $data);
        echo $html;
    }
}
