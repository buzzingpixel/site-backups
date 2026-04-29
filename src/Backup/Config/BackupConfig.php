<?php

declare(strict_types=1);

namespace App\Backup\Config;

use function array_map;
use function array_values;

readonly class BackupConfig
{
    /** @var BackupMysqlDatabaseConfig[] */
    public array $mysqlDatabaseConfigs;

    /** @var BackupRsyncConfig[] */
    public array $rsyncConfigs;

    /**
     * @param BackupMysqlDatabaseConfig[] $mysqlDatabaseConfigs
     * @param BackupRsyncConfig[]         $rsyncConfigs
     */
    public function __construct(
        public string $name,
        public string $sshHost,
        public string $sshUsername,
        public string $localBackupFolderName,
        array $mysqlDatabaseConfigs = [],
        array $rsyncConfigs = [],
    ) {
        $this->mysqlDatabaseConfigs = array_values(array_map(
            static fn (BackupMysqlDatabaseConfig $c) => $c,
            $mysqlDatabaseConfigs,
        ));

        $this->rsyncConfigs = array_values(array_map(
            static fn (BackupRsyncConfig $c) => $c,
            $rsyncConfigs,
        ));
    }

    public function walkMySqlDatabaseConfigs(callable $callback): void
    {
        array_map($callback, $this->mysqlDatabaseConfigs);
    }

    public function walkRsyncConfigs(callable $callback): void
    {
        array_map($callback, $this->rsyncConfigs);
    }
}
