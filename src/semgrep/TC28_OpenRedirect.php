<?php

namespace App\Semgrep;

class TC28_OpenRedirect
{
    public function redirect(): void
    {
        $redirectTo = $_GET['redirect_to'] ?? '/';

        header('Location: ' . $redirectTo);
    }
}
