<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Scanner\ScanMode\ScanMode;

interface AnnotationScanner
{
    public function scan(ScanMode $mode = null): ScanResult;
}
