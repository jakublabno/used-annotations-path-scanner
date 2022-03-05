<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use Psr\SimpleCache\CacheInterface;
use Scanner\AnnotationsScanner\ScanMode;

class CachedDoctrineAnnotationsScanner implements AnnotationScanner
{
    private const CACHE_KEY = 'annotation_scanner';

    private AnnotationScanner $nativeScanner;
    private CacheInterface $cache;

    public function __construct(AnnotationScanner $nativeScanner, CacheInterface $cache)
    {
        $this->nativeScanner = $nativeScanner;
        $this->cache = $cache;
    }

    public function scan(ScanMode $mode = null): ScanResult
    {
        $key = self::CACHE_KEY;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $paths = $this->nativeScanner->scan($mode);

        $this->cache->set($key, $paths);

        return $paths;
    }
}
