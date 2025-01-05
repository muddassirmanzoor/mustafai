<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Models\Admin\BookReceipt;
use App\Http\Controllers\Controller;
use App\Models\Admin\BookReceiptLeaf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BookReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Book-Receipt'))
            access_denied();
        $data = [];
        $data['adminList'] = Admin::where('status', 1)->get();
        if ($request->ajax()) {
            $db_record = BookReceipt::orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Pending</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-warning">Issued</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-success">Received</span>';
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

            $datatable = $datatable->editColumn('leaf_start_number', function ($row) {

                return $row->leaf_start_number . "-" . $row->leaf_end_number;
            });

            $datatable = $datatable->editColumn('total_amount', function ($row) {
                $totalAmount = BookReceiptLeaf::where('book_receipt_id', $row->id)->sum('donation_amount');
                return empty($totalAmount) ? 'N/A' : $totalAmount;
            });

            $datatable = $datatable->editColumn('issued_to', function ($row) {
                return  empty($row->issued_to) ? 'N/A' : optional($row->user)->first_name;
            });

            $datatable = $datatable->addColumn('paid_totalLeafs', function ($row) {
                $paidReceipt = BookReceiptLeaf::where('book_receipt_id', $row->id)->where('donation_amount', "!=", NULL)->count();
                return empty($paidReceipt) ? 'N/A' : $paidReceipt;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Book-Receipt')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/book-receipts/" . encodeDecode($row->id) . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }
                if (have_right('Delete-Book-Receipt')) {
                    $actions .= '<form method="POST" action="' . url("admin/book-receipts/" . encodeDecode($row->id)) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }
                // <i class="fa-solid fa-arrow-up-right"></i>
                if (have_right('Issue-Book-Receipt') && ($row->status < 2)) {
                    $actions .= '<a class="btn btn-info ml-1" href="javascript:void(0)" data-id="' . encodeDecode($row->id) . '" onclick="getIssuedata($(this))" title="Issue Book"><i class="fas fa-arrow-circle-right"></i></a>';
                }
                if (have_right('Received-Book-Receipt') && ($row->status == 1)) {
                    $actions .= '<a class="btn btn-info ml-1" href="' . url('admin/book-receipts-status?id=' . encodeDecode($row->id) . '&changeStatus=2') . '"  title="Receive Book"><i class="fas fa-arrow-circle-left"></i></a>';
                }
                if ($row->status == 2 &&  have_right('Insertion-Leaf-Book-Receipt')) {
                    $actions .= '<a class="btn btn-secondary ml-1" href="' . url('admin/book-receipts-leaf?id=' . encodeDecode($row->id)) . '"  title="Enter Leafs Book"><i class="fa fa-leaf"></i></a>';
                }
                if (have_right('log-Book-Receipt')) {
                    $actions .= '<a class="btn  ml-1 btn-light bg-warning" href="' . url('admin/book-receipts-log?id=' . encodeDecode($row->id)) . '"  title="See logs Book"><i class="fas fa-arrow-circle-up"></i></a>';
                }
                $actions .= '</span>';
                return $actions;
            });
            $datatable = $datatable->rawColumns(['status', 'paid_totalLeafs', 'issued_to', 'statusColumn', 'leaf_start_number', 'total_amount', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.book-receipt.listing', $data);
    }

    /**
     * Show the form for creating a new resource.
    */
    public function create()
    {
        if (!have_right('Create-Book-Receipt'))
            access_denied();
        $data = [];
        $data['row'] = new BookReceipt();
        $data['action'] = 'add';
        return View('admin.book-receipt.form', $data);
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        if (!have_right('Create-Book-Receipt'))
            access_denied();

        if ($request->ajax()) {
            return response()->json($this->jqueryValidate($request));
            exit;
        }
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'book_number' => 'required|string',
            'leaf_start_number' => 'required|string',
            'leaf_end_number' => 'required|string',
            'description' => 'string:min:100',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput()->with('error',  $validator->messages());
        }
        if ($input['action'] == 'add') {
            if (!have_right('Create-Book-Receipt'))
                access_denied();

            $input['created_by'] = auth()->user()->id;
            $model = new BookReceipt();
            unset($input['action']);
            $model->fill($input);
            $model->save();
            return redirect('admin/book-receipts')->with('message', 'Data added Successfully');
        } else {

            if (!have_right('Edit-Book-Receipt'))
                access_denied();

            // dd($input);
            $id = encodeDecode($input['id']);
            $model = BookReceipt::find($id);
            $input['updated_by'] = auth()->user()->id;
            unset($input['action']);
            unset($input['id']);
            $model->fill($input);
            $model->update();
            \LogActivity::addToLog('Log for Book Receipt.', $model);
            return redirect('admin/book-receipts')->with('message', 'Data added Successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Book-Receipt'))
            access_denied();
        $data = [];
        $data['id'] = $id;
        $data['row'] = BookReceipt::find(encodeDecode($id));
        $data['action'] = 'edit';
        return View('admin.book-receipt.form', $data);
    }

    /**
     * Update the specified resource in storage.
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Book-Receipt'))
            access_denied();

        $data = [];
        $data['row'] = BookReceipt::destroy(encodeDecode($id));
        return redirect('admin/book-receipts')->with('message', 'Data deleted Successfully');
    }

    /**
     * jquery validations
    */
    function jqueryValidate($request)
    {

        $leaf_start_number = $request->leaf_start_number;
        $leaf_end_number = $request->leaf_end_number;
        // dd($request->id);
        $id = (!empty($request->id)) ? encodeDecode($request->id) : null;
        $type = $request->type;
        // dd($id);
        if ($type == 'book_data') {
            $bookCount = BookReceipt::where('id', "!=", $id)->where('book_number', $request->book_number)->count();
            if ($bookCount > 0) {
                return 'Book number is already exist';
                exit;
            } else {
                return true;
                exit;
            }
        } else {

            // select * from book_receipts where 20 between leaf_start_number and leaf_end_number;
            if (!empty($leaf_start_number) && !empty($leaf_end_number) && $leaf_start_number < $leaf_end_number) {
                $data = BookReceipt::query()
                    ->where('id', '!=', $id)
                    ->Where(function ($query) use ($leaf_start_number, $leaf_end_number) {
                        // $query->whereRaw("".$leaf_start_number." between leaf_start_number AND leaf_end_number")
                        //     ->orWhereRaw("".$leaf_end_number." between leaf_start_number AND leaf_end_number");
                        $query->whereRaw(" leaf_start_number between $leaf_start_number AND $leaf_end_number")
                            ->orWhereRaw(" leaf_end_number between $leaf_start_number AND $leaf_end_number");
                    })->count();
                if ($data > 0) {
                    return 'Leaf range is already exist';
                } else {
                    return true;
                }
            } else {
                return 'Please fill leaf start and end both correctly';
            }
        }
    }

    /**
     *assining books
    */
    public function assignBook(Request $request)
    {
        // dd($request);
        $input    =  $request->all();
        $bookId   =  encodeDecode($input['id']);
        $bookData =  BookReceipt::find($bookId);
        if ($request->isMethod('post')) {
            $input['issued_by'] = auth()->user()->id;
            $input['status'] = 1;
            unset($input['id']);
            $bookData->fill($input);
            $bookData->update();
            $adminName = ucfirst(Admin::find($bookData->issued_to)->first_name);
            \LogActivity::addToLog('Book Issued to ' . $adminName . '', $bookData);
            return redirect('admin/book-receipts')->with('message', 'Book Receipt Successfully');
        } else {
            $data = [];
            $data['bookData'] = $bookData;
            $data['adminList'] = Admin::where('status', 1)->get();
            $html = (string)View('admin.partial.book-issue-partial', $data);
            echo $html;
            exit;
        }
    }

    /**
     *create book Leaf
    */
    public function createLeaf(Request $request)
    {
        if ($request->ajax()) {
            // edit case 
            $isCompleted = BookReceipt::where('id', encodeDecode($request->bookId))->where('leaf_status', 1)->first();
            if (!empty($isCompleted)) {
                $data['bookLeafs'] = $this->getBookLeafs($request);
                $html = (string)View('admin.partial.leaf-details', $data);
                $response['html'] = $html;
                $response['lastLeafNumber'] = $data['bookLeafs']->last()->leaf_number;
                return response()->json(['status' => 200, 'data' => $response]);
            }
            $createBookData = $this->createBookReceipt($request);
            if ($createBookData != 'completed') {
                $data['bookLeafs'] = $this->getBookLeafs($request);
                $html = (string)View('admin.partial.leaf-details', $data);
                $response['html'] = $html;
                $response['lastLeafNumber'] = $createBookData;
                return response()->json(['status' => 200, 'data' => $response]);
            } else {
                BookReceipt::find(encodeDecode($request->bookId))->update(['leaf_status' => 1]);
                return response()->json(['status' => 200, 'data' => 'completed']);
            }
        } else {
            $id = $request->id;

            $data = [];
            $data['bookId'] = $id;
            $data['bookReceipt'] = BookReceipt::find(encodeDecode($id));
            return view('admin.book-receipt.leaf', $data);
        }
    }

    /**
     *creating books receipt
    */
    public function createBookReceipt($request)
    {
        $bookId = $request->bookId;
        // dd(encodeDecode($bookId));
        $bookData = BookReceipt::where('id', encodeDecode($bookId))->first();
        $startNumber = empty($request->lastLeafNumber) ? $bookData->leaf_start_number : $request->lastLeafNumber + 1;
        $endNumber = $bookData->leaf_end_number;

        if (!($startNumber > $endNumber)) {
            $counter = 1;
            $lastLeaf = '';
            for ($i = $startNumber; $i <= $endNumber; $i++) {
                $leaf = new BookReceiptLeaf;
                $leaf->leaf_number = $i;
                $leaf->book_receipt_id = $bookData->id;
                $leaf->save();
                // dd($leaf->leaf_number);
                if ($counter == 10) {
                    return $leaf->leaf_number;
                } else {
                    $lastLeaf = $leaf->leaf_number;
                }
                $counter++;
            }
            return $lastLeaf;
        } else {
            return 'completed';
        }
    }

    /**
     *get books leaf
    */
    public function getBookLeafs($request)
    {
        $startNumber = empty($request->lastLeafNumber) ? 0 : $request->lastLeafNumber;
        $bookLeafs = BookReceiptLeaf::where('book_receipt_id', encodeDecode($request->bookId))->where('leaf_number', '>', $startNumber)->limit(10)->get();
        return $bookLeafs;
    }

    /**
     *update books leaf
    */
    public function updateLeaf(Request $request)
    {
        // dd($request);
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'donar_name' => 'required',
            'donar_address' => 'required',
            'donation_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        if ($request->receipt_image) {
            //dd($request->receipt_image);
            //deleteS3File($image_url);
            $imagePath = uploadS3File($request , "images/leaf-images" ,"receipt_image","bookRecipiets",$filename = null);
            // deletfolderImage(BookReceiptLeaf::find(encodeDecode($request->id))->receipt_image);
            $input['receipt_image'] = $imagePath;
            $image_url = BookReceiptLeaf::find(encodeDecode($request->id))->receipt_image;
            deleteS3File($image_url);
            //dd($image_url);
        } else {
            $input['receipt_image'] = BookReceiptLeaf::find(encodeDecode($request->id))->receipt_image;
            
        }
        unset($input['id']);
        $id = encodeDecode($request->id);
        $dataLeaf = BookReceiptLeaf::find($id)->update($input);
        return ($dataLeaf) ? response()->json(true) : response()->json(false);
    }

    /**
     * uploading book images
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->receipt_image) {
            $imageName = 'leaf' . time() . '.' . $request->receipt_image->extension();
            if ($request->receipt_image->move(public_path('images/leaf-images'), $imageName)) {
                $path =  'images/leaf-images/' . $imageName;
            }
        }
        return $path;
    }

    /**
     * changing book receipt status
    */
    public function changeStatus(Request $request)
    {
        if (isset($request->changeStatus) && !empty($request->changeStatus)) {
            BookReceipt::find(encodeDecode($request->id))->update(['status' => $request->changeStatus]);
        }
        return redirect('admin/book-receipts')->with('message', 'Data added Successfully');
    }

    /**
     * get book Receipt Logs
    */
    public function seeBookReceiptLogs(Request $request)
    {
        // $db_record=\LogActivity::orderBy('created_at', 'DESC')->get();
        // dd(\LogActivity::logActivityLists('book_receipts',encodeDecode($request->id) ));
        $data = [];
        $data['id'] = $request->id;
        if ($request->ajax()) {
            $db_record = \LogActivity::logActivityLists('book_receipts', encodeDecode($request->id));
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('user_id', function ($row) {
                return Admin::find($row->user_id)->first_name;
            });
            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-m-Y ');
            });

            $datatable = $datatable->rawColumns(['user_id']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.book-receipt.log', $data);
    }
}
