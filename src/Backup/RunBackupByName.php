<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfigCollection;

readonly class RunBackupByName
{
    public const string JOB_HANDLE = 'run-backup-by-name';

    public const string JOB_NAME = 'Run backup by name';

    public function __construct(
        private BackupCommand $backupCommand,
        private BackupConfigCollection $config,
    ) {
    }

    /** @param array{name: string} $config */
    public function __invoke(array $config): void
    {
        $this->run(name: $config['name']);
    }

    public function run(string $name): void
    {
        $config = $this->config->getByName(name: $name);

        $this->backupCommand->runItem(config: $config);
    }
}
