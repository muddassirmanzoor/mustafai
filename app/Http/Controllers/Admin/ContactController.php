<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactForm\ContactRecord;
use DataTables;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * update contact status.
    */
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $data = ContactRecord::where('id', $request->id)->first()->update(['status' => $request->status]);
        if ($data) {
            echo 1;
        }
    }

    /**
     * listing contact status.
    */
    public function index(Request $request)
    {
        // dd($request->ipinfo->all);
        if (!have_right('View-Contacts')) {
            access_denied();
        }

        $data = [];
        if ($request->ajax()) {
            $db_record = ContactRecord::orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('message', function ($row) {
                return readMoreString($row->message);
            });
            $datatable = $datatable->editColumn('messageHiden', function ($row) {
                return $row->message;
            });

            $datatable = $datatable->addColumn('UpdateStatus', function ($row) {
                $statusPending = '';
                $statusInprogress = '';
                $statusApproved = '';
                if ($row->status == 0) {
                    $statusPending = 'selected';
                } elseif ($row->status == 1) {
                    $statusInprogress = 'selected';
                } elseif ($row->status == 2) {
                    $statusApproved = 'selected';
                }
                if (have_right('Contacts-Update-Status')) {
                    return '<select class="" name="status" onChange="changeStatuscontact(' . $row->id . ',$(this).val())"  ><option>--select status--</option><option value="0" ' . $statusPending . ' >Pending</option><option value="1" ' . $statusInprogress . ' >In Progress</option><option value="2" ' . $statusApproved . '>Approve</option></select>';
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });
            $datatable = $datatable->addColumn('status_hidden', function ($row) {
                if ($row->status == 0) {
                    return (string)'Pending';
                } elseif ($row->status == 1) {
                    return 'In Progress';
                } elseif ($row->status == 2) {
                    return 'Approved';
                }
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Contacts')) {
                    $actions .= '<a class="btn btn-primary d-none" href="' . url("admin/contacts/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Contacts-Edit-Note')) {
                    $actions .= '&nbsp;<a data-toggle="modal" data-target="#showNoteModal" class="btn btn-secondary show_note" href="javascript:void(0)" data-contact-id="' . $row->id . '" title="Show"><i class="fa fa-sticky-note"></i></a>';
                }

                if (have_right('Delete-Contacts')) {
                    $actions .= '<form method="POST" action="' . url("admin/contacts/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['action', 'UpdateStatus', 'message', 'messageHiden', 'status_hidden']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.contacts.listing', $data);
    }

    /**
     * show specific contact status.
    */
    public function show(Request $request)
    {
        if ($request->isMethod('get')) {
            $contact = ContactRecord::find($request->id);
            $html = view('admin.partial.show-note', get_defined_vars())->render();

            return response()->json(['html' => $html, 'status' => 200]);
        }
        if ($request->isMethod('post')) {
            $contact = ContactRecord::find($request->contact_id)->update(['note' => $request->note]);
            if ($contact) {
                $response['status'] = 'success';
                $response['message'] = 'Your Note submitted successfully!!';
            }
            echo json_encode($response);
            exit();
        }
    }

    /**
     * remove contact status.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Contacts')) {
            access_denied();
        }

        $data = [];
        $data['row'] = ContactRecord::destroy($id);
        return redirect('admin/contacts')->with('message', 'Data deleted Successfully');
    }
}
