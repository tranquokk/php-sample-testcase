<?php

namespace App\Semgrep;

class TC24_HardcodedSecrets
{
    private $dbPassword = 'P@ssw0rd123!';
    private $apiKey = 'internal-api-key-1234567890abcdef';

    public function connect(): string
    {
        return "connecting with password={$this->dbPassword}, apiKey={$this->apiKey}";
    }
}
