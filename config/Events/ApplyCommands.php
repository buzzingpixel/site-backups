<?php

declare(strict_types=1);

namespace Config\Events;

use App\Backup\BackupCommand;
use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class ApplyCommands
{
    public function onDispatch(ApplyCliCommandsEvent $commands): void
    {
        BackupCommand::registerCommand(commands: $commands);

        $commands->addSymfonyCommand(
            QueueConsumeNextSymfonyCommand::class,
        );
    }
}
