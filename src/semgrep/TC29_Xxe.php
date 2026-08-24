<?php

namespace App\Semgrep;

class TC29_Xxe
{
    public function parseImport()
    {
        $xml = $_POST['xml'] ?? '';

        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOENT);
    }
}
