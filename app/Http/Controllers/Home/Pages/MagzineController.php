<?php

namespace App\Http\Controllers\Home\Pages;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Magazine;
use App\Models\Admin\MagazineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagzineController extends Controller
{
    /**
     *Get magazine list with filters
    */
    public function magzines(Request $request)
    {
        if ($request->ajax()) {
            $catId = (isset($_GET['category'])) ? $_GET['category'] : '';
            $sortby = (isset($_GET['sortBy'])) ? $_GET['sortBy'] : '';
            $searchStr = (isset($_GET['searchStr'])) ? $_GET['searchStr'] : '';

            $magazineIds = (isset($_GET['magazineIds'])) ? $_GET['magazineIds'] : [];
            $where = '1';
            if ($searchStr != '') {
                $where .= ' AND title_' . lang() . ' LIKE ' . "'%" . $searchStr . "%'";
            }
            if ($catId != '') {
                $where .= ' AND magazine_category_id=' . $catId;
            }
            if (count($magazineIds) && $magazineIds[0] != "") {
                $magazineIds = implode(',', $magazineIds);
                $where .= ' AND id NOT IN (' . $magazineIds . ')';
            }

            $orderBy = '';
            if ($sortby != '') {
                $orderBy .= $sortby;
            } else {
                $orderBy .= 'created_at DESC';
            }

            $query = getQuery(App::getLocale(), ['title', 'description']);
            $query[] = 'id';
            $query[] = 'file';
            $query[] = 'thumbnail_image';
            // DB::enableQueryLog();
            $magazines = Magazine::select($query)->whereRaw($where)->where(['status' => 1])->orderByRaw($orderBy)->limit(9)->get();
            $data = [];
            $data['magazines'] = $magazines;
            $html = (string) View('home.partial.magazines-partial', $data);
            $response = [];
            $response['html'] = $html;
            $response['loadMore'] = count($magazines);
            echo json_encode($response);
            exit();
        }

        $data = [];
        $query = getQuery(App::getLocale(), ['name']);
        $query[] = 'id';
        $data['categories'] = MagazineCategory::select($query)->where('status', 1)->get();
        return view('home.home-pages.magzine.index', $data);
    }
}
