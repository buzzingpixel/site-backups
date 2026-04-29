<?php

declare(strict_types=1);

namespace App\Backup\Config;

readonly class BackupRsyncConfig
{
    public function __construct(
        public string $rootRelativeServerSrcDir,
        public string $destinationDirectoryName,
    ) {
    }
}
