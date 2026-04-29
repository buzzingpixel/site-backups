<?php

declare(strict_types=1);

namespace App\Ssh;

use RuntimeException;

use function stream_get_contents;
use function stream_set_blocking;

readonly class SshConnection
{
    public bool $connected;

    /** @var resource|null */
    private mixed $connection;

    public function __construct(
        public string $host,
        public string $userName,
    ) {
        $connection = ssh2_connect($host);

        if ($connection === false) {
            $this->connected = false;

            $this->connection = null;

            return;
        }

        $auth = ssh2_auth_pubkey_file(
            $connection,
            $userName,
            '/root/.ssh/id_rsa.pub',
            '/root/.ssh/id_rsa',
        );

        if (! $auth) {
            $this->connected = false;

            $this->connection = null;

            return;
        }

        $this->connected = true;

        $this->connection = $connection;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function runCommand(string $command): string
    {
        if ($this->connection === null) {
            throw new RuntimeException(
                'Failed to execute command: ssh is not connected',
            );
        }

        $stream = ssh2_exec($this->connection, $command);

        if ($stream === false) {
            throw new RuntimeException('Failed to execute command');
        }

        stream_set_blocking($stream, true);

        $errorStream = ssh2_fetch_stream(
            $stream,
            SSH2_STREAM_STDERR,
        );

        if ($errorStream === false) {
            throw new RuntimeException('Failed to execute command');
        }

        $error = stream_get_contents($errorStream);

        if ($error !== '') {
            throw new RuntimeException($error);
        }

        return stream_get_contents($stream);
    }

    public function scpPull(string $source, string $destination): void
    {
        if ($this->connection === null) {
            throw new RuntimeException(
                'Failed to execute command: ssh is not connected',
            );
        }

        $stream = ssh2_scp_recv(
            $this->connection,
            $source,
            $destination,
        );

        if ($stream === false) {
            throw new RuntimeException(
                'Failed to execute scp pull: ' . $source . ' to ' . $destination,
            );
        }
    }

    public function close(): void
    {
        if ($this->connection === null) {
            return;
        }

        ssh2_disconnect($this->connection);
    }
}
