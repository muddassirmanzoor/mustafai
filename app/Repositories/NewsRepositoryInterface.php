<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface NewsRepositoryInterface
{
    /**
     * calling function to get news
     *
     * @param int
     */
    public function getnews($request);


}
