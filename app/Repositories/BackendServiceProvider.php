<?php

namespace App\Repositories;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{

    public function register()
    {
        // dd("ok binding");
        $this->app->bind(
            'App\Repositories\LibraryRepositoryInterface',
            'App\Repositories\LibraryRepository',

        );
        $this->app->bind(
            'App\Repositories\DonationpaymentHistoryRepositoryInterface',
            'App\Repositories\DonationpaymentHistoryRepository',
        );
        $this->app->bind(
            'App\Repositories\AddressRepositoryInterface',
            'App\Repositories\AddressRepository',
        );
        $this->app->bind(
            'App\Repositories\NewsRepositoryInterface',
            'App\Repositories\NewsRepository',
        );
        $this->app->bind(
            'App\Repositories\TestimonialRepositoryInterface',
            'App\Repositories\TestimonialRepository',
        );
        $this->app->bind(
            'App\Repositories\TeamRepositoryInterface',
            'App\Repositories\TeamRepository',
        );
    }
}
