<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Mother;

use Psr\SimpleCache\CacheInterface;

class FakeCacheMother
{
    public static function withResult($result): CacheInterface
    {
        return new class($result) implements CacheInterface {

            private $result;

            public function __construct($result)
            {
                $this->result = $result;
            }

            public function get($key, $default = null)
            {
                return $this->result;
            }

            public function set($key, $value, $ttl = null)
            {
            }

            public function delete($key)
            {
            }

            public function clear()
            {
            }

            public function getMultiple($keys, $default = null)
            {
            }

            public function setMultiple($values, $ttl = null)
            {
            }

            public function deleteMultiple($keys)
            {
            }

            public function has($key)
            {
                return true;
            }
        };
    }
}
