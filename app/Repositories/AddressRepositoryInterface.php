<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface AddressRepositoryInterface
{
    /**
     * Getting country codes
     *
     * @param int
     */
    public function getCountryCodes();


}
