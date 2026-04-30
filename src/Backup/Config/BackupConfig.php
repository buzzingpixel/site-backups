<?php

declare(strict_types=1);

namespace App\Backup\Config;

use function array_map;
use function array_values;

readonly class BackupConfig
{
    /** @var BackupDatabaseConfig[] */
    public array $databaseConfigs;

    /** @var BackupRsyncConfig[] */
    public array $rsyncConfigs;

    /**
     * @param BackupDatabaseConfig[] $databaseConfigs
     * @param BackupRsyncConfig[]    $rsyncConfigs
     */
    public function __construct(
        public string $name,
        public string $sshHost,
        public string $sshUsername,
        public string $localBackupFolderName,
        array $databaseConfigs = [],
        array $rsyncConfigs = [],
    ) {
        $this->databaseConfigs = array_values(array_map(
            static fn (BackupDatabaseConfig $c) => $c,
            $databaseConfigs,
        ));

        $this->rsyncConfigs = array_values(array_map(
            static fn (BackupRsyncConfig $c) => $c,
            $rsyncConfigs,
        ));
    }

    public function walkMySqlDatabaseConfigs(callable $callback): void
    {
        array_map($callback, $this->databaseConfigs);
    }

    public function walkRsyncConfigs(callable $callback): void
    {
        array_map($callback, $this->rsyncConfigs);
    }
}
