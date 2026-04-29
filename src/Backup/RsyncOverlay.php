<?php

declare(strict_types=1);

namespace App\Backup;

use ErrorException;
use PhpRsync\Rsync;

use function array_filter;
use function array_slice;
use function array_splice;
use function exec;
use function explode;
use function implode;
use function rtrim;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

class RsyncOverlay extends Rsync
{
    /**
     * @throws ErrorException
     *
     * @phpstan-ignore-next-line
     */
    public function runPull(
        string $sourceDirectory,
        string $destinationDirectory,
        array $options = [],
        $returnCommand = false,
    ): int|string {
        $options = $this->mergeOptionsWithDefaults($options);

        $command = explode(
            ' ',
            /** @phpstan-ignore-next-line */
            $this->compileCommand(
                sourceDirectory: $sourceDirectory,
                destinationDirectory: $destinationDirectory,
                /** @phpstan-ignore-next-line */
                options: $options,
            ),
        );

        // Remove last 3 items
        $command = array_slice($command, 0, -3);

        array_splice(
            $command,
            3,
            0,
            ['-o StrictHostKeyChecking=no'],
        );

        $command[] = $this->connection->getDestination(
            /** @phpstan-ignore-next-line */
            $this->standardizeDirectory($sourceDirectory),
        );

        $command[] = rtrim(
            /** @phpstan-ignore-next-line */
            $this->standardizeDirectory($destinationDirectory),
            '/',
        );

        $command[] = '2>&1'; // redirect to STDOUT

        /** @phpstan-ignore-next-line */
        $command = implode(' ', array_filter($command));

        if ($returnCommand) {
            return $command;
        }

        exec($command, $output, $resultCode);

        /** @phpstan-ignore-next-line */
        if ($options['dryrun'] === true && $this->logger) {
            $this->logger->info('RSYNC dry run', [
                'command' => $command,
                'output' => $output,
            ]);
        }

        if ($resultCode > 0) {
            throw new ErrorException(
                'RSYNC failed. ' . implode('\n', $output),
            );
        }

        return $resultCode;
    }
}
