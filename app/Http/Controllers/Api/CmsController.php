<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CmsValidation;
use App\Http\Resources\CmsResource;
use App\Http\Resources\PostResource;
use App\Models\Admin\Admin;
use App\Models\Admin\Notification;
use App\Models\Admin\Page;
use App\Models\Posts\Comment\Comment;
use App\Models\Posts\Like\Like;
use App\Models\Posts\Post\Post;
use App\Models\Posts\PostFile\PostFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CmsController extends Controller
{
    /**
     *get cms page detail
    */
    public function getCmsPage(CmsValidation $request)
    {
        $data= [];
        $data['lang']=$request->lang;
        $slug=$request->slug;
        $query = array_merge(getQuery($data['lang'], ['title', 'description']),['id']);
        $data['page'] = Page::select($query)->where('url', $slug)->first();
        return view('web-views.cms-page',$data);

    }

}
