<?php

namespace App\Http\Services;

class CommonService extends BaseService
{
    public function code(array $params)
    {
        $key = 'echo';
        cache_set($key, currentTime());
        return uuid();
    }
}
