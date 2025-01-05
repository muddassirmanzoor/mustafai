<?php

namespace App\Http\Controllers\Home\Pages;

use Illuminate\Http\Request;
use App\Models\Admin\LibraryType;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Admin\EmployeeSection;

class EmployeeSectionController extends Controller
{
    /**
     *Get Employee with library types
    */
    public function employee(Request $request)
    {
        $data['libraryTypes'] = $this->getLibraryTypes();
        $data['employeeSection'] = $this->getEmployeeSections($request->id);
        if (empty($data['employeeSection'])) {
            abort(403);
        }
        $data['id'] = $request->id;
        return view('home.home-pages.employee.employee-details', $data);
    }

    /**
     *Get library types
    */
    function getLibraryTypes()
    {
        $query = getQuery(App::getLocale(), ['title']);
        $query[] = 'id';
        return LibraryType::select($query)->where('status', 1)->get();
    }

    /**
     *Get team members
    */
    public function getEmployeeSections($id)
    {
        $id = encodeDecode($id);
        $query = getQuery(App::getLocale(), ['name', 'designation', 'short_description', 'content']);
        $query[] = 'image';
        $query[] = 'id';
        return EmployeeSection::select($query)->where('status', 1)->where('id', $id)->first();
    }
}
