<?php

session_start();
include "admin_test/ketnoi/conndb.php";
/* if(isset($_POST['user_id']) && isset($_POST['star']) && isset($_POST['description'])){
    $user_id = $_POST['user_id'];
    $star = $_POST['star'];
    $description = $_POST['description'];
    $created_at =time();
    // Lưu đánh giá vào cơ sở dữ liệu
    $sql = "INSERT INTO product_feedback (id_kh, rating, comment,created_at) VALUES ('$user_id', '$star', '$description','$created_at')";
    if(mysqli_query($link, $sql)){
        // Sau khi thêm đánh giá mới, hiển thị lại tất cả đánh giá
        include 'fetch_feedback.php';
    } else {
        echo 'Không thể lưu đánh giá, vui lòng thử lại.';
    }
}*/

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

$response = array('success' => false); // Khởi tạo phản hồi mặc định

if (isset($_POST['user_id']) && isset($_POST['star']) && isset($_POST['description'])) {
    $user_id = $_POST['user_id'];
    $star = $_POST['star'];
    $description = $_POST['description'];
    $created_at = time();
    $id_sp = $_POST['id_sp'] ; 
    $hoatdong = 0 ; 
    // INSERT INTO `binhluan` (`id_bl`, `id_sp`, `id_kh`, `NoiDung`, `rating`, `Hinh_BL`, `HoatDong`, `created_at`) VALUES (NULL, '107', '18', 'Rất tuyệt', '4', 'maxresdefault.jpg', '0', current_timestamp());
    // Lưu đánh giá vào cơ sở dữ liệu
    $sql = "INSERT INTO binhluan (id_kh, rating, NoiDung, id_sp,HoatDong) VALUES ('$user_id', '$star', '$description', '$id_sp','$hoatdong')";

    if (mysqli_query($link, $sql)) {
        $feedback_id = mysqli_insert_id($link); // Lấy ID của đánh giá

        // Xử lý upload hình ảnh
        if (!empty($_FILES['file'])) {
            $images = [];
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['file']['name'][$key];
                $file_tmp = $_FILES['file']['tmp_name'][$key];

                // Đường dẫn lưu trữ ảnh
                $target_dir = "admin_test/modul/uploads/";
                $target_file = basename($file_name);

                if (move_uploaded_file($file_tmp, $target_file)) {
                    $images[] = $target_file; // Lưu đường dẫn
                } else {
                    $response['error'] = 'Không thể tải ảnh lên.';
                    echo json_encode($response);
                    exit();
                }
            }

            // Nếu có ảnh, cập nhật cơ sở dữ liệu
            if (!empty($images)) {
                $images_string = implode(',', $images);
                $update_sql = "UPDATE binhluan SET Hinh_BL ='$images_string' WHERE id_bl ='$feedback_id'";
                mysqli_query($link, $update_sql);
            }
        }

        $response['success'] = 'success'; // Đánh giá và ảnh đã lưu thành công
    } else {
        $response['error'] = mysqli_error($link);
    }
} else {
    $response['error'] = 'Thiếu dữ liệu cần thiết.';
}

echo json_encode($response); // Trả về JSON hợp lệ