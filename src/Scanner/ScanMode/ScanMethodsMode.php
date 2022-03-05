<?php

declare(strict_types=1);

namespace Scanner\AnnotationsScanner;

class ScanMethodsMode implements ScanMode
{
    public function getName(): string
    {
        return 'scan_methods';
    }
}
