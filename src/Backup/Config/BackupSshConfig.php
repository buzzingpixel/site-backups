<?php

declare(strict_types=1);

namespace App\Backup\Config;

readonly class BackupSshConfig
{
    public function __construct(
        public string $host,
        public string $user,
        public string $privateKey,
    ) {
    }
}
