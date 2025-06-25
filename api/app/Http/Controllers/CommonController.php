<?php

namespace App\Http\Controllers;

use App\Http\Services\CommonService;
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
     */
    public function code(Request $request)
    {
        $params       = $request->all();
        $params['ip'] = $request->getClientIp();

        $this->CommonService->code($params);

        return $this->success();
    }


    /**
     * @param Request $request
     */
    public function download(Request $request)
    {
        $params       = $request->all();
        $params['ip'] = $request->getClientIp();

        $this->CommonService->download($params);

        return $this->success();
    }
}
