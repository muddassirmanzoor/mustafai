<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TeamRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryType;
use App\Models\Admin\EmployeeSection;
use App;

class TeamController extends Controller
{
    /**
     *get team member api
    */
    public function ourTeam(Request $request, TeamRepositoryInterface $team){
        return $team->ourTeam($request);
    }
    /**
     *get library sections api
    */
    public function librarySections(Request $request)
    {
        $data = [];

        $isTypeMatched = false;

        if (!isset($_GET['all'])) {
            if ($_GET['type'] == 1 && !have_permission('View-Image-Library')) {
                $isTypeMatched = true;
                $data['results'] = collect([]);
            } else {
                if (!$isTypeMatched) $data['results'] = $this->getLibraryData($request);
            }
            if ($_GET['type'] == 2 && !have_permission('View-Video-Library')) {
                $isTypeMatched = true;
                $data['results'] = collect([]);
            } else {
                if (!$isTypeMatched) $data['results'] = $this->getLibraryData($request);
            }
            if ($_GET['type'] == 3 && !have_permission('View-Audio-Library')) {
                $isTypeMatched = true;
                $data['results'] = collect([]);
            } else {
                if (!$isTypeMatched) $data['results'] = $this->getLibraryData($request);
            }
            if ($_GET['type'] == 4 && !have_permission('View-Book-Library')) {
                $isTypeMatched = true;
                $data['results'] = collect([]);
            } else {
                if (!$isTypeMatched) $data['results'] = $this->getLibraryData($request);
            }
            if ($_GET['type'] == 5 && !have_permission('View-Document-Library')) {
                $data['results'] = collect([]);
            } else {
                if (!$isTypeMatched) $data['results'] = $this->getLibraryData($request);
            }
        } else {
            $data['results'] = Library::where('type_id', $_GET['type'])->get();
        }
        $data['type'] = $_GET['type'];
        if(isset($request->empId)){
            // $data['empId'] = 'employee';
            $data['empId'] = $request->empId;
        }
        $queryLibType = [];
        $queryLibType = getQuery(App::getLocale(), ['content']);
        $queryLibType[] = 'id';
        $data['libratype'] =  LibraryType::select($queryLibType)->where('id', $data['type'])->first();
        return response()->json(
            [
                'status'  => 1,
                'message' => "Data Found!!",
                'data' => $data
            ]);
    }
    /**
     *get  team library data api
    */
    public function getLibraryData($request)
    {
        if(!isset($request->empId)){
            $query = getQuery(App::getLocale(), ['title', 'content']);
            $query[] = 'id';
            $query[] = 'parent_id';
            $query[] = 'img_thumb_nail';
            $query[] = 'file';
            $query[] = 'status';
            $query[] = 'type_id';
            return LibraryAlbum::select($query)->where('type_id', $_GET['type'])->whereNull('parent_id')->where('status', 1)->get();
        }else{
            $empId    = $request->empId;
            $employee = EmployeeSection::find($empId);
            $select = getQuery(App::getLocale(), ['title', 'content']);
            $select[] = 'library_albums.id';
            $select[] = 'library_albums.parent_id';
            $select[] = 'library_albums.img_thumb_nail';
            $select[] = 'library_albums.file';
            $select[] = 'library_albums.status';
            $select[] = 'library_albums.type_id';
            return $employee->libraryAlbums($select,$_GET['type'])->get();
            // return LibraryAlbum::select($query)->where('type_id', $_GET['type'])->whereNull('parent_id')->where('status', 1)->limit(8)->get();
        }
    }
}
