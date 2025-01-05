<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\LibraryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryType;
use App\Models\Admin\EmployeeSection;
use App;
use App\Models\Admin\LibraryAlbumDetails;


class LibraryController extends Controller
{
    /**
     *Get Library Data WIth Count Of Each Libry Items
    */
    public function getLibrary(LibraryRepositoryInterface $library)
    {
        return $library->getLibraries();
    }
    /**
     *get Library details Data
    */
    public function getLibraryDetails(Request $request, LibraryRepositoryInterface $library)
    {
        return $library->getLibraryDetails($request);
    }
    /**
     *get Library album details Data
    */
    public function getLibraryAlbumDetails(Request $request, LibraryRepositoryInterface $library)
    {
        return $library->getLibraryAlbumDetails($request);
    }
    /**
     *get Library Data for employee or specific library type
    */
    public function library(Request $request)
    {
        $data = [];
        $data['album_id'] = isset($request->id) ? $request->id : null;
        $data['type_idlib'] = isset($request->type_id) ? $request->type_id : null;
        $albumExists = LibraryAlbum::find(($data['album_id']));
        // $libtypExistsData =LibraryType::where('id',($data['type_idlib']))->exists();
        if (!$albumExists) {
            return response()->json(
                [
                    'status'  => 0,
                    'message' => "Data not Found!!",
                    'data' => ''
                ]);
        }

        $empId=isset($request->empId) ? $request->empId : '';
        $data['empId'] = $empId;
        $album_id = ($request->id);
        $data = [];
        $data['breadCrums'] = $this->getBreadCrums($album_id,$empId);
        $query = getQuery($request->lang, ['title', 'content']);
        $query[] = 'id';
        $query[] = 'library_album_id';
        $query[] = 'img_thumb_nail';
        $query[] = 'file';
        $query[] = 'status';
        $data['albumDetails'] = LibraryAlbumDetails::select($query)->where('library_album_id', $album_id)->where('status', 1)->get();
        $data['allchildfiles'] = LibraryAlbumDetails::where('library_album_id', $album_id)->where('status', 1)->get();

        $queryAlbumDetails = getQuery($request->lang, ['title', 'content']);
        $queryAlbumDetails[] = 'id';
        $queryAlbumDetails[] = 'parent_id';
        $queryAlbumDetails[] = 'img_thumb_nail';
        $queryAlbumDetails[] = 'file';
        $queryAlbumDetails[] = 'status';
        $queryAlbumDetails[] = 'type_id';
        $data['childAlbums'] = LibraryAlbum::select($queryAlbumDetails)->where('parent_id', $album_id)->where('status', 1)->get();


        $libAlbum = getQuery($request->lang, ['title', 'content']);
        $libAlbum[] = 'id';
        $libAlbum[] = 'parent_id';
        $libAlbum[] = 'img_thumb_nail';
        $libAlbum[] = 'file';
        $libAlbum[] = 'status';
        $libAlbum[] = 'type_id';
        $data['libAlbum'] = LibraryAlbum::select($libAlbum)->where('id', $album_id)->first();

        $querylibType = getQuery($request->lang, ['title', 'content']);
        $querylibType[] = 'id';
        $querylibType[] = 'icon';
        $data['libraryType'] = LibraryType::select($querylibType)->where('id', $data['libAlbum']->type_id)->first();
        return response()->json(
            [
                'status'  => 1,
                'message' => "Data Found",
                'data' => $data
            ]);
    }
    /**
     *get Library bread crums
    */
    public function getBreadCrums($id,$empId='')
    {
        $albumBreadCrumbs = [];
        if(!empty($empId)){
            $album = LibraryAlbum::find($id);
            $column_name = 'title_' . request()->lang;
            $albumBreadCrumbs[$album->id] = $album->{$column_name};
        }else{
            $b = 'uncomplted';
            while ($b != 'completed') {
                $album = LibraryAlbum::find($id);
                $column_name = 'title_' . request()->lang;
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
}
