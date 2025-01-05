<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    /**
     *Get user activities 
    */
    public function index()
    {
        if (!have_permission('View-Activity')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }
        $activities = auth()->user()->activities()->latest()->get();
        return view('user.activities', get_defined_vars());
    }
}
