<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Library;
use App\Models\Admin\Product;
use App\Models\Posts\Post\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SearchController extends Controller
{
    public $result = [];

    public $total = 0;

    /**
     *providing data for search options
    */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            // get user data
            $searchInput = $_POST['query'];
            $searchType = $_POST['type'];

            // given models
            $posts = new Post();
            $users = new User();
            $libraries = new Library();
            $products = new Product();
            $searchModels = [$posts, $users, $libraries, $products];

            // search in database
            foreach ($searchModels as $key => $model)
            {
                if ($model instanceof Post && $searchType == 'posts') $this->searchInPosts($model, $searchInput);
                if ($model instanceof User && $searchType == 'users') $this->searchInUsers($model, $searchInput);
                if ($model instanceof Library && $searchType == 'libraries') $this->searchInLibrary($model, $searchInput);
                if ($model instanceof Product && $searchType == 'products') $this->searchInProducts($model, $searchInput);
            }
            $html = view('user.partials.global-search', ['results' => $this->result])->render();
            return response()->json(['status' => 200, 'html' => $html, 'total' => count($this->result)]);
        }

        return view('user.search-result', ['results' => $this->result]);
    }

    /**
     *Search in posts
    */
    public function searchInPosts($model, $searchInput)
    {
        // helper to get selected language fields
        $query = getQuery(App::getLocale(), ['title', 'description']);
        $query[] = 'id';

        return $model::select($query)->where('title_english', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('title_urdu', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('title_arabic', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('description_english', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('description_urdu', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('description_arabic', 'LIKE', '%'.$searchInput.'%')
            ->where('status', 1)
            ->get()
            ->each(function ($q) {
                array_push($this->result, ['title' => $q->title, 'link' => route('user.specific-post', hashEncode($q->id))]);
            });
    }

    /**
     *Search in users
    */
    public function searchInUsers($model, $searchInput)
    {
        $query= array_merge(getQuery(App::getLocale(), ['user_name']), ['id','user_name']);
        return $model::select($query)->where('user_name', 'LIKE', '%' . $searchInput . '%')
            ->orWhere('user_name_english', 'LIKE', '%' . $searchInput . '%')
            ->orWhere('user_name_urdu', 'LIKE', '%' . $searchInput . '%')
            ->orWhere('user_name_arabic', 'LIKE', '%' . $searchInput . '%')
            ->where('status', 1)
            ->get()
            ->each(function ($q) {
                array_push($this->result, ['title' => availableField($q->user_name, $q->user_name_english, $q->user_name_urdu, $q->user_name_arabic), 'link' => route('user.profile', hashEncode($q->id))]);
            });
    }

    /**
     *Search in library
    */
    public function searchInLibrary($model, $searchInput)
    {
        // helper to get selected language fields
        $query = getQuery(App::getLocale(), ['title']);
        $query[] = 'id';
        $query[] = 'description';

        return $model::select($query)
                    ->where('status', 1)
                    ->where('title_english', 'LIKE', '%'.$searchInput.'%')
                    ->orWhere('title_urdu', 'LIKE', '%'.$searchInput.'%')
                    ->orWhere('title_arabic', 'LIKE', '%'.$searchInput.'%')
                    ->orWhere('description', 'LIKE', '%'.$searchInput.'%')
                    ->get()
                    ->each(function ($q) {
                        array_push($this->result, ['title' => $q->description, 'link' => route('user.library')]);
                    });
    }
    
    /**
     *Search in Products
    */
    public function searchInProducts($model, $searchInput)
    {
        // helper to get selected language fields
        $query = getQuery(App::getLocale(), ['name', 'description']);
        $query[] = 'id';

        return $model::select($query)->where('name_english','LIKE','%'.$searchInput.'%')
                        ->orWhere('name_urdu','LIKE','%'.$searchInput.'%')
                        ->orWhere('name_arabic','LIKE','%'.$searchInput.'%')
                        ->orWhere('description_english','LIKE','%'.$searchInput.'%')
                        ->orWhere('description_urdu','LIKE','%'.$searchInput.'%')
                        ->orWhere('description_arabic','LIKE','%'.$searchInput.'%')
                        ->where('status',1)
                        ->get()
                        ->each(function ($q) {
                            array_push($this->result, ['title' => $q->description, 'link' => '#']);
                        });
    }
}
