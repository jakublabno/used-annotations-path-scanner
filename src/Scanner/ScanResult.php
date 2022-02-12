<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

class ScanResult
{
    private array $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function getFilePaths(): array
    {
        return $this->result;
    }

    public function getFileDirs(): array
    {
        return array_map(function (string $filePath): string {
            return dirname($filePath);
        }, $this->getFilePaths());
    }
}
