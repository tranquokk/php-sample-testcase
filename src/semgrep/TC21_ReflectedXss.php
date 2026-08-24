<?php

namespace App\Semgrep;

class TC21_ReflectedXss
{
    public function greet(): void
    {
        $name = $_GET['name'] ?? '';

        echo "Hello, " . $name . "!";
    }
}
