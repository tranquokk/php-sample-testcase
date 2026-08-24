<?php
// Tạo file db/sample.sqlite từ db/init.sql bằng PDO (không cần cài sqlite3 CLI riêng).
// Chạy: php db/init.php
$dbPath = __DIR__ . '/sample.sqlite';
$sql = file_get_contents(__DIR__ . '/init.sql');

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->exec($sql);

echo "OK: da tao " . $dbPath . PHP_EOL;
