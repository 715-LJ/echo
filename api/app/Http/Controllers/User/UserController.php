<?php

namespace App\Http\Controllers\User;

use App\Constants\ErrorCode;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 用户列表
     *
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->all();
        $result = $this->userService->list($params);

        return $this->success($result);
    }

    /**
     * 添加用户
     *
     * Store a newly created resource in storage.
     * @throws ApiException
     */
    public function store(Request $request): JsonResponse
    {
        $params = $request->all();
        $params['ip'] = $request->getClientIp();

        $result = $this->userService->save($params);

        return $this->success($result);
    }

    /**
     * 用户详情
     *
     * Display the specified resource.
     */
    public function show(int $uid): JsonResponse
    {
        $result = $this->userService->detail($uid);

        return $this->success($result);
    }

    /**
     * 用户数据更新
     *
     * Update the specified resource in storage.
     * @throws ApiException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            throw new ApiException(ErrorCode::PARAMS_ERROR);
        }
        $params = $request->all();
        $res = $this->userService->save($params, $id);

        return $this->success($res);
    }

    /**
     * 删除用户
     *
     * Remove the specified resource from storage.
     * @throws ApiException
     */
    public function destroy(int $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', new Exists('users')],
        ]);
        if ($validator->fails()) {
            throw new ApiException(ErrorCode::PARAMS_ERROR);
        }

        $res = $this->userService->delete($id);

        return $this->success($res);
    }
}
