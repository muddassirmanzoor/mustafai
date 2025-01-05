<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Magazine;
use App\Models\Admin\MagazineCategory;

class MagazineController extends Controller
{
    /**
     *get magazines data with filters or all
    */
    public function magzines(Request $request)
    {
        $catId = (isset($_GET['categoryId'])) ? $_GET['categoryId'] : '';
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
        // dd($request->all());
        $orderBy = '';
        if ($sortby != '') {
            $orderBy .= $sortby;
        }else{
            $orderBy .= 'created_at DESC';
        }

        $query = getQuery($request->lang, ['title', 'description']);
        $query[] = 'id';
        $query[] = 'file';
        $query[] = 'thumbnail_image';
        $query[] = 'magazine_category_id';
        $query[] = 'created_at';
        // DB::enableQueryLog();
        $magazines = Magazine::select($query)->whereRaw($where)->where(['status' => 1])->orderByRaw($orderBy)
        // ->get()
        ->paginate($request->limit ?? 8);
        // dd( $magazines->toArray());
        // dd(DB::getQueryLog());
        return response()->json([
            'status'  => 1,
            'message' => 'Success',
            'data'    => $magazines->items(),

        ], 200);

    }
    /**
     *get magazines category data
    */
    public function magazineCategory(Request $request){
        $data = [];
        $query = getQuery($request->lang, ['name']);
        $query[] = 'id';
        $data= MagazineCategory::select($query)->where('status', 1)->get();
        return response()->json([
            'status'  => 1,
            'message' => 'Success',
            'data'    =>  $data,

        ], 200);
    }
}
