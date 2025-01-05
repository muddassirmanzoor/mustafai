<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\Event;
use App\Models\Admin\EventAttende;
use App\Models\Admin\EventSession;
use App\Models\EventImage;
use App\Models\Subscription\NewSubscription;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * listing the Events.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Events')) {
            access_denied();
        }

        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';
            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';
            $start_date = (isset($_GET['start_date']) && $_GET['start_date']) ? $_GET['start_date'] : '';
            $end_date = (isset($_GET['end_date']) && $_GET['end_date']) ? $_GET['end_date'] : '';
            $db_record = Event::latest();
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
                return $q->where("created_at", '<=', $endDate);
                // return $q->whereRaw("(date(created_at) <='" . $endDate . "')");
            });
            $db_record = $db_record->when($start_date, function ($q, $start_date) {
                $eventStartDate = date('Y-m-d', strtotime($start_date)) . ' 00:00:00';
                return $q->where('start_date_time', '>=', $eventStartDate);
            });
            $db_record = $db_record->when($end_date, function ($q, $end_date) {
                $eventEndDate = date('Y-m-d ', strtotime($end_date)) . ' 23:59:00';
                return $q->where("end_date_time", '<=', $eventEndDate);
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

            $datatable = $datatable->editColumn('start_date_time', function ($row) {
                return date('Y-m-d g:i A', strtotime($row->start_date_time));
            });
            $datatable = $datatable->editColumn('end_date_time', function ($row) {
                return date('Y-m-d g:i A', strtotime($row->end_date_time));
            });
            $datatable = $datatable->addColumn('statusColumn', function ($row) {
                if ($row->status == 1) {
                    $status = 'Active';
                } else {
                    $status = 'Disable';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions white-space">';

                if (have_right('Edit-Events')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/events/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Events')) {
                    $actions .= '<form method="POST" action="' . url("admin/events/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                if (have_right('Set-Attendence-Events')) {
                    $actions .= '<a class="btn btn-primary ml-1" href="' . route('event.attendes', [$row->id]) . '" title="Attendees"><i class="far fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'start_date_time', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.events.listing', $data);
    }

    /**
     * creating the Events.
    */
    public function create()
    {
        if (!have_right('Create-Events')) {
            access_denied();
        }

        $data = [];
        $data['row'] = new User();
        $data['action'] = 'add';
        return View('admin.events.form', $data);
    }

    /**
     * storing the Events.
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $eventSessionData = $request->only([
            'session_english', 'session_urdu', 'session_arabic',
            'description_english', 'description_urdu', 'description_arabic',
            'session_start_date_time', 'session_end_date_time'
        ]);

        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string|max:200',
            'content_english' => 'required',
            'location_english' => 'required|string|max:200',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Events')) {
                access_denied();
            }

            $model = new Event();
            /*if (isset($input['image'])) {
                $imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
            }*/
            unset($input['images']);
            // unset event-sessions fields
            $input = $this->unsetFields($input);
            $model->fill($input);
            $model->save();

            if ($request->has('session_english')) {
                $this->deletePreviousEventSessionsAndSaveBrandNew($model, $eventSessionData, 'add');
            }


            $subscribe_user = NewSubscription::where('status', 1)->get();

            if ($request->hasFile('images')) {
                //$files = uploadS3File($request , "images/events-images" ,"images","events",$filename = null);
                $files = $this->uploadFiles($request->images);
                foreach ($files as $file) {
                    EventImage::create(['event_id' => $model->id, 'image' => $file]);
                }
            }

            $details = [
                'subject' => "New Event Added",
                'user_name' => " Subscribe User",
                'content' => "<p>A new event is added on Mustafai Portal called : " . $model->title_english . ".</p><p>Event details are as follows:</p>
                <bold><p> Event Details:" . $model->content_english . "</p></bold>",
                'links' => "<a href='" . url('/admin') . "'>Click here </a> to log in to Mustafai Portal.",
            ];
            foreach ($subscribe_user as $key => $val) {
                saveEmail($val->email, $details);
                // sendEmail($val, $details);
            }

            return redirect('admin/events')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Events')) {
                access_denied();
            }

            unset($input['action']);
            unset($input['images']);
            unset($input['old_files']);
            $id = $input['id'];
            // unset event-sessions fields
            $input = $this->unsetFields($input);
            $model = Event::find($id);
            //            unset($input['image']);
            /*if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
            }*/
            $model->fill($input);
            $model->update();

            if ($request->has('session_english')) {
                $this->deletePreviousEventSessionsAndSaveBrandNew($model, $eventSessionData, 'edit');
            }

            if ($request->has('old_files')) {
                $eventImagesIds = $model->images->map(fn ($img) => $img->id)->toArray();
                $diffArray = array_diff($eventImagesIds, $request->old_files);
                if ($diffArray) EventImage::whereIn('id', $diffArray)->delete();
            }

            if ($request->hasFile('images')) {
                $files = $this->uploadFiles($request->images);
                foreach ($files as $file) {
                    EventImage::create(['event_id' => $model->id, 'image' => $file]);
                }
            }

            return redirect('admin/events')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Events.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Events')) {
            access_denied();
        }

        $data = [];
        $data['id'] = $id;
        $data['row'] = Event::find($id);
        $data['files'] =  $data['row']->images;
        $data['sessions'] =  $data['row']->sessions;
        $data['action'] = 'edit';
        return View('admin.events.form', $data);
    }

    /**
     * uploading the Events images.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'event' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/events-images'), $imageName)) {
                $path = 'images/events-images/' . $imageName;
            }
        }
        return $path;
    }

    /**
     * listing the Events attendes.
    */
    public function listEventAttendes($id, Request $request)
    {
        // dd($id);
        if (!have_right('Set-Attendence-Events')) {
            access_denied();
        }

        $data = [];
        $data['id'] = $id;
        if ($request->ajax()) {
            $db_record = EventAttende::where('event_id', '=', $id)->get();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('event', function ($row) {
                $event_title = Event::where('id', $row->event_id)->first()->title_english;
                return $event_title;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Delete-Events-Attendes')) {
                    $actions .= '<form method="Post" action="' . url("admin/delete-event-attendes/" . $row->id) . '" accept-charset="UTF-8">';
                    $actions .= '<input type="hidden" name="_method" value="Post">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" type="button" onclick="showConfirmAlert(this)" style="margin-left:02px;" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'title_english']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.events-attendes.listing', $data);
    }

    /**
     * removing the Events.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Events')) {
            access_denied();
        }

        $data = [];
        $id = $id;
        $data['row'] = Event::destroy($id);
        return redirect('admin/events')->with('message', 'Data deleted Successfully');
    }

    /**
     * removing the Events attendes.
    */
    public function deleteEventAttendes($id)
    {
        if (!have_right('Delete-Events-Attendes')) {
            access_denied();
        }

        $data = [];
        $id = $id;
        $data['row'] = EventAttende::destroy($id);
        return back()->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading files for the Events.
    */
    public function uploadFiles($files)
    {
        $imagePaths = [];
        foreach ($files as $image) {
            $imagePaths[] = ImageOptimize::improve($image, 'event-images');
        }
        return $imagePaths;
    }

    /**
     * unset request fields for storing event.
    */
    public function unsetFields(array $arrayOfInputs): array
    {
        unset($arrayOfInputs['session_english']);
        unset($arrayOfInputs['session_urdu']);
        unset($arrayOfInputs['session_arabic']);
        unset($arrayOfInputs['description_english']);
        unset($arrayOfInputs['description_urdu']);
        unset($arrayOfInputs['description_arabic']);
        unset($arrayOfInputs['session_start_date_time']);
        unset($arrayOfInputs['session_end_date_time']);

        return $arrayOfInputs;
    }

    /**
     * removing the previous event sessions and create new ones.
    */
    public function deletePreviousEventSessionsAndSaveBrandNew(Event $model, array $eventSessionData, string $case): void
    {
        // delete previous event sessions and save brand new
        if ($case === 'edit') EventSession::query()->where('event_id', $model->id)->delete();
        /* save event sessions */
        foreach ($eventSessionData['session_english'] as $key => $value) {
            $model->sessions()->create([
                'session_english' => $eventSessionData['session_english'][$key],
                'session_urdu' => $eventSessionData['session_urdu'][$key] ?? '',
                'session_arabic' => $eventSessionData['session_arabic'][$key] ?? '',
                'description_english' => $eventSessionData['description_english'][$key],
                // 'description_urdu' => isset($eventSessionData['description_urdu'][$key]) ? : '',
                'description_urdu' => $eventSessionData['description_urdu'][$key] ?: '',
                'description_arabic' => $eventSessionData['description_arabic'][$key] ?? '',
                'session_start_date_time' => $eventSessionData['session_start_date_time'][$key],
                'session_end_date_time' => $eventSessionData['session_end_date_time'][$key],
            ]);
        }
    }
}
