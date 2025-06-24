<?php

namespace App\Http\Services;

class CommonService extends BaseService
{
    public function code(array $params)
    {
        return uuid();
    }
}
