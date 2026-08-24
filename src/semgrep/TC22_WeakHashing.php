<?php

namespace App\Semgrep;

class TC22_WeakHashing
{
    public function hashPassword(string $password): string
    {
        return md5($password);
    }
}
