<?php

declare(strict_types=1);

namespace App\Backup\MySql;

use App\Backup\Config\BackupMysqlDatabaseConfig;
use App\Ssh\SshConnection;

use function implode;

readonly class BackupMySqlDatabase
{
    public function __construct(
        private DumpRemoteDbScriptFactory $dumpRemoteDbScriptFactory,
    ) {
    }

    public function run(
        string $sshHost,
        string $sshUsername,
        BackupMysqlDatabaseConfig $config,
    ): void {
        $backupDir = new BackupDirHandler(
            projectDir: $config->localBackupFolderName,
        );

        $sshConnection = new SshConnection(
            host: $sshHost,
            userName: $sshUsername,
        );

        $sshConnection->runCommand(
            command: $this->dumpRemoteDbScriptFactory->create(config: $config),
        );

        $sshConnection->scpPull(
            source: $config->remoteSqlPath,
            destination: $backupDir->createFilePath(
                fileName: $config->sqlFileName,
            ),
        );

        $sshConnection->runCommand(implode(' ', [
            'rm',
            $config->remoteSqlPath,
        ]));

        $sshConnection->close();
    }
}
