<?php

namespace App\Semgrep;

class TC26_InsecureRandom
{
    public function generateOtp(): string
    {
        return (string) rand(100000, 999999);
    }
}
