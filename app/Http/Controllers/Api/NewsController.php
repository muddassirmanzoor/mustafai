<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AddressRepositoryInterface;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     *get news headlines api
    */
    public function getNews(Request $request, NewsRepository $news){

        return $news->getnews($request);
    }
}
