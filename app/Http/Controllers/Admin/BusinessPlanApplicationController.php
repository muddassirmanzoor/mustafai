<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\Notification;
use App\Models\BusinessBooster\ApplicationDateChangeRequest;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use App\Models\User;
use Carbon\CarbonPeriod;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessPlanApplicationController extends Controller
{
    /**
     * listing the business applications.
    */
    public function index(Request $request)
    {
        // dd($request);
        if (!have_right('View-Business-Plan-Applications')) {
            access_denied();
        }

        $type = (isset($_GET['type'])) ? $_GET['type'] : '';
        $plan_id = (isset($_GET['plan_id'])) ? $_GET['plan_id'] : '';

        $data['plan_id'] = $plan_id;
        if (empty($plan_id)) {
            abort(404);
        }
        if ($request->ajax()) {
            $type = ($type == 'unapproved') ? 0 : 1;
            $db_record = BusinessPlanApplication::with(['plan', 'user'])
                ->where('status', $type)
                ->where('plan_id', $request->plan_id)
                ->orderBy('created_at', 'DESC')
                ->get()->each(function ($application) use ($type) {
                    if ($type) {
                        $defaulterPlansArray = [];
                        $plan = $application->plan;

                        $defaulterPlansArray[] = $plan->name_english;

                        $startDate = date('Y-m-d', $plan->start_date);
                        $endDate = date('Y-m-d', $plan->end_date);

                        $period = CarbonPeriod::create($startDate, $endDate);

                        $paidInvoices = BusinessPlanInvoice::where('application_id', $application->id)->pluck('for_date')->toArray();
                        $paidInvoices = array_map(function ($date) {
                            return date('d-m-Y', $date);
                        }, $paidInvoices);

                        $reliefDatesArray = [];
                        $reliefDate = BusinessPlanUserReliefDate::where('application_id', $application->id)->where('user_id', $application->applicant_id)->first();
                        if ($reliefDate) {
                            $reliefStartDate = date('Y-m-d', $reliefDate->start_date);
                            $reliefEndDate = date('Y-m-d', $reliefDate->end_date);
                            $reliefPeriod = CarbonPeriod::create($reliefStartDate, $reliefEndDate);
                            foreach ($reliefPeriod as $reliefDatePeriod) {
                                $reliefDatesArray[] = $reliefDatePeriod->format('d-m-Y');
                            }
                        }

                        $planDates = [];
                        foreach ($period as $date) {
                            $planDates[] = $date->format('d-m-Y');
                        }

                        $todayDate = today()->format('d-m-Y');
                        //                        $todayDate = date('d-m-Y', strtotime('+3 days'));

                        $defaulterDates = [];
                        foreach ($planDates as $planDate) {
                            if (strtotime($planDate) < strtotime($todayDate) && !in_array($planDate, $paidInvoices) && !in_array($planDate, $reliefDatesArray)) {
                                $defaulterDates[] = $planDate;
                            }
                        }
                        if (count($defaulterDates)) {
                            $application->defaulterDates = $defaulterDates;
                        } else {
                            $application->defaulterDates = ['No Records Found'];
                        }
                    }
                });

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {

                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                } else if ($row->status == 0) {
                    $status = '<span class="badge badge-warning">Pending</span>';
                } else {
                    $status = '<span class="badge badge-danger">Disabled</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('statusHidden', function ($row) {

                if ($row->status == 1) {
                    $status = 'Active';
                } else if ($row->status == 0) {
                    $status = 'Pending';
                } else {
                    $status = 'Disabled';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('defaulter_dates', function ($row) {
                gettype($row->defaulterDates) != 'array' ? $row->defaulterDates = ['No Record Found'] : $row->defaulterDates;
                return '<span data-plan-name="' . $row->plan->name_english . '" data-user-id="' . $row->applicant_id . '" data-toggle="modal" data-target="#defaulterDatesModal" title="Defaulter Dates" style="cursor:pointer;" data-dates="' . implode(',', $row->defaulterDates) . '" onclick="showDefaulterDates($(this))"><i class="far fa-eye"></i></span>';
            });

            $datatable = $datatable->addColumn('action', function ($row) use ($type) {
                $actions = '<span class="actions">';

                if ($type == 1) {
                    if (have_right('Add-Relief-Dates-Business-Plan-Applications')) {
                        $actions .= '<a title="relief dates" href="javascript:void(0)" data-application-id="' . $row->id . '" data-toggle="modal" data-target="#showReliefDateModal" onclick="showReliefDates(this)" class="btn btn-primary ml-2" title="Show"><i class="far fa-calendar"></i></a>';
                    }
                    if (have_right('View-Business-Plan-Application-Invoices')) {
                        $actions .= '<a title="Invoices" href="' . url('/admin/business-plans/invoices?application_id=' . $row->id) . '" class="btn btn-secondary ml-2"><i class="far fa-id-badge"></i></a>';
                    }
                    if (have_right('View-Business-Plan-Application-Requests')) {
                        $actions .= '<a title="Requests" href="' . url('/admin/business-plans/requests?application_id=' . $row->id . '&user_id=' . $row->applicant_id) . '" class="btn btn-success ml-2"><i class="fa fa-question-circle" aria-hidden="true"></i>
                        </a>';
                    }
                }

                if (have_right('Show-Form-Business-Plan-Applications')) {
                    $actions .= '<a href="javascript:void(0)" data-application-id="' . $row->id . '" data-toggle="modal" data-target="#showApplicationModal" onclick="showApplicantInformation(this)" class="btn btn-info ml-2" title="Show"><i class="far fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->addColumn('plan', function ($row) {
                return (!empty($row->plan)) ? $row->plan->name_english : '';
            });

            $datatable = $datatable->addColumn('user', function ($row) {
                return (!empty($row->user)) ? $row->user->user_name : '';
            });

            $datatable = $datatable->rawColumns(['plan', 'status', 'defaulter_dates', 'action', 'statusHidden']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.business-plan-applications.listing', $data);
    }

    /**
     * getting the business plans application requests.
    */
    public function requests(Request $request)
    {
        if (!have_right('View-Business-Plan-Application-Requests'))
            access_denied();

        $application_id = (isset($_GET['application_id'])) ? $_GET['application_id'] : '';
        $user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : '';
        $application = ApplicationDateChangeRequest::where('application_id', $application_id)->first();
        $plan_id = (!empty($application)) ? $application->application->plan_id : BusinessPlanApplication::find($_GET['application_id'])->plan_id;

        $data['user_id'] = $user_id;
        $data['plan_id'] = $plan_id;

        if ($request->ajax()) {
            $db_record = ApplicationDateChangeRequest::with('application')->where('application_id', $application_id)->where('user_id', $user_id)->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('plan_name', function ($row) {
                $planName = $row->application->plan->name_english;
                return $planName;
            });

            $datatable = $datatable->editColumn('user_name', function ($row) {
                return $row->user->user_name;
            });

            $datatable = $datatable->editColumn('assigned_to', function ($row) {
                if (!empty($row->assignee_id)) {
                    $user_name = User::where('id', $row->assignee_id)->value('user_name');
                } else {
                    $user_name = 'No One';
                }
                return $user_name;
            });
            $datatable = $datatable->editColumn('existing_date', function ($row) {
                $date = $row->old_date;
                return date('d-m-Y', $date);
            });

            $datatable = $datatable->editColumn('requested_date', function ($row) {
                return date('d-m-Y', $row->date);
            });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-info">Pending</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Approved</span>';
                }
                if ($row->status == 2) {
                    $status = '<span class="badge badge-danger">Declined</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';
                if ($row->status == 0) {
                    $plan_id = $row->application->plan_id;
                    $dataBusiness = BusinessPlanApplication::where('selected_date', $row->date)->where('plan_id', $plan_id)->first();
                    $application_id = empty($dataBusiness) ? null : $dataBusiness->id;
                    if (have_right('Approve-Business-Plan-Application-Requests')) {
                        $actions .= '<a class="btn btn-success ml-2" href="javascript:void(0)" title="approve" onclick="bussinessRequestchange(' . $row->id . ',1,' . $application_id . ')"><i class="fa fa-thumbs-up"></i></a>';
                    }
                    if (have_right('Reject-Business-Plan-Application-Requests')) {

                        $actions .= '<a class="btn btn-danger ml-2" href="javascript:vuser_name(0)" title="reject" onclick="bussinessRequestchange(' . $row->id . ',2,' . $application_id . ')"><i class="fa fa-thumbs-down"></i></a>';
                    }
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['plan_name', 'user_name', 'existing_date', 'requested_date', 'status', 'action', 'assigned_to']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.business-plan-requests.listing', $data);
    }

    /**
     * getting specific the business applications.
    */
    public function show(Request $request)
    {
        $application = BusinessPlanApplication::with(['applicationWitnesses', 'applicationPronotes', 'applicationAccounts.paymentMethod'])->find($request->application_id);
        // dd($application);
        $html = view('admin.partial.show-business-application', get_defined_vars())->render();

        return response()->json(['status' => 200, 'html' => $html]);
    }

    /**
     * displaying invoices of the business applications.
    */
    public function showInvoices(Request $request)
    {
        if (!have_right('View-Business-Plan-Application-Invoices'))
            access_denied();

        $data = [];
        if (isset($_GET['application_id'])) {
            try {
                $data['application_id'] = $_GET['application_id'];
                $data['plan_id'] = BusinessPlanApplication::where('id', $data['application_id'])->first()->plan_id;
            } catch (\Exception $e) {
                // handle error
                // for example, redirect to error page or show error message
                return redirect()->back()->withErrors("Error: " . $e->getMessage());
            }
        } else {
            abort(404, "Something went wrong.");
        }
        if ($request->ajax()) {

            $applicationID = (isset($_GET['application_id'])) ? $_GET['application_id'] : 0;
            $db_record = BusinessPlanInvoice::where('application_id', $applicationID)->orderBy('created_at', 'DESC')->get();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('user', function ($row) {
                return $row->application->user->user_name;
            });

            $datatable = $datatable->editColumn('date', function ($row) {
                return date('d-m-Y', $row->for_date);
            });

            $datatable = $datatable->editColumn('invoice', function ($row) {
                return '<a href="' . getS3File($row->invoice) . '">Invoice</a>';
            });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Un-Approved</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Approved</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Approve-Reject-Business-Plan-Application-Invoices')) {
                    //                    $actions .= '<a href="javascript:void(0)" data-application-id="'.$row->id.'" data-toggle="modal" data-target="#showApplicationModal" onclick="showApplicantInformation(this)" class="btn btn-primary ml-2" title="Show"><i class="far fa-eye"></i></a>';
                    if ($row->status == 1) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }

                    $actions = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="status" onclick="changeStatus($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['user', 'date', 'invoice', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.business-plan-invoices.listing', $data);
    }

    /**
     * staus of the business applications.
    */
    public function status(Request $request, $id)
    {
        // dd($request->status);
        $application = BusinessPlanApplication::find($id);
        $application->update(['status' => $request->status]);
        $userName = User::where('id', $application->applicant_id)->value('user_name');
        $useremail = User::where('id', $application->applicant_id)->value('email');
        $userId = User::where('id', $application->applicant_id)->value('id');
        if ($request->status == 1) {
            $details = [
                'subject' => "Application Approved Successfully",
                'user_name' => $userName,
                'content' => "<p>Your application for a business booster plan has been approved successfully.</p><p>Thanks for your contribution.</p>",
                'links' => "<a href='" . url('/user/busines_plans?type=3') . "'>Click here </a>to proceed further",
            ];
            // notification to user
            $user_id = $userId;
            $notification = Notification::create([
                'title' => 'Your application for a business booster plan has been approved successfully',
                'title_english' => 'Your application for a business booster plan has been approved successfully',
                'title_urdu' => 'بزنس بوسٹر پلان کے لیے آپ کی درخواست کامیابی کے ساتھ منظور ہو گئی ہے',
                'title_arabic' => 'تمت الموافقة على طلبك الخاص بخطة تعزيز الأعمال بنجاح',
                'module_id' => 42,
                'right_id' => 180,
                'ip' => request()->ip()
            ]);
            $notification->users()->attach($user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin
            // sendEmail($useremail, $details);
            saveEmail($useremail, $details);
        } else {
            $details = [
                'subject' => "Application Rejected",
                'user_name' => $userName,
                'content' => "<p>Unfortunately your application for the business booster plan has been rejected for certain reasons..</p><p>Apologize for the inconvenience.</p>",
                'links' => "<a href='" . url('/user/busines_plans?type=1') . "'>Click here </a>to apply again",
            ];
            // notification to user
            $user_id = $userId;
            $notification = Notification::create([
                'title' => 'Unfortunately your application for the business booster plan has been rejected for certain reasons.Apologize for the inconvenience',
                'title_english' => 'Unfortunately your application for the business booster plan has been rejected for certain reasons.Apologize for the inconvenience',
                'title_urdu' => 'بدقسمتی سے بزنس بوسٹر پلان کے لیے آپ کی درخواست کچھ وجوہات کی بنا پر مسترد کر دی گئی ہے۔ زحمت کے لیے معذرت خواہ ہوں۔',
                'title_arabic' => 'للأسف ، تم رفض طلبك الخاص بخطة تعزيز الأعمال لأسباب معينة. اعتذر عن الإزعاج',
                'module_id' => 42,
                'right_id' => '',
                'ip' => request()->ip()
            ]);
            $notification->users()->attach($user_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin
            // sendEmail($useremail, $details);
            saveEmail($useremail, $details);
        }
        return redirect()->back();
    }

    /**
     * relief dates for the business applications.
    */
    public function reliefDates(Request $request)
    {
        $application_id = $request->application_id;
        $reliefs = BusinessPlanUserReliefDate::where('application_id', $request->application_id)->get();

        $html = view('admin.partial.show-relief-dates', get_defined_vars())->render();

        return response()->json(['status' => 200, 'html' => $html]);
    }

    /**
     * show invoice staus of the business applications.
    */
    public function invoiceStatus($id)
    {
        BusinessPlanInvoice::where('id', $id)->update(['status' => $_GET['status']]);
    }

    /**
     * adding relief dates of the business applications.
    */
    public function addReliefDates(Request $request)
    {
        $input = $request->all();
        if (empty($input['id'])) {
            $modal = new BusinessPlanUserReliefDate();
        } else {
            $modal = BusinessPlanUserReliefDate::find($input['id']);
        }

        $application = BusinessPlanApplication::find($input['application_id']);

        $isSave = 0;

        if (!empty($application)) {
            $modal->application_id = $input['application_id'];
            $modal->user_id = $application->applicant_id;
            $modal->start_date = strtotime($input['start_date']);
            $modal->end_date = strtotime($input['end_date']);

            $isSave = $modal->save();
        }

        $response = [];
        if ($isSave) {
            $response['status'] = 1;
            $response['message'] = 'Dates added successfully.';

            //send email
            $details = [
                'subject' => "Payment Relief",
                'user_name' => $application->user->user_name,
                'content' => "<p>Your relief dates for the plan " . $application->plan->name_english . " are as follow.</p><p>Relief dates " . $input['start_date'] . "--" . $input['end_date'] . ".</p><p>Plan Details <br> Name: " . $application->plan->name_english . " <br>Invoice Amount: " . $application->plan->invoice_amount . "  <br>Description: " . $application->plan->description_english . " </p>",
                'links' => "<a href='" . url('/login') . "'>Click here </a> to log in to Mustafai Portal ",
            ];
            // sendEmail($application->user->email, $details);
            saveEmail($application->user->email, $details);
            // notification to user
            $notification = Notification::create([
                'title' => 'Admin added relief dates against your application',
                'title_english' => 'Admin added relief dates against your application',
                'title_urdu' => 'ایڈمن نے آپ کی درخواست کے خلاف ریلیف کی تاریخیں شامل کیں',
                'title_arabic' => 'أضاف المسؤول تواريخ الإغاثة على طلبك',
                'module_id' => 42,
                'right_id' => 184,
                'ip' => request()->ip()
            ]);
            $notification->users()->attach($application->applicant_id, ['notification_type' => 0, 'from_id' => auth()->user()->id]); // type 0 = notification_from_admin

        } else {
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';
        }

        echo json_encode($response);
        die();
    }

    /**
     * status change request of the business application invoice.
    */
    public function changeStatusRequest(Request $request)
    {
        // dd($request);
        if (!have_right('Approve-Reject-Business-Plan-Application-Invoices')) {
            access_denied();
        }
        $id = $request->id;
        $status = $request->change_number;
        $update_status = ApplicationDateChangeRequest::where('id', $id)->update(['status' => $status]);
        if ($status == 1) {
            $newDate = ApplicationDateChangeRequest::where('id', $id)->value('date');
            $oldDate = ApplicationDateChangeRequest::where('id', $id)->first()->application->selected_date;
            $update_status = ApplicationDateChangeRequest::where('id', $id)->first()->application->update(['selected_date' => $newDate]);
            //____________if new application date is already assigned old user application then assign old date of aplicatation to old user date______//
            if (isset($request->application_id)) {
                BusinessPlanApplication::where('id', $request->application_id)->update(['selected_date' => $oldDate]);
            }
        }
        if ($update_status) {
            echo true;
            exit();
        }
    }

    /**
     * send reminder email to user .
    */
    public function sendReminderEmail(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) return response()->json(['status' => 0, 'message' => 'something goes wrong!']);

        $reminderDates = $request->reminder_dates;
        $reminderDates = explode(',', $reminderDates);
        $list = "<ul>";
        foreach ($reminderDates as $date) {
            $list .= "<li>$date</li>";
        }
        $list .= "</ul>";
        $planName = $request->plan_name;

        $details = [
            'subject' =>  "Payment Reminder",
            'user_name' =>  $user->user_name,
            'content'  => "<p>Here’s a subtle reminder to release your pending payments for the business booster plan
                            <b>$planName</b>
                            <br>
                            Your payment for the following date(s) is pending: <br>
                            $list
                            </p>",
            'links'    =>  "<a href='" . url('/login') . "'>Click Here</a> to log in and pay now.",
        ];

        saveEmail($user->email, $details);

        return response()->json(['status' => 1, 'message' => 'Reminder email sent successfully!']);
    }
}
