<?php
session_start();
include "ketnoi/conndb.php";//hên xui 

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

$response = array('success' => false); // Khởi tạo phản hồi mặc định

if (isset($_POST['ten_sp'], $_POST['hang'], $_POST['xuatxu'], $_POST['loai'], $_POST['gia'], $_POST['soluong'], $_POST['hinh'], $_POST['mota'])) {
    // Nhận dữ liệu từ form
    $ten_sp = $_POST['ten_sp'];
    $hang = $_POST['hang'];
    $xuatxu = $_POST['xuatxu'];
    $loai = $_POST['loai'];
    $gia = $_POST['gia'];
    $soluong = $_POST['soluong'];
    $hinh = $_POST['hinh'];
    $mota = $_POST['mota'];

    // Thời gian hiện tại
    $created_at = time();

    // Lưu sản phẩm vào cơ sở dữ liệu
    $sql = "INSERT INTO dmsp (Tensp, id_hang, id_xuatxu, id_loai, Gia, SoLuong, hinh, Mota, created_at) 
            VALUES ('$ten_sp', '$hang', '$xuatxu', '$loai', '$gia', '$soluong', '$hinh', '$mota', '$created_at')";

    if (mysqli_query($link, $sql)) {
        $product_id = mysqli_insert_id($link); // Lấy ID sản phẩm vừa thêm

        // Xử lý ảnh từ Dropzone nếu có
        if (!empty($_FILES['file'])) {
            $images = [];
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['file']['name'][$key];
                $file_tmp = $_FILES['file']['tmp_name'][$key];

                // Đường dẫn lưu trữ ảnh
                $target_dir = "../../modul/uploads/";
                $target_file = $target_dir . basename($file_name);

                if (move_uploaded_file($file_tmp, $target_file)) {
                    $images[] = $target_file; // Lưu đường dẫn ảnh
                } else {
                    $response['error'] = 'Không thể tải ảnh lên.';
                    echo json_encode($response);
                    exit();
                }
            }

            // Nếu có ảnh, cập nhật trường HinhAnh_ChiTiet cho sản phẩm
            if (!empty($images)) {
                $images_string = implode(',', $images);
                $update_sql = "UPDATE dmsp SET HinhAnh_ChiTiet='$images_string' WHERE id_sp='$product_id'";
                mysqli_query($link, $update_sql);
            }
        }

        $response['success'] = true; // Sản phẩm và ảnh đã được lưu thành công
    } else {
        $response['error'] = mysqli_error($link); // Lỗi khi lưu sản phẩm
    }
} else {
    $response['error'] = 'Thiếu dữ liệu cần thiết.'; // Không nhận được tất cả dữ liệu từ form
}

echo json_encode($response); // Trả về phản hồi JSON
