<?php

namespace App\Http\Controllers\User;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryType;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     *If user has permission then it can view the library.
    */
    public function userLibrary()
    {
        if (!have_permission('View-Library')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $data = [];
        $data['libraryTypes'] = $this->getlibraryTypes();
        return view('user.library', $data);
    }

    /**
     *to view the library it gets the library type
    */
    public function getlibraryTypes()
    {
        
        $query = array_merge(getQuery(App::getLocale(), ['title']),['title_english']);
        $query[] = 'id';
        return LibraryType::select($query)->where('status', 1)->get();
    }

    /**
     *to to load library get library type id and return relevant library.
    */
    public function loadLibrary(Request $request)
    {
        // dd($request);
        $data = [];
        $data['libdetails'] = LibraryAlbum::where('type_id', $request->type)->where('id', ">", $request->lastId)->whereNull('parent_id')->limit($request->limit)->get();
        $html = (string) view('user.partials.load-lib', $data); 
        echo $html;
    }
    
    /**
     *Getting moved library Data
    */
    public function moveLib(Request $request)
    {
        $lastId = $request->lastId;
        $typeId = $request->typeId;
        $data = [];
        if ($request->data_move == 'next') {
            $libdata = Library::where('type_id', $typeId)->where('id', '>', $lastId)->take(1)->first();
            if (!empty($libdata)) {
                $data['path'] = asset($libdata->file);
                $data['description'] = $libdata->description;
                $data['last_id'] = $libdata->id;
            } else {
                $data['path'] = 0;
            }
            return $data;
        } else {
            $libdata = Library::where('type_id', $typeId)->where('id', '<', $lastId)->orderBy('id', 'desc')->take(1)->first();
            if (!empty($libdata)) {
                $data['path'] = asset($libdata->file);
                $data['description'] = $libdata->description;
                $data['last_id'] = $libdata->id;
                $data;
            } else {
                $data['path'] = 0;
            }
            return $data;
        }
    }
}
