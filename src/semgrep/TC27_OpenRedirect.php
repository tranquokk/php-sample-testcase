<?php

namespace App\Semgrep;

class TC27_OpenRedirect
{
    public function redirect(): void
    {
        $redirectTo = $_GET['redirect_to'] ?? '/';

        header('Location: ' . $redirectTo);
    }
}
