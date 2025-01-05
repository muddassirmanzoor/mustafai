<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface TeamRepositoryInterface
{
    /**
     * calling function to get team members
     *
     * @param int
     */
    public function ourTeam($request);


}
