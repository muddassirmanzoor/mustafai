<?php

namespace App\Repositories;

use GuzzleHttp\Psr7\Request;

interface DonationpaymentHistoryRepositoryInterface
{
    /**
     * Calling function to get donation history
     *
     * @param int
     */
    public function getDonationPaymentHistory($request);
}
