# PHP Sample Testcase Project

Project mẫu dùng để validate tool scan bảo mật (Semgrep, Trivy) trên ngôn ngữ PHP. Không dùng cho production.

## Yêu cầu

- PHP >= 7.4 (đã kiểm tra máy hiện tại: PHP 7.4.20, extension `pdo_sqlite` có sẵn — không cần cài SQLite riêng)
- Composer (đã kiểm tra máy hiện tại: 2.6.6, có sẵn)

Không cần cài `sqlite3` CLI — dùng PHP (`pdo_sqlite`) để tạo DB.

## Cài đặt (chạy trong terminal VSCode — PowerShell, cd vào đúng thư mục project trước)

```
cd d:\EC_50\php-sample-testcase
composer install
php db/init.php
```

Lệnh `composer install` đọc `composer.json`, tải các package (kể cả `phpmailer/phpmailer` bản cũ dùng cho TC-01) vào thư mục `vendor/`.
Lệnh `php db/init.php` chạy `db/init.sql` để tạo file `db/sample.sqlite`.

## Chạy thử

```
php -S localhost:8000 -t public
```

Terminal sẽ đứng chờ (server đang chạy) — mở tab terminal mới hoặc mở browser tới http://localhost:8000 để xem. Nhấn `Ctrl+C` trong terminal đó để tắt server.

## Quy trình test case

Danh sách test case tham chiếu tại `TestCase_List_PHP.md` trong thư mục này. Mỗi TC chỉ được implement khi có yêu cầu rõ ràng (ví dụ "test TC-01"), theo đúng mô tả trong "Cách inject" của TC đó — không tự ý mở rộng hoặc thêm lỗ hổng khác.

## Ghi chú về Trivy

`composer.json` khai báo `phpmailer/phpmailer` bản `5.2.10` — bản này nằm trong khoảng version được ghi nhận có lỗ hổng RCE đã biết (thường được nhắc tới là CVE-2016-10033). Cần chạy Trivy thực tế để xác nhận tool có detect đúng CVE ID hay không, vì database CVE của Trivy có thể thay đổi theo thời gian — không nên chỉ dựa vào version number để báo cáo PM.
