<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

class ClassNameFromPathGenerator
{
    public static function getFullClassNameFromFile(string $path): ?string
    {
        if (is_dir($path)) {
            return null;
        }

        $file = file_get_contents($path);

        if (!$file) {
            return null;
        }

        preg_match('/(?<=namespace\s)(.*)(?=;).*(?<=class\s)(.*)(?=\s)/sU', $file, $matches);

        if (isset($matches[1], $matches[2])) {
            return implode("\\", array_slice($matches, 1, 2));
        }

        return null;
    }
}
