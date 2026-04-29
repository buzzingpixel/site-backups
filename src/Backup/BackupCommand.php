<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use App\Backup\Config\BackupDatabaseConfig;
use App\Backup\Config\BackupRsyncConfig;
use App\Backup\Sql\BackupDatabase;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class BackupCommand
{
    public static function registerCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(expression: 'run-backup', callable: self::class);
    }

    public function __construct(
        private BackupConfigCollection $config,
        private BackupDatabase $backupMysqlDatabase,
        private BackupRsyncDirectory $backupRsyncDirectory,
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
        $backupDir = new BackupDirHandler(
            projectDir: $config->localBackupFolderName,
        );

        $config->walkMySqlDatabaseConfigs(
            callback: function (BackupDatabaseConfig $mySqlConfig) use (
                $config,
                $backupDir,
            ): void {
                $this->backupMysqlDatabase->run(
                    sshHost: $config->sshHost,
                    sshUsername: $config->sshUsername,
                    config: $mySqlConfig,
                    backupDir: $backupDir,
                );
            },
        );

        $config->walkRsyncConfigs(
            callback: function (BackupRsyncConfig $rsyncConfig) use (
                $config,
                $backupDir,
            ): void {
                $this->backupRsyncDirectory->run(
                    sshHost: $config->sshHost,
                    sshUsername: $config->sshUsername,
                    config: $rsyncConfig,
                    backupDir: $backupDir,
                );
            },
        );
    }
}
