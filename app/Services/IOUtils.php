<?php

namespace App\Services;

use RuntimeException;

class IOUtils
{
    public function readFile(string $filename): string
    {
        $content = file_get_contents($filename);

        if ($content === false) {
            throw new RuntimeException("Failed to read file: $filename");
        }

        return $content;
    }

    // Add other file-related methods if needed
}
