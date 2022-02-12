<?php

declare(strict_types=1);

namespace AnnotationsScanner\Cache;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Psr16Cache;

class MemCache implements CacheInterface
{
    private CacheInterface $cache;

    public function __construct()
    {
        $this->cache = new Psr16Cache(new ApcuAdapter());
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    public function set($key, $value, $ttl = null): void
    {
        $this->cache->set($key, $value, $ttl);
    }

    public function delete($key): void
    {
        $this->cache->delete($key);
    }

    public function clear(): void
    {
        $this->cache->clear();
    }

    public function getMultiple($keys, $default = null): iterable
    {
        return $this->cache->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null): void
    {
        $this->cache->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys): void
    {
        $this->cache->deleteMultiple($keys);
    }

    public function has($key): bool
    {
        return $this->cache->has($key);
    }
}
