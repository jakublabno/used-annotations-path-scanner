<?php

declare(strict_types=1);

namespace AnnotationsScanner\Cache;

use Psr\SimpleCache\CacheInterface;

class CacheFactory
{
    public static function createApcuCache(): CacheInterface
    {
        return new MemCache();
    }
}
