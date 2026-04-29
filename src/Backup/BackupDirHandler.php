<?php

declare(strict_types=1);

namespace App\Backup;

use DateTimeImmutable;

use function implode;
use function ltrim;
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

    public function createFilePath(
        string $fileName,
        bool $trimLeadingSlash = false,
    ): string {
        $dir = implode('/', [
            $this->backupDir,
            $fileName,
        ]);

        if ($trimLeadingSlash) {
            $dir = ltrim($dir, '/');
        }

        return $dir;
    }

    public function mkDirInBackupDir(string $dirName): bool
    {
        return mkdir(
            $this->createFilePath($dirName),
            0777,
            true,
        );
    }
}
