<?php

namespace App\Semgrep;

class TC21_InsecureDeserialization
{
    public function loadPreferences()
    {
        $raw = $_COOKIE['prefs'] ?? '';

        return unserialize($raw);
    }
}
