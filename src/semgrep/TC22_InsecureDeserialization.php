<?php

namespace App\Semgrep;

class TC22_InsecureDeserialization
{
    public function loadPreferences()
    {
        $raw = $_COOKIE['prefs'] ?? '';

        return unserialize($raw);
    }
}
