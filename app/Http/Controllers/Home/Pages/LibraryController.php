<?php

namespace App\Http\Controllers\Home\Pages;

use App\Http\Controllers\Controller;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryAlbumDetails;
use App\Models\Admin\LibraryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pipeline\Pipeline;
use DB;

class LibraryController extends Controller
{
    /**
     *Get all library
    */
    public function allLibrary(Request $request)
    {
        $data = [];
        $data['imageLibrary'] = $this->getLibrary(1);
        $data['videoLibrary'] = $this->getLibrary(2);
        $data['audioLibrary'] = $this->getLibrary(3);
        $data['bookLibrary'] = $this->getLibrary(4);
        $data['documentLibrary'] = $this->getLibrary(5);
        // dd($data['imageLibrary']->libraries);
        return view('home.home-pages.library-pages.all-libraries', $data);
    }

    /**
     *Get library type
    */
    function getLibrary($type)
    {
        $query = getQuery(App::getLocale(), ['title', 'content']);
        $query[] = 'id';
        return LibraryType::select($query)->where('status', 1)->where('id', $type)->first();
    }

    /**
     *Get library detail for specific library type
    */
    public function library(Request $request)
    {
        $data = [];
        $data['album_id'] = isset($request->id) ? $request->id : null;
        $data['type_idlib'] = isset($request->type_id) ? $request->type_id : null;
        $albumExists = LibraryAlbum::find(hashDecode($data['album_id']));
        $libtypExistsData = LibraryType::where('id', hashDecode($data['type_idlib']))->exists();
        if (!$albumExists) {
            abort(404);
        }

        $empId = isset($request->empId) ? $request->empId : '';
        $data['empId'] = $empId;
        // $data['type_id'] = 1;
        if ($request->ajax()) {
            $album_id = hashDecode($request->id);

            $data = [];
            $data['breadCrums'] = $this->getBreadCrums($album_id, $empId);
            $query = getQuery(App::getLocale(), ['title', 'content']);
            $query[] = 'id';
            $query[] = 'library_album_id';
            $query[] = 'img_thumb_nail';
            $query[] = 'file';
            $query[] = 'status';
            $data['albumDetails'] = LibraryAlbumDetails::select($query)->where('library_album_id', $album_id)->where('status', 1)->limit(15)->get();
            $data['allchildfiles'] = LibraryAlbumDetails::where('library_album_id', $album_id)->where('status', 1)->get();

            $queryAlbumDetails = getQuery(App::getLocale(), ['title', 'content']);
            $queryAlbumDetails[] = 'id';
            $queryAlbumDetails[] = 'parent_id';
            $queryAlbumDetails[] = 'img_thumb_nail';
            $queryAlbumDetails[] = 'file';
            $queryAlbumDetails[] = 'status';
            $queryAlbumDetails[] = 'type_id';
            $data['childAlbums'] = LibraryAlbum::select($queryAlbumDetails)->where('parent_id', $album_id)->where('status', 1)->limit(15)->get();


            $libAlbum = getQuery(App::getLocale(), ['title', 'content']);
            $libAlbum[] = 'id';
            $libAlbum[] = 'parent_id';
            $libAlbum[] = 'img_thumb_nail';
            $libAlbum[] = 'file';
            $libAlbum[] = 'status';
            $libAlbum[] = 'type_id';
            $data['libAlbum'] = LibraryAlbum::select($libAlbum)->where('id', $album_id)->first();

            $querylibType = getQuery(App::getLocale(), ['title', 'content']);
            $querylibType[] = 'id';
            $querylibType[] = 'icon';
            $data['libraryType'] = LibraryType::select($querylibType)->where('id', $data['libAlbum']->type_id)->first();
            if (isset($request->empId) && !empty($request->empId)) {
                $data['empId'] = $request->empId;
                $html = (string) view('home.partial.open-employeedir-details', $data);
            } else {
                $html = (string) view('home.partial.open-dir-details', $data);
            }

            echo json_encode($html);
            exit();
        }
        if (isset($empId) && !empty($empId)) {
            return view('home.home-pages.employee.employe-album', $data);
        } else {
            if (!Auth::check()) {
                return view('home.home-pages.library-pages.library-albums', $data);
            } else {
                return view('user.library-albums', $data);
            }
        }
    }

    /**
     *Get library playlist
    */
    public function viewPlaylist(Request $request)
    {
        $data['libAlbumData'] = LibraryAlbumDetails::where('id', $request->clickid)->first();
        $data['libAlbumDetails'] = LibraryAlbumDetails::where('library_album_id', $data['libAlbumData']->library_album_id)->get();
        $html  = (string) View('home.partial.playlist-viewer', $data);
        echo json_encode($html);
    }

    /**
     *Get library playlist
    */
    public function getBreadCrums($id, $empId = '')
    {
        $albumBreadCrumbs = [];
        if (!empty($empId)) {
            $album = LibraryAlbum::find($id);
            $column_name = 'title_' . App::getLocale();
            $albumBreadCrumbs[$album->id] = $album->{$column_name};
        } else {
            $b = 'uncomplted';
            while ($b != 'completed') {
                $album = LibraryAlbum::find($id);
                $column_name = 'title_' . App::getLocale();
                if (!empty($album->parent_id)) {
                    $parent_album = LibraryAlbum::where(['id' => $album->parent_id])->first();
                    if (!isset($albumBreadCrumbs[$album->id])) {
                        $albumBreadCrumbs[$album->id] = $album->{$column_name};
                    }
                    if (!isset($albumBreadCrumbs[$parent_album->id])) {
                        $albumBreadCrumbs[$parent_album->id] = $parent_album->{$column_name};
                    }
                    $id = $parent_album->id;
                } else {
                    if (!isset($albumBreadCrumbs[$album->id])) {
                        $albumBreadCrumbs[$album->id] = $album->{$column_name};
                    }
                    $b = 'completed';
                }
            }
        }
        return  array_reverse($albumBreadCrumbs, true);
    }

    /**
     *Get load more album
    */
    public function albumLoad(Request $request)
    {

        $data = [];
        $data['typDir'] = $request->typeDir;
        if ($data['typDir'] == "folder-div") {
            $queryAlbumDetails = getQuery(App::getLocale(), ['title', 'content']);
            $queryAlbumDetails[] = 'id';
            $queryAlbumDetails[] = 'parent_id';
            $queryAlbumDetails[] = 'img_thumb_nail';
            $queryAlbumDetails[] = 'file';
            $queryAlbumDetails[] = 'status';
            $queryAlbumDetails[] = 'type_id';
            $data['childAlbums'] = LibraryAlbum::select($queryAlbumDetails)->where('parent_id', $request->parent_id)->where('id', '>', $request->lastId)->where('status', 1)->where('type_id', $request->type)->limit(15)->get();
        } else {
            $query = getQuery(App::getLocale(), ['title', 'content']);
            $query[] = 'id';
            $query[] = 'library_album_id';
            $query[] = 'img_thumb_nail';
            $query[] = 'file';
            $query[] = 'status';
            $data['albumDetails'] = LibraryAlbumDetails::select($query)->where('library_album_id', $request->parent_id)->where('status', 1)->where('id', '>', $request->lastId)->limit(15)->get();
        }
        $html = (string) view('home.partial.load-album', $data);
        echo $html;
    }

    /**
     *Get library data
    */
    public function getLibraryData(Request $request)
    {
        $type = encodeDecode($request->type_id);
        if (empty($request->searchData)) {
            echo json_encode('');
            exit();
        }
        $data = [];
        $data['breadCrums'] = array();
        $q = $request->searchData;

        //________________for geting albums________________________________//
        $queryAlbum = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id', 'parent_id', 'img_thumb_nail', 'file', 'status', 'type_id']);
        $data['childAlbums'] = LibraryAlbum::query()->select($queryAlbum)->where('type_id', $type)->where(function ($querry) use ($q) {
            $querry->where('title_english', 'like', "%" . $q . "%")->orWhere('title_urdu', 'LIKE', '%' . $q . '%')
                ->orWhere('title_arabic', 'LIKE', '%' . $q . '%')->orWhere('content_english', 'LIKE', '%' . $q . '%')
                ->orWhere('content_urdu', 'LIKE', '%' . $q . '%')->orWhere('content_arabic', 'LIKE', '%' . $q . '%');
        })->get();

        //__________________for getting Albums Details____________________//
        $query = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id', 'library_album_id', 'img_thumb_nail', 'file', 'status']);
        $data['albumDetails'] = LibraryAlbumDetails::query()->select($query)->whereHas('libraryAlbum', function ($query) use ($type) {
            $query->where('type_id', $type);
        })->where(function ($querry) use ($q) {
            $querry->where('title_english', 'like', "%" . $q . "%")->orWhere('title_urdu', 'LIKE', '%' . $q . '%')
                ->orWhere('title_arabic', 'LIKE', '%' . $q . '%')->orWhere('content_english', 'LIKE', '%' . $q . '%')
                ->orWhere('content_urdu', 'LIKE', '%' . $q . '%')->orWhere('content_arabic', 'LIKE', '%' . $q . '%');
        })->get();
        $querylibType = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id', 'icon']);
        $data['libraryType'] = LibraryType::select($querylibType)->where('id', $type)->first();

        $data['allchildfiles'] = $data['albumDetails'];

        $data['searchBit'] = 1;

        $html = (string) view('home.partial.open-dir-details', $data);

        echo json_encode($html);
        exit();
    }
}
