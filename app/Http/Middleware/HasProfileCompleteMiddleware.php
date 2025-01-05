<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HasProfileCompleteMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $user = User::with(['experience', 'education'])->where('id', auth()->user()->id)->first();

//        if (!$this->hasAddress($user) || !$this->hasSkills($user) || $user->experience->count() == 0 || $user->education->count() == 0)
        if (!$this->hasAddress($user))
        {
            return redirect(route('user.profile'))->with('error', __('app.complete-your-profile-first').'\n'.$this->whatIsMissing($user));
        }

        return $next($request);
    }

    public function hasSkills($user): bool
    {
        if ($user->skills_english != null || $user->skills_urdu != null || $user->skills_arabic != null) {
            return true;
        }

        return false;
    }

    public function hasAddress($user): bool
    {
        if ($user->country_id == null || $user->province_id == null || $user->division_id == null || $user->district_id == null || $user->tehsil_id == null) {
            return false;
        }
        return true;
    }

    public function whatIsMissing($user): string
    {
        $whatMissing = !$this->hasAddress($user) ? __('app.address').',' : '';
//        $whatMissing.= !$this->hasSkills($user) ? __('app.skills').',' : '';
//        $whatMissing.= $user->experience->count() == 0 ? __('app.experience').',' : '';
//        $whatMissing.= $user->education->count() == 0 ? __('app.education').',' : '';
        // remove last comma of string
        $whatMissing = Str::of($whatMissing)->beforeLast(',');

        return $whatMissing.= __('app.is-missing');
    }
}
