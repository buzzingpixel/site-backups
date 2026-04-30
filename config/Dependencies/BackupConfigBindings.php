<?php

declare(strict_types=1);

namespace Config\Dependencies;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use App\Backup\Config\BackupMysqlDatabaseConfig;
use App\Backup\Config\BackupPostgresDatabaseConfig;
use App\Backup\Config\BackupRsyncConfig;
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
                    localBackupFolderName: 'stmarkreformed.com',
                    databaseConfigs: [
                        new BackupMysqlDatabaseConfig(
                            dbUser: 'site',
                            dbPassword: $runtimeConfig->getString(
                                from: RuntimeConfigOptions::SMRC_DB_PASSWORD,
                            ),
                            dbName: 'site',
                            dbContainerName: 'smrc_db',
                            remoteSqlPath: '/root/stmarkreformed.com/stmarkreformed.sql',
                            sqlFileName: 'stmarkreformed.sql',
                        ),
                    ],
                    rsyncConfigs: [
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/smrc_files-above-webroot-volume/_data/',
                            destinationDirectoryName: 'filesAboveWebroot',
                        ),
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/smrc_files-volume/_data/',
                            destinationDirectoryName: 'files',
                        ),
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/smrc_uploads-volume/_data/',
                            destinationDirectoryName: 'uploads',
                        ),
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/smrc_web-public-images-galleries-volume/_data/',
                            destinationDirectoryName: 'images/galleries',
                        ),
                    ],
                ),
                new BackupConfig(
                    name: 'buzzingpixel',
                    sshHost: '5.161.94.209',
                    sshUsername: 'root',
                    localBackupFolderName: 'buzzingpixel.com',
                    databaseConfigs: [
                        new BackupPostgresDatabaseConfig(
                            dbUser: 'buzzingpixel',
                            dbPassword: $runtimeConfig->getString(
                                from: RuntimeConfigOptions::BUZZINGPIXEL_DB_PASSWORD,
                            ),
                            dbName: 'buzzingpixel',
                            dbContainerName: 'buzzingpixel_db.1',
                            remoteSqlPath: '/root/buzzingpixel.com/buzzingpixel.psql',
                            sqlFileName: 'buzzingpixel.psql',
                        ),
                    ],
                    rsyncConfigs: [
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/buzzingpixel_files-volume/_data/',
                            destinationDirectoryName: 'files',
                        ),
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/buzzingpixel_storage-volume/_data/',
                            destinationDirectoryName: 'storage-volume',
                        ),
                    ],
                ),
                new BackupConfig(
                    name: 'moviebyte',
                    sshHost: '5.161.94.209',
                    sshUsername: 'root',
                    localBackupFolderName: 'moviebyte.com',
                    databaseConfigs: [
                        new BackupMysqlDatabaseConfig(
                            dbUser: 'site',
                            dbPassword: $runtimeConfig->getString(
                                from: RuntimeConfigOptions::MOVIE_BYTE_DB_PASSWORD,
                            ),
                            dbName: 'site',
                            dbContainerName: 'moviebyte_db',
                            remoteSqlPath: '/root/moviebyte.com/moviebyte.sql',
                            sqlFileName: 'moviebyte.sql',
                        ),
                    ],
                    rsyncConfigs: [
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/moviebyte_uploads-volume/_data/',
                            destinationDirectoryName: 'uploads',
                        ),
                    ],
                ),
                new BackupConfig(
                    name: 'nightowl',
                    sshHost: '5.161.94.209',
                    sshUsername: 'root',
                    localBackupFolderName: 'nightowl.fm',
                    databaseConfigs: [
                        new BackupPostgresDatabaseConfig(
                            dbUser: 'nightowl',
                            dbPassword: $runtimeConfig->getString(
                                from: RuntimeConfigOptions::NIGHT_OWL_DB_PASSWORD,
                            ),
                            dbName: 'nightowl',
                            dbContainerName: 'nightowl_db',
                            remoteSqlPath: '/root/nightowl.fm/nightowl.psql',
                            sqlFileName: 'nightowl.psql',
                        ),
                    ],
                    rsyncConfigs: [
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/nightowl_files-volume/_data/',
                            destinationDirectoryName: 'public/files',
                        ),
                        new BackupRsyncConfig(
                            rootRelativeServerSrcDir: 'var/lib/docker/volumes/nightowl_episodes-volume/_data/',
                            destinationDirectoryName: 'episodes',
                        ),
                    ],
                ),
            ]),
        );
    }
}
