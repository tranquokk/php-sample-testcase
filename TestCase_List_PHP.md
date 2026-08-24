# Danh Sách Test Case - Validation Toolchain Bảo Mật (PHP Sample Project)

Dự án: PHP thuần (không framework), SQLite, dependency quản lý bằng Composer.
Tool: ScanCode (license/copyright), Semgrep (SAST), Trivy (SCA/CVE + license dependency). OWASP ZAP (DAST) — chưa test cho project này (ưu tiên test ZAP trên project Java trước), để dành làm sau khi có thời gian.

Đánh số **TC-01 → TC-32**, tách riêng khỏi số TC của project Java (`security-check/TestCase_List.md`).

Đã đối chiếu 1-1 với `security-check/TestCase_List.md` (Nhóm 1–6 phía Java). Java TC-08 (ScanCode nhận diện license viết tiếng Nhật) không lặp lại ở đây — test đó kiểm tra năng lực engine ScanCode (đọc đa ngôn ngữ), không phụ thuộc code Java hay PHP nên không cho thêm thông tin mới.

## Trạng thái tổng quan

| Nhóm | Tool | Trạng thái |
|---|---|---|
| Nhóm 1 — License/Copyright trong Source Code | ScanCode | Đã implement code, chưa chạy tool thực tế |
| Nhóm 2 — License + CVE của dependency (Composer) | Trivy | Đã implement + đã verify license/CVE qua `composer show`/`composer audit`, chưa chạy Trivy thực tế |
| Nhóm 3 — SAST nghiêm trọng cao | Semgrep | Chưa implement |
| Nhóm 4 — SAST cần kiểm tra | Semgrep | Chưa implement |
| Nhóm 5 — Đề xuất bổ sung | Semgrep | Chưa implement |
| Nhóm 6 — OWASP ZAP (DAST) | OWASP ZAP | Không nằm trong phạm vi lần này |

---

## NHÓM 1: License / Copyright trong Source Code (ScanCode)

### TC-01 — Header GPL-3.0 dán vào code tự viết
- **Cách dựng:** Tạo file `.php`, dán nguyên comment header GPL-3.0 (copy từ 1 project GPL thật) ở đầu file
- **Kết quả kỳ vọng:** Bị flag vào nhóm copyleft dù là code tự viết, không phải lib
- **Mức độ:** Cao
- **File:** `src/scancode/TC01_GplHeader.php`

### TC-02 — SPDX identifier chuẩn (MIT)
- **Cách dựng:** Thêm dòng `// SPDX-License-Identifier: MIT` ở đầu file `.php`
- **Kết quả kỳ vọng:** Detect đúng license expression MIT → nhóm permissive, không cảnh báo
- **Mức độ:** Trung bình
- **File:** `src/scancode/TC02_SpdxMit.php`

### TC-03 — Copy code GPL nhưng không kèm comment license
- **Cách dựng:** Copy 1 đoạn code từ project GPL nhưng KHÔNG dán license/copyright comment
- **Kết quả kỳ vọng:** Không phát hiện được (expected miss — blind spot)
- **Mức độ:** Thấp (nhưng quan trọng)
- **Ghi chú:** ScanCode không suy luận nguồn gốc code nếu không có text khai báo
- **File:** `src/scancode/TC03_CopiedNoLicense.php`

### TC-04 — Comment license sai định dạng / không chuẩn SPDX
- **Cách dựng:** Viết tay dòng comment kiểu `"License: GPL v3"` (không đúng cú pháp SPDX)
- **Kết quả kỳ vọng:** Test khả năng nhận diện biến thể text phổ biến, hay chỉ nhận format chuẩn
- **Mức độ:** Trung bình
- **File:** `src/scancode/TC04_NonStandardLicenseComment.php`

### TC-05 — SPDX cho các nhóm license còn lại (GPL/LGPL/Apache)
- **Cách dựng:** Tạo 3 file riêng với `SPDX-License-Identifier: GPL-3.0-only` / `LGPL-2.1-only` / `Apache-2.0`
- **Kết quả kỳ vọng:** Phân loại đúng cả 3 nhóm qua SPDX
- **Mức độ:** Cao
- **File:** `src/scancode/TC05_SpdxGpl.php`, `TC05_SpdxLgpl.php`, `TC05_SpdxApache.php`

### TC-06 — Copyright text đơn thuần, không license
- **Cách dựng:** Comment `// Copyright (c) 2026 ABC Corp. All rights reserved.` — không có license identifier
- **Kết quả kỳ vọng:** Vào nhóm "cần kiểm tra thủ công" hoặc im lặng
- **Mức độ:** Trung bình
- **File:** `src/scancode/TC06_CopyrightOnly.php`

### TC-07 — License text trong file không phải .php
- **Cách dựng:** Dán SPDX/comment license vào file `.sql`, `.md` — thay `composer.json` bằng file `.ini` riêng để tránh làm hỏng `composer.json` gốc (đang dùng thật cho Nhóm 2/Trivy)
- **Kết quả kỳ vọng:** Xác nhận ScanCode quét hết mọi loại file trong source tree, không chỉ `.php`
- **Mức độ:** Cao
- **File:** `src/scancode/TC07_license_note.sql`, `TC07_license_note.md`, `TC07_license_note.ini`

### TC-08 — Tín hiệu mâu thuẫn trong cùng 1 file
- **Cách dựng:** Đầu file ghi `SPDX-License-Identifier: MIT` nhưng phía dưới dán nguyên văn bản GPL-3.0
- **Kết quả kỳ vọng:** Xem tool ưu tiên tín hiệu nào, có báo conflict/warning không
- **Mức độ:** Thấp (edge case)
- **File:** `src/scancode/TC08_ConflictingSignals.php`

### TC-09 — SPDX sai cú pháp / typo
- **Cách dựng:** 3 biến thể SPDX sai cú pháp cho GPL-3.0 trong cùng 1 file: `"GPL-3.0"` (thừa ngoặc kép), `GPL-3.0-only ` (thừa khoảng trắng cuối dòng), `GPL 3.0` (thiếu dấu gạch ngang)
- **Kết quả kỳ vọng:** Xác định hành vi thực tế của tool (bỏ qua / nhận sai nhóm / vẫn nhận đúng) — edge case mang tính điều tra, không có đáp án đúng/sai cố định trước
- **Mức độ:** Thấp (edge case)
- **File:** `src/scancode/TC09_SpdxTypo.php`

---

## NHÓM 2: License + CVE của dependency (Trivy)

### TC-10 — Proprietary
- **Cách dựng:** Tự tạo 1 composer package local (khai qua Composer `path` repository trong `composer.json` root, không public lên Packagist), `internal/report-engine`, khai `"license": ["proprietary"]`
- **Kết quả kỳ vọng:** Vào danh sách "Thư viện yêu cầu bản quyền"
- **Mức độ:** Trung bình
- **File:** `local-packages/internal-report-engine/` (composer.json + src/ReportEngine.php)
- **Đã verify:** `composer show internal/report-engine` → `license: proprietary` ✓ đúng như khai

### TC-11 — Strong-copyleft (GPL-2.0-or-later)
- **Cách dựng:** Add package `johnpbloch/wordpress` (bản mirror WordPress core cho Composer)
- **Kết quả kỳ vọng:** Vào danh sách "Thư viện yêu cầu công khai source"
- **Mức độ:** Cao
- **Đã verify:** `composer show johnpbloch/wordpress` → `license: GNU General Public License v2.0 or later (GPL-2.0-or-later)` — confirm đúng dự đoán ban đầu
- **Ghi chú quan trọng:** Package cài kèm 1 Composer plugin (`johnpbloch/wordpress-core-installer`) — đã phải bật `allow-plugins` trong `composer.json` root để install được (đã thêm block `config.allow-plugins`). Package tải về khá nặng: toàn bộ WordPress core được đặt ở thư mục `wordpress/` tại root project (~72MB, ngoài `vendor/`) — cân nhắc nếu muốn project gọn hơn có thể đổi sang package GPL khác, báo tôi nếu muốn đổi

### TC-12 — Weak-copyleft (LGPL-2.1)
- **Cách dựng:** Add package `ezyang/htmlpurifier`
- **Kết quả kỳ vọng:** Vào danh sách "Thư viện cần kiểm tra thủ công"
- **Mức độ:** Trung bình
- **Đã verify:** `composer show ezyang/htmlpurifier` → `license: GNU Lesser General Public License v2.1 or later (LGPL-2.1-or-later)` — confirm đúng dự đoán

### TC-13 — Permissive (MIT/Apache-2.0)
- **Cách dựng:** Add package `guzzlehttp/guzzle`
- **Kết quả kỳ vọng:** Không bị cảnh báo license (control case)
- **Mức độ:** Thấp
- **Đã verify:** `composer show guzzlehttp/guzzle` → `license: MIT License (MIT)`. `composer audit` không liệt kê guzzle trong danh sách advisory — control case sạch cả license và CVE tại version đã lock (7.15.3)

### TC-14 — Không có license
- **Cách dựng:** Tự tạo 1 composer package local khác (path repository), KHÔNG khai field `license` trong `composer.json`
- **Kết quả kỳ vọng:** Trivy báo unknown/no-license
- **Mức độ:** Thấp
- **File:** `local-packages/internal-no-license-lib/` (composer.json + src/NoLicenseLib.php)
- **Đã verify:** `composer show internal/no-license-lib` → không có dòng `license` nào xuất hiện (khác hẳn TC-10 có dòng `license: proprietary`) ✓ đúng như khai

### TC-15 — Dependency cũ có CVE công khai đã biết
- **Cách dựng:** `composer.json` khai `phpmailer/phpmailer: 5.2.10` (đã có sẵn trong skeleton project)
- **Kết quả kỳ vọng:** Trivy phát hiện CVE qua `composer.lock`
- **Mức độ:** Cao
- **Đã verify:** `composer audit` xác nhận version 5.2.10 nằm trong ít nhất 7 CVE công khai, bao gồm đúng **CVE-2016-10033** (affected `>=5.0.0,<5.2.18`) như dự đoán ban đầu, cộng thêm CVE-2016-10045, CVE-2017-11503, CVE-2018-19296, CVE-2020-13625, CVE-2021-3603, CVE-2021-34551 — độ tin cậy cao hơn hẳn so với lúc chưa verify, vì `composer audit` dùng chung nguồn advisory (FriendsOfPHP/security-advisories) mà Trivy cũng tham chiếu. Vẫn cần chạy Trivy thực tế để confirm nó có match đúng CVE ID hay không, `composer audit` chỉ là bước cross-check trước
- **Ghi chú license (phát sinh thêm, ngoài phạm vi TC gốc):** `composer show phpmailer/phpmailer` cho thấy license thực tế là `LGPL-2.1` — nếu muốn dùng case này chéo sang test license-of-dependency thì đã có sẵn, không cần tạo thêm

### TC-16 — Dependency bản mới, không có CVE (control case)
- **Cách dựng:** Add package `monolog/monolog`
- **Kết quả kỳ vọng:** Không bị flag CVE (baseline để so sánh với TC-15)
- **Mức độ:** Thấp
- **Ghi chú:** Máy hiện có PHP 7.4.20 — bản `^3.x` của monolog yêu cầu PHP >=8.1 nên không cài được, đã dùng `^2.9` (lock ở 2.11.0, license MIT) thay thế. `composer audit` xác nhận monolog không nằm trong danh sách advisory nào → đúng vai trò control case

### TC-17 — Composer package không có `composer.lock` (mô phỏng thiếu lock file)
- **Cách dựng:** Test trường hợp chỉ có `composer.json`, chưa chạy `composer install`/không commit `composer.lock`
- **Kết quả kỳ vọng:** Xác định hành vi thực tế của Trivy (có tự resolve version từ `composer.json` hay chỉ đọc `composer.lock`) — edge case mang tính điều tra, không có đáp án đúng/sai cố định trước
- **Mức độ:** Trung bình
- **Ghi chú quan trọng:** TC này là **kịch bản thao tác lúc chạy tool**, không phải code cần tạo — không có file nào để implement. Cách test: tạm rename `composer.lock` (vd `composer.lock.bak`) trước khi chạy Trivy, rồi rename trả lại ngay sau — **không xoá vĩnh viễn** vì các TC-10→16 khác đang phụ thuộc lock file này để có version cụ thể

---

## NHÓM 3: SAST — Nghiêm trọng cao (Semgrep) — "Cần fix ngay"

### TC-18 — SQL Injection
- **Cách inject:** Nối chuỗi input người dùng (`$_GET`/`$_POST`) trực tiếp vào câu query SQLite qua `PDO::query()`, không dùng prepared statement
- **Kết quả kỳ vọng:** Semgrep phát hiện SQL Injection
- **Mức độ:** Critical

### TC-19 — OS Command Injection
- **Cách inject:** Dùng `exec()`/`shell_exec()`/`system()` ghép input người dùng trực tiếp vào command string
- **Kết quả kỳ vọng:** Semgrep phát hiện OS Command Injection
- **Mức độ:** Critical

---

## NHÓM 4: SAST — Cần kiểm tra (Semgrep)

### TC-20 — Reflected XSS
- **Cách inject:** `echo`/`print` trực tiếp giá trị từ `$_GET`/`$_POST` ra HTML, không qua `htmlspecialchars()`
- **Kết quả kỳ vọng:** Semgrep phát hiện XSS
- **Mức độ:** Trung bình

### TC-21 — Insecure Deserialization (PHP Object Injection)
- **Cách inject:** Dùng `unserialize()` trên input chưa xác thực (vd cookie/param do người dùng gửi lên)
- **Kết quả kỳ vọng:** Semgrep phát hiện insecure deserialization
- **Mức độ:** Trung bình

### TC-22 — Thuật toán mã hoá yếu / lỗi thời
- **Cách inject:** Dùng `md5()`/`sha1()` để hash password
- **Kết quả kỳ vọng:** Semgrep phát hiện weak hashing algorithm
- **Mức độ:** Trung bình

### TC-23 — Local File Inclusion (LFI)
- **Cách inject:** `include`/`require` với đường dẫn file lấy từ input người dùng, không whitelist
- **Kết quả kỳ vọng:** Semgrep phát hiện LFI (đặc thù PHP, khác Path Traversal thuần đọc file)
- **Mức độ:** Cao

---

## NHÓM 5: Đề xuất bổ sung

### TC-24 — Hardcoded Credentials / Secrets
- **Cách inject:** Hardcode password DB hoặc API key trực tiếp trong source PHP
- **Kết quả kỳ vọng:** Semgrep phát hiện hardcoded secret
- **Mức độ:** Cao

### TC-25 — Path Traversal
- **Cách inject:** Ghép input người dùng trực tiếp vào file path để đọc/ghi file (vd chức năng download)
- **Kết quả kỳ vọng:** Semgrep phát hiện path traversal
- **Mức độ:** Cao

### TC-26 — Insecure Random
- **Cách inject:** Dùng `rand()`/`mt_rand()` để sinh token/OTP thay vì `random_bytes()`/`random_int()`
- **Kết quả kỳ vọng:** Semgrep phát hiện insecure randomness
- **Mức độ:** Trung bình

### TC-27 — Open Redirect
- **Cách inject:** `header('Location: ' . $_GET['redirect_to'])` không validate
- **Kết quả kỳ vọng:** Semgrep phát hiện open redirect
- **Mức độ:** Trung bình

### TC-28 — XXE (XML External Entity)
- **Cách inject:** Parse XML bằng `simplexml_load_string()`/`DOMDocument` với `LIBXML_NOENT` bật external entity, input XML từ người dùng
- **Kết quả kỳ vọng:** Semgrep phát hiện XXE
- **Mức độ:** Cao
- **Ghi chú:** Chỉ áp dụng nếu quyết định thêm tính năng nhận XML — cần confirm trước khi implement

### TC-29 — Code Injection qua eval()
- **Cách inject:** Đưa input người dùng (`$_GET`/`$_POST`) trực tiếp vào `eval()`
- **Kết quả kỳ vọng:** Semgrep phát hiện code injection — tương đương gần nhất với EL Injection phía Java (TC-27 Java: JSF EL và PHP `eval()` cùng bản chất "evaluate string thành code")
- **Mức độ:** Critical

### TC-30 — Dynamic Code Execution qua biến (class/function name từ input)
- **Cách inject:** `new $className()` hoặc `call_user_func($_GET['fn'])` với tên class/function lấy trực tiếp từ input người dùng
- **Kết quả kỳ vọng:** Semgrep phát hiện — pattern PHP tương đương "unsafe reflection" mà Semgrep thực tế bắt được ở Java (TC-22 Java: `Class.forName($VAR)`), khác với "thiếu check quyền" thuần business logic (nhiều khả năng Semgrep vẫn miss như đã ghi nhận ở Java)
- **Mức độ:** Cao
- **Ghi chú:** Nếu muốn test lại đúng phần "thiếu check quyền" (để confirm lại kết quả miss như Java TC-22), cần làm thêm 1 case phụ riêng — kỳ vọng không phát hiện được (expected miss), không nên gộp chung với case này

### TC-31 — SSRF qua curl/file_get_contents
- **Cách inject:** Ghép input người dùng vào URL rồi gọi `curl_setopt($ch, CURLOPT_URL, $url)` hoặc `file_get_contents($url)`
- **Kết quả kỳ vọng:** Semgrep phát hiện SSRF pattern
- **Mức độ:** Cao

### TC-32 — Log Injection / Log Forging
- **Cách inject:** Ghi `$_GET`/`$_POST` trực tiếp vào `error_log()` không sanitize `\n`, `\r`
- **Kết quả kỳ vọng:** Semgrep phát hiện log injection/log forging
- **Mức độ:** Thấp

---

## NHÓM 6: OWASP ZAP (DAST)

Chưa lên kế hoạch cho project này. Khi cần test, có thể tận dụng lại các endpoint tĩnh ở Nhóm 3–5 (theo đúng chiến lược hybrid đã áp dụng cho project Java: tạo route/endpoint mới gọi lại logic lỗ hổng cũ, không sửa code cũ) vì `php -S` cũng phục vụ HTTP bình thường cho ZAP quét.
