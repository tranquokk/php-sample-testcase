<?php

namespace App\Semgrep;

class TC30_CodeInjectionEval
{
    public function calculate(): void
    {
        $expr = $_GET['expr'] ?? '';

        eval('$result = ' . $expr . ';');
    }
}
