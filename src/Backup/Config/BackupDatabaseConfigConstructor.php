<?php

declare(strict_types=1);

namespace App\Backup\Config;

trait BackupDatabaseConfigConstructor
{
    public function __construct(
        public readonly string $dbUser,
        public readonly string $dbPassword,
        public readonly string $dbName,
        public readonly string $dbContainerName,
        public readonly string $remoteSqlPath,
        public readonly string $sqlFileName,
    ) {
    }

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
    public function asArray(): array
    {
        return [
            'dbUser' => $this->dbUser,
            'dbPassword' => $this->dbPassword,
            'dbName' => $this->dbName,
            'dbContainerName' => $this->dbContainerName,
            'remoteSqlPath' => $this->remoteSqlPath,
            'sqlFileName' => $this->sqlFileName,
        ];
    }

    public function dbUser(): string
    {
        return $this->dbUser;
    }

    public function dbPassword(): string
    {
        return $this->dbPassword;
    }

    public function dbName(): string
    {
        return $this->dbName;
    }

    public function dbContainerName(): string
    {
        return $this->dbContainerName;
    }

    public function remoteSqlPath(): string
    {
        return $this->remoteSqlPath;
    }

    public function sqlFileName(): string
    {
        return $this->sqlFileName;
    }
}
