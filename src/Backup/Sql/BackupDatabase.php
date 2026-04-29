<?php

declare(strict_types=1);

namespace App\Backup\Sql;

use App\Backup\BackupDirHandler;
use App\Backup\Config\BackupDatabaseConfig;
use App\Ssh\SshConnection;

use function implode;

readonly class BackupDatabase
{
    public function __construct(
        private DumpRemoteDbScriptFactory $dumpRemoteDbScriptFactory,
    ) {
    }

    public function run(
        string $sshHost,
        string $sshUsername,
        BackupDatabaseConfig $config,
        BackupDirHandler $backupDir,
    ): void {
        $sshConnection = new SshConnection(
            host: $sshHost,
            userName: $sshUsername,
        );

        $sshConnection->runCommand(
            command: $this->dumpRemoteDbScriptFactory->create(config: $config),
        );

        $sshConnection->scpPull(
            source: $config->remoteSqlPath(),
            destination: $backupDir->createFilePath(
                fileName: $config->sqlFileName(),
            ),
        );

        $sshConnection->runCommand(implode(' ', [
            'rm',
            $config->remoteSqlPath(),
        ]));

        $sshConnection->close();
    }
}
