<?php

namespace App\Semgrep;

class TC28_Xxe
{
    public function parseImport()
    {
        $xml = $_POST['xml'] ?? '';

        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOENT);
    }
}
