<?php

declare(strict_types=1);

namespace Config\Dependencies;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Redis;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class CacheBindings
{
    public function __invoke(Bindings $bindings): void
    {
        $bindings->addBinding(
            CacheItemPoolInterface::class,
            $bindings->resolveFromContainer(RedisAdapter::class),
        );

        $bindings->addBinding(
            RedisAdapter::class,
            static function (ContainerInterface $container): RedisAdapter {
                return new RedisAdapter(
                    $container->get(Redis::class),
                    'buzzingpixel-site-backups',
                );
            },
        );

        $bindings->addBinding(
            Redis::class,
            static function (ContainerInterface $container): Redis {
                $redis = new Redis();

                $redis->connect(
                    'buzzingpixel-site-backups-redis',
                );

                return $redis;
            },
        );
    }
}
