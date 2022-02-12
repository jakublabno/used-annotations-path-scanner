Annotated methods scanner
-----------------

Scanner looks for methods annotated with provided annotations, outputs an array of either filepaths or directories.

[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/goaop/framework.svg)](https://packagist.org/packages/goaop/framework)

Installation
------------

### Step 1: Download the library using composer

Require package by composer

``` bash
composer require jlabno/annotations-scanner
```

### Step 2: How to use

By default, library uses Apcu cache, you can pass your own cache implementation of `Psr\SimpleCache`

``` php
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
//['/app/src/classes/MyClass.php', '/app/src/classes/deeper/MyAnotherClass.php']

$foundDirectories = $result->getFileDirs();
//['/app/src/classes', '/app/src/classes/deeper']
```


### 2. Optional features

You can use your own cache which implements `Psr\SimpleCache`

``` php
$cache = new \Symfony\Component\Cache\Psr16Cache(new \Symfony\Component\Cache\Adapter\NullAdapter());
$scanner = ScannerFactory::createWithDefaultReaderAndCache('/app', $cache);
```

You can use your own annotation reader compliant with `Doctrine\Common\Annotations\Reader`

``` php
$scanner = ScannerFactory::createWithAnnotationReader( '/app', $reader);
```
or with custom cache

``` php
$scanner = ScannerFactory::createWithAnnotationReaderAndCache( '/app', $reader, $cache);
```
