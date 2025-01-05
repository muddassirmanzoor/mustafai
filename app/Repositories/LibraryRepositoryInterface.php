<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface LibraryRepositoryInterface
{
    /**
     * Calling function to get libraries
     *
     * @param int
     */
    public function getLibraries();

    /**
     * calling function to get library details
     *
     * @param int
     */
    public function getLibraryDetails($request);

    /**
     * calling function to get library album details
     *
     * @param int
     */
    public function getLibraryAlbumDetails($request);
}
