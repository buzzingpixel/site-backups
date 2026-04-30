<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use BuzzingPixel\Queue\QueueHandler;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class EnqueueRunBackupsByNameCommand
{
    public static function registerCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(
            expression: 'enqueue-backups',
            callable: self::class,
        );
    }

    public function __construct(
        private QueueHandler $queueHandler,
        private BackupConfigCollection $config,
    ) {
    }

    public function __invoke(): void
    {
        $this->run();
    }

    public function run(): void
    {
        $this->config->walk(callback: [$this, 'runItem']);
    }

    public function runItem(BackupConfig $config): void
    {
        $this->queueHandler->enqueueJob(
            handle: RunBackupByName::JOB_HANDLE,
            name: RunBackupByName::JOB_NAME,
            class: RunBackupByName::class,
            context: ['name' => $config->name],
        );

        $this->queueHandler->enqueueJob(
            handle: ApplyRetentionByName::JOB_HANDLE,
            name: ApplyRetentionByName::JOB_NAME,
            class: ApplyRetentionByName::class,
            context: ['name' => $config->name],
        );

        $this->queueHandler->enqueueJob(
            handle: ApplyMonthlyBackupByName::JOB_HANDLE,
            name: ApplyMonthlyBackupByName::JOB_NAME,
            class: ApplyMonthlyBackupByName::class,
            context: ['name' => $config->name],
        );
    }
}
