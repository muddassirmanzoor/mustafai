<?php

namespace App\Providers;

use App\Models\Admin\HeaderSetting;
use Doctrine\DBAL\Schema\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') == 'production'){
            URL::forceScheme('https');
        }
    }
}
