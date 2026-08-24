<?php

namespace App\Semgrep;

class TC19_SqlInjection
{
    public function findUserByUsername(): array
    {
        $username = $_GET['username'] ?? '';

        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../db/sample.sqlite');
        $query = "SELECT id, username, email FROM users WHERE username = '" . $username . "'";
        $result = $pdo->query($query);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
