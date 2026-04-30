<?php

declare(strict_types=1);

namespace Config\Events;

use App\Backup\ApplyRetentionCommand;
use App\Backup\BackupCommand;
use App\Backup\EnqueueRunBackupsByNameCommand;
use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use BuzzingPixel\Scheduler\Framework\RunScheduleSymfonyCommand;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class ApplyCommands
{
    public function onDispatch(ApplyCliCommandsEvent $commands): void
    {
        ApplyRetentionCommand::registerCommand(commands: $commands);
        BackupCommand::registerCommand(commands: $commands);
        EnqueueRunBackupsByNameCommand::registerCommand(commands: $commands);

        $commands->addSymfonyCommand(
            QueueConsumeNextSymfonyCommand::class,
        );

        $commands->addSymfonyCommand(
            RunScheduleSymfonyCommand::class,
        );
    }
}
