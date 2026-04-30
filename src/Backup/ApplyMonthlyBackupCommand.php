<?php

declare(strict_types=1);

namespace App\Backup;

use App\Backup\Config\BackupConfig;
use App\Backup\Config\BackupConfigCollection;
use DateTimeImmutable;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;
use SplFileInfo;

use function array_filter;
use function array_values;
use function assert;
use function copy;
use function count;
use function glob;
use function is_dir;
use function mkdir;
use function preg_match;
use function rsort;
use function strlen;
use function substr;

use const GLOB_ONLYDIR;

readonly class ApplyMonthlyBackupCommand
{
    public static function registerCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(expression: 'apply-monthly-backup', callable: self::class);
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
        $now = new DateTimeImmutable();

        if ($now->format('j') !== $now->format('t')) {
            return;
        }

        $baseDir = BackupDirHandler::DIR_NAME . '/' . $config->localBackupFolderName;

        $globResult = glob($baseDir . '/*', GLOB_ONLYDIR);

        $dirs = array_values(array_filter(
            $globResult !== false ? $globResult : [],
            static fn (string $dir) => preg_match('/\/\d{14}$/', $dir) === 1,
        ));

        if (count($dirs) === 0) {
            return;
        }

        rsort($dirs);

        $sourceDir = $dirs[0];

        $destDir = MonthlyBackupDirHandler::DIR_NAME
            . '/' . $config->localBackupFolderName
            . '/' . $now->format('Ym');

        if (is_dir($destDir)) {
            return;
        }

        $this->copyDirectory($sourceDir, $destDir);
    }

    private function copyDirectory(string $sourceDir, string $destDir): void
    {
        mkdir($destDir, 0777, true);

        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $sourceDir,
                FilesystemIterator::SKIP_DOTS,
            ),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($items as $item) {
            assert($item instanceof SplFileInfo);

            $relativePath = substr($item->getPathname(), strlen($sourceDir) + 1);
            $destPath = $destDir . '/' . $relativePath;

            if ($item->isDir()) {
                mkdir($destPath, 0777, true);
            } else {
                copy($item->getPathname(), $destPath);
            }
        }
    }
}
