<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Donation;
use App\Models\Admin\Product;
use App\Models\Admin\Event;
use App\Models\Admin\Page;
use App;

class SearchController extends Controller
{
    public function __construct()
    {
    }
    
    /**
     *get records for general home search
    */
    public function index(Request $request)
    {
        $data = [];

        if ($request->ajax()) {
            $search = $_POST['query'];
            $type = $_POST['type'];

            $data['type'] = $type;

            $isLast = 0;

            if ($type == 'donations') {
                $query = getQuery(App::getLocale(), ['title', 'description']);
                $query[] = 'id';
                $searches = Donation::select($query)->where('title_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_arabic', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_arabic', 'LIKE', '%' . $search . '%')
                    ->where('status', 1)
                    ->get();
            } else if ($type == 'products') {
                $query = getQuery(App::getLocale(), ['name', 'description']);
                $query[] = 'id';
                $searches = Product::select($query)->where('name_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('name_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('name_arabic', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_arabic', 'LIKE', '%' . $search . '%')
                    ->where('status', 1)
                    ->get();
            } else if ($type == 'events') {
                $query = getQuery(App::getLocale(), ['title', 'content']);
                $query[] = 'id';
                $searches = Event::select($query)->where('title_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_arabic', 'LIKE', '%' . $search . '%')
                    ->orWhere('content_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('content_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('content_arabic', 'LIKE', '%' . $search . '%')
                    ->where('status', 1)
                    ->get();
            } else if ($type == 'pages') {
                $query = getQuery(App::getLocale(), ['title', 'short_description']);
                $query[] = 'url';
                $query[] = 'id';
                $searches = Page::select($query)->where('title_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('title_arabic', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_description_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_description_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_description_arabic', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_english', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_urdu', 'LIKE', '%' . $search . '%')
                    ->orWhere('description_arabic', 'LIKE', '%' . $search . '%')
                    ->where('status', 1)
                    ->get();

                $isLast = 1;
            }

            $data['searches'] = $searches;
            $html = (string)View('home.partial.search-partial', $data);

            $response = [];
            $response['isLast'] = $isLast;
            $response['html'] = $html;
            $response['total'] = count($searches);

            echo json_encode($response);
            exit();
        }
        return view('home.search', $data);
    }
}
