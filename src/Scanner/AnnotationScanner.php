<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use Scanner\AnnotationsScanner\ScanMode;

interface AnnotationScanner
{
    public function scan(ScanMode $mode = null): ScanResult;
}
