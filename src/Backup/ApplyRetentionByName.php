<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfigCollection;

readonly class ApplyRetentionByName
{
    public const string JOB_HANDLE = 'apply-retention-by-name';

    public const string JOB_NAME = 'Apply retention by name';

    public function __construct(
        private ApplyRetentionCommand $retentionCommand,
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

        $this->retentionCommand->runItem(config: $config);
    }
}
