<?php

namespace App\Semgrep;

class TC20_OsCommandInjection
{
    public function pingHost(): string
    {
        $host = $_GET['host'] ?? '';

        return shell_exec('ping -c 1 ' . $host);
    }
}
