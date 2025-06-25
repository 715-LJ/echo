<?php

namespace App\Http\Services;

use App\Models\User\UserModel;
use Carbon\Carbon;
use Gregwar\Captcha\CaptchaBuilder;

class CommonService extends BaseService
{
    public function code(array $params)
    {
        $verification_code = getRandom(4);
        cache_set(data_get($params, 'ip'), $verification_code);

        $builder = new CaptchaBuilder();
        $builder->setPhrase($verification_code);
        $builder->build(150, 50);
        header('Content-type: image/jpeg');
        $builder->output(quality: 100);
    }

    public function download(array $params)
    {
        $execHeader = ['参会名', '参会人单位', '参会人联系方式', '参会类型', '参会人邮箱', '参会人地址', '提交时间'];

        $users = UserModel::query()->orderByDesc('id')->get();
        $list = collect($users)->map(function ($user) {
            return [
                data_get($user, 'name', ''),
                data_get($user, 'unit', ''),
                data_get($user, 'phone', ''),
                data_get($user, 'type', ''),
                data_get($user, 'email', ''),
                data_get($user, 'address', ''),
                Carbon::createFromTimestamp(data_get($user, 'created_at', Carbon::now()))->toDateTimeString(),
            ];
        })->toArray();

        exportExcelToBrowser($execHeader, $list, Carbon::now() . '_' . uuid() . '.xlsx');
    }
}
