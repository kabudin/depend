<?php

declare(strict_types=1);

namespace Bud\Depend\Collector;

use Hyperf\Di\MetadataCollector;

/**
 * 依赖代理收集器
 */
class DependCollector extends MetadataCollector
{
    protected static array $container = [];

    public static function setAround(string $className, string $interface, int $priority)
    {
        static::$container[$className] = [$interface, $priority];
    }
}
