<?php

declare(strict_types=1);

namespace App\Backup\Config;

use function array_map;
use function array_values;

readonly class BackupConfigCollection
{
    /** @var BackupConfig[] */
    public array $configs;

    /** @param BackupConfig[] $configs */
    public function __construct(array $configs)
    {
        $this->configs = array_values(array_map(
            static fn (BackupConfig $c) => $c,
            $configs,
        ));
    }

    /**
     * @param callable(BackupConfig): T $callback
     *
     * @template T
     */
    public function walk(callable $callback): void
    {
        array_map($callback, $this->configs);
    }
}
