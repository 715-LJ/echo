<?php

namespace App\Handlers\Logging;

use Illuminate\Log\Logger;

class CustomLogFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param Logger $logger
     *
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
