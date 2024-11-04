<?php
session_start();
include "ketnoi/conndb.php";
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Bật báo lỗi chi tiết
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array('success' => false); // Khởi tạo phản hồi mặc định

if (isset($_POST['star'])) {
    $star = $_POST['star'];
    $description = $_POST['description'];
    $created_at = time();

    // Đảm bảo đường dẫn tồn tại
    $target_dir = "modul/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Kiểm tra quyền ghi
    if (!is_writable($target_dir)) {
        $response['error'] = "Thư mục $target_dir không có quyền ghi.";
        echo json_encode($response);
        exit();
    }

    // Lưu đánh giá vào cơ sở dữ liệu
    $sql = "INSERT INTO product_feedback (id_kh, rating, comment, created_at, id_sp) VALUES ('1', '$star', '$description', '$created_at', '19')";

    if (mysqli_query($link, $sql)) {
        $feedback_id = mysqli_insert_id($link); // Lấy ID của đánh giá

        // Xử lý upload hình ảnh
        if (!empty($_FILES['file'])) {
            $images = [];
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['file']['name'][$key];
                $file_tmp = $_FILES['file']['tmp_name'][$key];
                $target_file = $target_dir . basename($file_name);
            
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $images[] = $file_name; // Thêm tên file vào mảng
                } else {
                    // Ghi lại chi tiết lỗi
                    $error = error_get_last();
                    $response['error'] = "Không thể tải ảnh lên cho file $file_name. Chi tiết lỗi: " . $error['message'];
                    echo json_encode($response);
                    exit();
                }
            }

            // Cập nhật tên hình ảnh vào cơ sở dữ liệu
            if (!empty($images)) {
                $images_string = implode(',', $images);
                $update_sql = "UPDATE product_feedback SET images='$images_string' WHERE id='$feedback_id'";
                mysqli_query($link, $update_sql);
            }
        }

        $response['success'] = true; // Đánh giá và ảnh đã lưu thành công
    } else {
        $response['error'] = 'Lỗi cơ sở dữ liệu: ' . mysqli_error($link);
    }
} else {
    $response['error'] = 'Thiếu dữ liệu cần thiết.';
}

ob_end_clean(); // Xóa toàn bộ output không mong muốn
echo json_encode($response); // Trả về JSON hợp lệ

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON error: " . json_last_error_msg());
}
?>
