<?php

declare(strict_types=1);

namespace Config\Dependencies;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use App\Backup\Config\BackupMysqlDatabaseConfig;
use Config\RuntimeConfigOptions;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use RxAnte\AppBootstrap\RuntimeConfig;

readonly class BackupConfigBindings
{
    public function __invoke(Bindings $bindings): void
    {
        $runtimeConfig = new RuntimeConfig();

        $bindings->addBinding(
            BackupConfigCollection::class,
            static fn () => new BackupConfigCollection(configs: [
                new BackupConfig(
                    name: 'smrc',
                    sshHost: '5.161.111.41',
                    sshUsername: 'root',
                    mysqlDatabaseConfigs: [
                        new BackupMysqlDatabaseConfig(
                            dbUser: 'site',
                            dbPassword: $runtimeConfig->getString(
                                from: RuntimeConfigOptions::SMRC_DB_PASSWORD,
                            ),
                            dbName: 'site',
                            dbContainerName: 'smrc_db',
                            remoteSqlPath: '/root/stmarkreformed.com/stmarkreformed.sql',
                            sqlFileName: 'stmarkreformed.sql',
                            localBackupFolderName: 'stmarkreformed.com',
                        ),
                    ],
                ),
            ]),
        );
    }
}
