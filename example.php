<?php

declare(strict_types=1);

use AnnotationsScanner\Scanner\ScannerFactory;
use AnnotationsScanner\Scanner\ScanResult;
use MyAnnotations\YourAnnotation;
use MyAnnotations\AnotherAnnotation;

$scanner = ScannerFactory::createWithDefaultReader(
    '/app',
    YourAnnotation::class,
    AnotherAnnotation::class,
);

/**
 * @returns ScanResult
 */
$result = $scanner->scan();

$foundFilePaths = $result->getFilePaths();

$foundDirectories = $result->getFileDirs();


$scanner = ScannerFactory::createWithAnnotationReaderAndCache( '/app', $reader, $cache);
