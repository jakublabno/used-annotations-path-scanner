<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

trait ExcludeTrait
{
    private ?string $excludeRegex = null;

    public function setExcludeRegex(string $regex): void
    {
        $this->excludeRegex = $regex;
    }
}
