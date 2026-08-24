<?php

namespace App\Semgrep;

class TC33_LogInjection
{
    public function logAction(): void
    {
        $action = $_GET['action'] ?? '';

        error_log('User action: ' . $action);
    }
}
