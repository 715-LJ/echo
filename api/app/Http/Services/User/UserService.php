<?php

namespace App\Http\Services\User;

use App\Constants\Constant;
use App\Constants\ErrorCode;
use App\Exceptions\ApiException;
use App\Http\Resources\User\UserCollection;
use App\Models\User\UserModel;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function list(array $params)
    {
        $page = $params['page'] ?? 1;
        $pageSize = $params['page_size'] ?? Constant::PAGE_SIZE;

        $users = UserModel::query()->orderByDesc('id')->paginate($pageSize, ['*'], 'page', $page);

        return new UserCollection($users);
    }

    public function save(array $params, int $id = 0)
    {
        try {
            if ($id) {
                $user = UserModel::query()->where('id', $id)->first();
                if (!$user) {
                    throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, 'the user not find');
                }
            } else {
                $user = UserModel::query()->where('email', data_must_get($params, "email"))->first();
                if (!$user) {
                    $user = new UserModel();
                }

                if(data_must_get($params, 'check_code') != cache_get(data_get($params, 'ip'))){
                    throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, 'the verification code error');
                }
            }

            $user->name = data_get($params, "name", $user->name ?? '');
            $user->unit = data_get($params, "unit", $user->path ?? '');
            $user->phone = data_get($params, "phone", $user->phone ?? '');
            $user->email = data_get($params, "email", $user->email ?? '');
            $user->address = data_get($params, "address", $user->address ?? '');
            $user->type = data_get($params, "type", $user->type ?? '');

            if (!$user->save()) {
                throw new ApiException(ErrorCode::PARAMS_ERROR, 'The data has already exists');
            }
            return $user;
        } catch (\Exception $e) {
            Log::info('save user fail', [
                'params'   => $params,
                'err_code' => $e->getCode(),
                'err_msg'  => $e->getMessage(),
            ]);
            throw new ApiException(ErrorCode::PARAMS_ERROR, $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        return true;
    }

    public function detail(int $uid)
    {
        return true;
    }
}
