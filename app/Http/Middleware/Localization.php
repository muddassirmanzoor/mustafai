<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        } else {
            $continent = $this->getContinentFromExternalApi();

            if ($continent == 'Europe') $this->setLocale('english');

            if ($continent == 'Asia') $this->setLocale('urdu');

            // in case if Api do not return data, set fallback locale urdu
            if (! isset($continent) || $continent == '') $this->setLocale('urdu');
        }
        return $next($request);
    }

    /**
     * @return mixed
     */
    public function getContinentFromExternalApi()
    {
        try{
            $getIpData = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.request()->ip()));

            return $getIpData['geoplugin_continentName'];
        }
        catch(\Exception $e)
        {
            return 'Asia';
        }
    }

    /**
     * @param $locale
     * @return void
     */
    public function setLocale($locale)
    {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
}
