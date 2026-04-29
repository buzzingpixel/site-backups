<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupRsyncConfig;
use App\Backup\MySql\BackupDirHandler;
use PhpRsync\Connection;

readonly class BackupRsyncDirectory
{
    public function run(
        string $sshHost,
        string $sshUsername,
        BackupRsyncConfig $config,
        BackupDirHandler $backupDir,
    ): void {
        $backupDir->mkDirInBackupDir(dirName: $config->destinationDirectoryName);

        $connection = new Connection(
            type: 'remote',
            destinationRootDir: '/',
            host: $sshHost,
            user: $sshUsername,
            auth: ['ssh_key' => '/root/.ssh/id_rsa'],
        );

        $rsync = new RsyncOverlay(connection: $connection);

        $rsync->runPull(
            sourceDirectory: $config->rootRelativeServerSrcDir,
            destinationDirectory: $backupDir->createFilePath(
                fileName: $config->destinationDirectoryName,
            ),
        );
    }
}
