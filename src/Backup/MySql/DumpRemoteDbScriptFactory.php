<?php

declare(strict_types=1);

namespace App\Backup\MySql;

use App\Backup\Config\BackupMysqlDatabaseConfig;
use BuzzingPixel\Templating\TemplateEngineFactory;

use function trim;

readonly class DumpRemoteDbScriptFactory
{
    public function __construct(
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function create(BackupMysqlDatabaseConfig $config): string
    {
        return trim(
            $this->templateEngineFactory->create()
                    ->templatePath(__DIR__ . '/DumpRemoteDb.phtml')
                    ->vars($config->asArray())
                    ->render(),
        );
    }
}
