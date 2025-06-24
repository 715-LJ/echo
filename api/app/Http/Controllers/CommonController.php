<?php

namespace App\Http\Controllers;

use App\Http\Services\CommonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->CommonService = $commonService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function code(Request $request): JsonResponse
    {
        $params       = $request->all();
        $params['ip'] = $request->getClientIp();

        $result = $this->CommonService->code($params);

        return $this->success($result);
    }
}
