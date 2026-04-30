<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use FilesystemIterator;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function array_filter;
use function array_slice;
use function array_values;
use function assert;
use function count;
use function glob;
use function preg_match;
use function rmdir;
use function rsort;
use function unlink;

use const GLOB_ONLYDIR;

readonly class ApplyRetentionCommand
{
    public static function registerCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(expression: 'apply-retention', callable: self::class);
    }

    public function __construct(private BackupConfigCollection $config)
    {
    }

    public function __invoke(): void
    {
        $this->run();
    }

    public function run(): void
    {
        $this->config->walk(callback: [$this, 'runItem']);
    }

    public function runItem(BackupConfig $config): void
    {
        $baseDir = BackupDirHandler::DIR_NAME . '/' . $config->localBackupFolderName;

        $globResult = glob($baseDir . '/*', GLOB_ONLYDIR);

        $dirs = array_values(array_filter(
            $globResult !== false ? $globResult : [],
            static fn (string $dir) => preg_match('/\/\d{14}$/', $dir) === 1,
        ));

        if (count($dirs) <= $config->retentionCount) {
            return;
        }

        rsort($dirs);

        $toDelete = array_slice($dirs, $config->retentionCount);

        foreach ($toDelete as $dir) {
            $this->deleteDirectory($dir);
        }
    }

    private function deleteDirectory(string $dir): void
    {
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                FilesystemIterator::SKIP_DOTS,
            ),
            RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($items as $item) {
            assert($item instanceof SplFileInfo);
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($dir);
    }
}
