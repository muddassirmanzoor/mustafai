<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface TestimonialRepositoryInterface
{
    /**
     * Calling function to get testimonials
     *
     * @param int
     */
    public function getTestimonials($request);


}

