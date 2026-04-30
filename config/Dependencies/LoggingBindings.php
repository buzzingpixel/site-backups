<?php

declare(strict_types=1);

namespace Config\Dependencies;

use BuzzingPixel\Queue\NoOpLogger;
use Psr\Log\LoggerInterface;
use RxAnte\AppBootstrap\Dependencies\Bindings;

readonly class LoggingBindings
{
    public function __invoke(Bindings $bindings): void
    {
        $bindings->addBinding(
            LoggerInterface::class,
            $bindings->resolveFromContainer(NoOpLogger::class),
        );
    }
}
