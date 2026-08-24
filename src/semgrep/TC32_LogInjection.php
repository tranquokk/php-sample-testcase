<?php

namespace App\Semgrep;

class TC32_LogInjection
{
    public function logAction(): void
    {
        $action = $_GET['action'] ?? '';

        error_log('User action: ' . $action);
    }
}
