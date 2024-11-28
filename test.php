<?php
function getFolderInfo($folderPath) {
    $fileCount = 0;
    $totalSize = 0;

    // Kiểm tra nếu thư mục tồn tại
    if (is_dir($folderPath)) {
        $files = scandir($folderPath);

        // Duyệt qua các file trong thư mục
        foreach ($files as $file) {
            // Bỏ qua các thư mục `.` và `..`
            if ($file != "." && $file != "..") {
                $filePath = $folderPath . '/' . $file;

                // Kiểm tra nếu là file (không phải là thư mục con)
                if (is_file($filePath)) {
                    $fileCount++;
                    $totalSize += filesize($filePath); // Lấy kích thước file và cộng dồn
                }
            }
        }
    } else {
        echo "Thư mục không tồn tại.";
        return;
    }

    // Định dạng kích thước để dễ đọc (kB, MB, GB)
    function formatSize($size) {
        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    echo "Số lượng file trong thư mục: " . $fileCount . "<br>";
    echo "Tổng kích thước: " . formatSize($totalSize);
}

// Đường dẫn thư mục cần kiểm tra
$folderPath = 'C:\Intel';
getFolderInfo($folderPath);
?>
