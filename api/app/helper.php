<?php

if (!function_exists('uuid')) {
    function uuid(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}

if (!function_exists('currentTime')) {
    function currentTime(): string
    {
        return strtotime(gmdate('Y-m-d H:i:s')); // 输出当前的 UTC 时间戳
    }
}
