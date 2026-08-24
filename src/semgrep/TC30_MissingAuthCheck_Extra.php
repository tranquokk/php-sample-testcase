<?php

namespace App\Semgrep;

class TC30_MissingAuthCheck_Extra
{
    // Case phụ TC-30: thiếu kiểm tra quyền admin trước khi xoá user.
    // Lỗ hổng business logic thuần, không có pattern cú pháp nguy hiểm -> expected miss.
    public function deleteUser(int $userId): string
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../db/sample.sqlite');
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $userId]);

        return "User {$userId} deleted";
    }
}
