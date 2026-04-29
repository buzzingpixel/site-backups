<?php

declare(strict_types=1);

namespace App\Backup\Config;

readonly class BackupMysqlDatabaseConfig
{
    public function __construct(
        public string $dbUser,
        public string $dbPassword,
        public string $dbName,
        public string $dbContainerName,
        public string $remoteSqlPath,
        public string $sqlFileName,
        public string $localBackupFolderName,
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
}
