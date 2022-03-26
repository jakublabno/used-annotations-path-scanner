<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use Doctrine\Common\Annotations\Reader;
use Psr\SimpleCache\CacheInterface;

class ScannerFactory
{
    public static function createWithDefaultReader(string $basePath, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->build();
    }

    public static function createWithDefaultReaderDirectoryIteratorScanImpl(string $basePath, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withDirectoryIteratorScanMethod()
            ->build();
    }

    public static function createWithDefaultReaderDirectoryIteratorScanImplAndCache(string $basePath, CacheInterface $cache, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withDirectoryIteratorScanMethod()
            ->withCache($cache)
            ->build();
    }

    public static function createWithDefaultReaderAndCache(string $basePath, CacheInterface $cache, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withCache($cache)
            ->build();
    }

    public static function createWithAnnotationReader(string $basePath, Reader $annotationReader, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withReader($annotationReader)
            ->build();
    }

    public static function createWithAnnotationReaderAndCache(
        string         $basePath,
        Reader         $annotationReader,
        CacheInterface $cache,
        string         ...$annotations
    ): AnnotationScanner {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withReader($annotationReader)
            ->withCache($cache)
            ->build();
    }
}
