<?php

namespace App\Semgrep;

class TC29_CodeInjectionEval
{
    public function calculate(): void
    {
        $expr = $_GET['expr'] ?? '';

        eval('$result = ' . $expr . ';');
    }
}
