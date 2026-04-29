<?php

declare(strict_types=1);

namespace App\Backup\Config;

interface BackupDatabaseConfig
{
    /**
     * @return array{
     *     dbUser: string,
     *     dbPassword: string,
     *     dbName: string,
     *     dbContainerName: string,
     *     remoteSqlPath: string,
     *     sqlFileName: string,
     * }
     */
    public function asArray(): array;

    public function dbUser(): string;

    public function dbPassword(): string;

    public function dbName(): string;

    public function dbContainerName(): string;

    public function remoteSqlPath(): string;

    public function sqlFileName(): string;
}
