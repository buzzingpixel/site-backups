<?php

declare(strict_types=1);

namespace App\Backup\Sql;

use App\Backup\Config\BackupDatabaseConfig;
use App\Backup\Config\BackupMysqlDatabaseConfig;
use App\Backup\Config\BackupPostgresDatabaseConfig;
use BuzzingPixel\Templating\TemplateEngineFactory;
use InvalidArgumentException;

use function trim;

readonly class DumpRemoteDbScriptFactory
{
    public function __construct(
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function create(BackupDatabaseConfig $config): string
    {
        $templatePath = match ($config::class) {
            BackupMysqlDatabaseConfig::class => __DIR__ . '/DumpRemoteMysqlDb.phtml',
            BackupPostgresDatabaseConfig::class => __DIR__ . '/DumpRemotePostgresDb.phtml',
            default => throw new InvalidArgumentException('Unsupported database type'),
        };

        return trim(
            string: $this->templateEngineFactory->create()
                    ->templatePath(templatePath: $templatePath)
                    ->vars($config->asArray())
                    ->render(),
        );
    }
}
