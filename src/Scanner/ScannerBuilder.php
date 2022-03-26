<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Cache\CacheFactory;
use ArrayIterator;
use Doctrine\Common\Annotations\Reader;
use Psr\SimpleCache\CacheInterface;

class ScannerBuilder
{
    private ?Reader $reader = null;
    private ?CacheInterface $cache = null;
    private string $basePath;
    private array $annotationsToSearchFor = [];
    private string $scanMethod = ScanMethod::COMPOSER;
    private ?string $excludeDirsRegex = null;

    public function withExcludeDirs(string $excludeDirsRegex): self
    {
        $this->excludeDirsRegex = $excludeDirsRegex;

        return $this;
    }

    public function withReader(Reader $reader): self
    {
        $this->reader = $reader;

        return $this;
    }

    public function withDirectoryIteratorScanMethod(): self
    {
        $this->scanMethod = ScanMethod::DIRECTORY_ITERATOR;

        return $this;
    }

    public function withAnnotations(string ...$annotations): self
    {
        $this->annotationsToSearchFor = $annotations;

        return $this;
    }

    public function withBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function withCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public function build(): AnnotationScanner
    {
        if (!isset($this->basePath)) {
            throw BasePathMustBeSetException::create();
        }

        if ($this->excludeDirsRegex && $this->scanMethod == ScanMethod::COMPOSER) {
            throw new ExcludeDirsNotAvailableException('Exclude function available only when directory scan method is in use');
        }

        $collection = new AnnotationsToSearchCollection(new ArrayIterator($this->annotationsToSearchFor));

        if ($this->reader) {
            $scanner = new DoctrineAnnotationsScanner($collection, $this->basePath, $this->scanMethod, $this->excludeDirsRegex, $this->reader);
        } else {
            $scanner = new DoctrineAnnotationsScanner($collection, $this->basePath, $this->scanMethod, $this->excludeDirsRegex);
        }

        return new CachedDoctrineAnnotationsScanner($scanner, $this->getCache());
    }

    private function getCache(): CacheInterface
    {
        return $this->cache ?? CacheFactory::createApcuCache();
    }
}
