<?php

declare(strict_types=1);

namespace App\Backup\Config;

use function array_map;
use function array_values;

readonly class BackupConfig
{
    /** @var BackupMysqlDatabaseConfig[] */
    public array $mysqlDatabaseConfigs;

    /** @param BackupMysqlDatabaseConfig[] $mysqlDatabaseConfigs */
    public function __construct(
        public string $name,
        public string $sshHost,
        public string $sshUsername,
        array $mysqlDatabaseConfigs = [],
    ) {
        $this->mysqlDatabaseConfigs = array_values(array_map(
            static fn (BackupMysqlDatabaseConfig $c) => $c,
            $mysqlDatabaseConfigs,
        ));
    }

    public function walkMySqlDatabaseConfigs(callable $callback): void
    {
        array_map($callback, $this->mysqlDatabaseConfigs);
    }
}
