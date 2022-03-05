<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner\ScanMode;

class ScanMethodsMode implements ScanMode
{
    public function getName(): string
    {
        return 'scan_methods';
    }
}
