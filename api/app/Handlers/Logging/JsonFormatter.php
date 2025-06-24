<?php

namespace App\Handlers\Logging;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;
use Monolog\LogRecord;

class JsonFormatter extends BaseJsonFormatter
{
    public function format(LogRecord $record): string
    {
        $newRecord = [
            'sapi'       => defined('SAPI') ? SAPI : '',
            'request_id' => REQUEST_ID ?? '',
            'time'       => $record['datetime']->format('Y-m-d H:i:s'),
//            'env'         => config('app.env'),
//            'app_id'      => config('app.name'),
//            'instance_id' => gethostname(),
            'level'      => $record['level_name'],
            'message'    => $record['message'],
            'context'    => $record['context'] ?? [],
        ];

        return $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');
    }
}
