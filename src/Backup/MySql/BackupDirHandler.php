<?php

declare(strict_types=1);

namespace App\Backup\MySql;

use DateTimeImmutable;

use function implode;
use function mkdir;

readonly class BackupDirHandler
{
    public const string DIR_NAME = '/storage/backups';

    public string $backupDir;

    public function __construct(public string $projectDir)
    {
        $backupDate = new DateTimeImmutable();

        $this->backupDir = implode('/', [
            self::DIR_NAME,
            $this->projectDir,
            $backupDate->format('YmdHis'),
        ]);

        mkdir($this->backupDir, recursive: true);
    }

    public function createFilePath(string $fileName): string
    {
        return implode('/', [
            $this->backupDir,
            $fileName,
        ]);
    }
}
