<?php

namespace App\Semgrep;

class TC32_Ssrf
{
    public function fetchUrl(): string
    {
        $url = $_GET['url'] ?? '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
