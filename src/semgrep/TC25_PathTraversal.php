<?php

namespace App\Semgrep;

class TC25_PathTraversal
{
    public function download(): void
    {
        $filename = $_GET['file'] ?? '';
        $path = __DIR__ . '/../../storage/' . $filename;

        readfile($path);
    }
}
