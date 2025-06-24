<?php

namespace App\Http\Services\User;

use App\Constants\ErrorCode;
use App\Exceptions\ApiException;
use App\Http\Resources\User\UserCollection;
use App\Models\User\UserModel;

class UserService
{
    public function list(array $params)
    {
        $users = UserModel::query()->get();

        return new UserCollection($users);
    }

    public function save(array $params)
    {
        return true;
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
