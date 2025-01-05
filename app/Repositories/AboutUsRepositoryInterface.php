<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface AboutUsRepositoryInterface
{
    /**
     * Get Testimonials on about us
     *
     * @param int
     */
    public function getAboutUs($request);


}
