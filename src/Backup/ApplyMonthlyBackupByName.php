<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfigCollection;

readonly class ApplyMonthlyBackupByName
{
    public const string JOB_HANDLE = 'apply-monthly-backup-by-name';

    public const string JOB_NAME = 'Apply monthly backup by name';

    public function __construct(
        private ApplyMonthlyBackupCommand $monthlyBackupCommand,
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

        $this->monthlyBackupCommand->runItem(config: $config);
    }
}
