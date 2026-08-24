<?php

namespace App\Semgrep;

class TC30_DynamicCodeExecution
{
    public function instantiate()
    {
        $className = $_GET['class'] ?? '';

        return new $className();
    }

    public function callFunction()
    {
        $fn = $_GET['fn'] ?? '';

        return call_user_func($fn);
    }
}
