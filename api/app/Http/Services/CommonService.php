<?php

namespace App\Http\Services;

use Gregwar\Captcha\CaptchaBuilder;

class CommonService extends BaseService
{
    public function code(array $params)
    {
        $builder = new CaptchaBuilder();
        $builder->setPhrase(getRandom(4));
        $builder->build(150, 50);
        header('Content-type: image/jpeg');
        $builder->output();
    }
}
