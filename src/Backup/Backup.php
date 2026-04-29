<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use App\Backup\Config\BackupMysqlDatabaseConfig;
use App\Backup\MySql\BackupMySqlDatabase;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class Backup
{
    public static function registerCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(expression: 'run-backup', callable: self::class);
    }

    public function __construct(
        private BackupConfigCollection $config,
        private BackupMySqlDatabase $backupMysqlDatabase,
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
        $config->walkMySqlDatabaseConfigs(
            function (
                BackupMysqlDatabaseConfig $mySqlConfig,
            ) use ($config): void {
                $this->backupMysqlDatabase->run(
                    sshHost: $config->sshHost,
                    sshUsername: $config->sshUsername,
                    config: $mySqlConfig,
                );
            },
        );
    }
}
