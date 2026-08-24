<?php

namespace App\Semgrep;

class TC23_LocalFileInclusion
{
    public function renderPage(): void
    {
        $page = $_GET['page'] ?? 'home';

        include $page . '.php';
    }
}
