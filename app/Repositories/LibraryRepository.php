<?php

namespace App\Repositories;

use App\Models\Admin\LibraryType;
use App\Models\Post;
use App;
use App\Http\Resources\LibraryAlbumDetails;
use App\Http\Resources\LibraryAlbumFiles;
use App\Http\Resources\LibraryDetails;
use App\Http\Resources\LibraryItemCollection;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryAlbumDetails as AdminLibraryAlbumDetails;
use Illuminate\Support\Facades\Validator;

class LibraryRepository implements LibraryRepositoryInterface
{

    /**
     * Get All libraries
     *
     * @return mixed
     */
    public function getLibraries()
    {
        $query = array_merge(getQuery(request()->lang, ['title', 'content']), ['id', 'icon']);
        $libData = LibraryType::select($query)->where('status', 1)->get();
        if (!$libData->isEmpty()) {
            $data = LibraryItemCollection::collection($libData);
            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'No record found',

            ], 200);
        }
    }

    /**
     *get Library detais data with offset and lint with imags and show dummy thumbnail if thubnail is not exist
     */
    public function getLibraryDetails($request)
    {
        $validator = Validator::make($request->all(), [
            'typeId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages(),
            ], 200);
        }
        $typeId     = $request->typeId;
        $lastItemId = $request->lastAlbumId;
        $limit      = $request->limit;
        $albumId      = $request->albumId;
        $query = array_merge(getQuery(request()->lang, ['title', 'content']), ['id', 'img_thumb_nail', 'parent_id', 'type_id']);
        $libDetail = LibraryAlbum::select($query)
            ->where('type_id', $typeId)
            // ->where('id','>',$lastItemId ?? 0)
            ->where('parent_id', null)
            // ->limit($limit ?? 8)
            // ->get();
            // ->latest()
            ->paginate($request->limit ?? 8);

        $libratype =  LibraryType::select('id', 'icon', 'status')->where('id', $typeId)->first();
        if ($libratype) {
            $libratype->icon = getS3File($libratype->icon);
        }

        if (!$libDetail->isEmpty()) {
            $data = LibraryDetails::collection($libDetail);
            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $data,
                'library_type' => $libratype
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'No record found',

            ], 200);
        }
    }

    /**
     *Get Library Album Details
     */
    public function getLibraryAlbumDetails($request)
    {
        $validator = Validator::make($request->all(), [
            'typeId' => 'required|integer',
            'albumId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages(),
            ], 200);
        }
        $typeId     = $request->typeId;
        $albumId    = $request->albumId;

        $query = array_merge(getQuery(request()->lang, ['title', 'content']), ['id', 'img_thumb_nail', 'parent_id', 'type_id', 'created_at']);
        $libAlbumDetail = LibraryAlbum::select($query)
            ->where('type_id', $typeId)
            ->where('parent_id', $albumId)
            // ->latest()
            ->paginate($request->albumlimit ?? 8);
        // ->where('id','>',$request->libraryAlbumLastId ?? 0)
        // ->limit($request->albumlimit ?? 8)
        // ->get();
        //sub files
        $query = array_merge(getQuery(request()->lang, ['title', 'content']), ['id', 'library_album_id', 'img_thumb_nail', 'file', 'type_video', 'created_at']);
        $libAlbumDetailFiles = AdminLibraryAlbumDetails::select($query)
            ->where('library_album_id', $albumId)
            // ->latest()
            ->paginate($request->fileslimit ?? 8);
        // ->where('id','>',$request->libraryAlbumFilesLastId ?? 0)
        // ->limit($request->fileslimit ?? 8)
        // ->get();
        $albums = $files = collect();
        if (!$libAlbumDetail->isEmpty()) {
            $albums = LibraryAlbumDetails::collection($libAlbumDetail);
        }
        if (!$libAlbumDetailFiles->isEmpty()) {
            $files = LibraryAlbumFiles::collection($libAlbumDetailFiles);
        }
        $libAlbum = getQuery(App::getLocale(), ['title', 'content']);
        $libAlbum[] = 'id';
        $libAlbum[] = 'parent_id';
        $libAlbum[] = 'img_thumb_nail';
        $libAlbum[] = 'file';
        $libAlbum[] = 'status';
        $libAlbum[] = 'type_id';
        $libAlbum = LibraryAlbum::select($libAlbum)->where('id', $albumId)->first();
        return response()->json([
            'status'     => 1,
            'message'    => 'success',
            'albums'     => $albums,
            'files'      => $files,
            'libAlbum' => $libAlbum,
            'breadCrums' => $this->getBreadCrums($albumId),
        ], 200);
    }
    
    /**
     *Get bread crumms for user
     */
    public function getBreadCrums($id, $empId = '')
    {
        $albumdata = LibraryAlbum::find($id);
        if (empty($albumdata)) {
            return collect();
        }
        $albumBreadCrumbs = [];
        if (!empty($empId)) {
            $album = LibraryAlbum::find($id);
            $column_name = 'title_' . request()->lang;
            $albumBreadCrumbs[$album->id] = $album->{$column_name};
        } else {
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
