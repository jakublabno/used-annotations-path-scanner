<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use Doctrine\Common\Annotations\Reader;

class ScannerFactory
{
    public static function createWithDefaultReader(string $basePath, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->build();
    }

    public static function createWithAnnotationReader(Reader $annotationReader, string $basePath, string ...$annotations): AnnotationScanner
    {
        return (new ScannerBuilder())
            ->withAnnotations(...$annotations)
            ->withBasePath($basePath)
            ->withReader($annotationReader)
            ->build();
    }
}
