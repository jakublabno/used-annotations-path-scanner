<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

interface AnnotationScanner
{
    public function scan(): ScanResult;
}
