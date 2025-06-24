<?php


namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Traits\ApiRequest;

class BaseService
{
    use ApiRequest;


    /**
     * @throws ApiException
     */
    public function __construct()
    {
    }
}
