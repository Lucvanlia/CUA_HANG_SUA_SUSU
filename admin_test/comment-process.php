<?php
session_start();
include "ketnoi/conndb.php";
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Bật báo lỗi chi tiết
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array('success' => false); // Khởi tạo phản hồi mặc định

if (isset($_POST['Ten_sp'])) {
    $ten_sp = $_POST['Ten_sp'];
    $mo_ta = $_POST['MoTa_sp'];
    $id_dm = $_POST['id_dm'];
    $id_xx = $_POST['id_xx'];
    $id_ncc = $_POST['id_ncc'];

    // Xử lý ảnh đại diện (Hinh_Nen)
    $Hinh_Nen = null;
    if (isset($_FILES['Hinh_Nen']) && $_FILES['Hinh_Nen']['error'] == 0) {
        $target_dir = "modul/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $avatar_name = $_FILES['Hinh_Nen']['name'];
        $target_avatar = $target_dir . basename($avatar_name);
        if (move_uploaded_file($_FILES['Hinh_Nen']['tmp_name'], $target_avatar)) {
            $Hinh_Nen = $avatar_name;
        }
    }

    // Thêm sản phẩm vào CSDL
    $sql = "INSERT INTO SanPham (Ten_sp, MoTa_sp, id_dm, id_xx, id_ncc, Hinh_Nen) 
            VALUES ('$ten_sp', '$mo_ta', '$id_dm', '$id_xx', '$id_ncc', '$Hinh_Nen')";

    if (mysqli_query($link, $sql)) {
        $id_sp = mysqli_insert_id($link); // ID sản phẩm vừa thêm

        // Xử lý các size sản phẩm
        if (isset($_POST['sizes']['GiaBan']) && is_array($_POST['sizes']['GiaBan'])) {
            foreach ($_POST['sizes']['GiaBan'] as $key => $giaBan) {
                $soLuong = $_POST['sizes']['SoLuong'][$key] ?? 0;
                $giaNhap = $_POST['sizes']['GiaNhap'][$key] ?? 0;
                $khuyenMai = $_POST['sizes']['KhuyenMai_Fast'][$key] ?? 0;

                $sizeSql = "INSERT INTO DonGia (id_sp, GiaNhap, GiaBan, KhuyenMai_Fast, SoLuong) 
                            VALUES ('$id_sp', '$giaNhap', '$giaBan', '$khuyenMai', '$soLuong')";
                mysqli_query($link, $sizeSql);
            }
        }
        if (isset($_FILES['hinh_chi_tiet'])) {
            $target_dir = "modul/uploads/";
            $images = [];
            foreach ($_FILES['hinh_chi_tiet']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['hinh_chi_tiet']['error'][$key] == 0) {
                    $file_name = $_FILES['hinh_chi_tiet']['name'][$key];
                    $file_tmp = $_FILES['hinh_chi_tiet']['tmp_name'][$key];
                    $target_file = $target_dir . basename($file_name);

                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $images[] = $file_name;
                    }
                }
            }

            // Cập nhật các ảnh chi tiết vào CSDL
            if (!empty($images)) {
                $images_string = implode(',', $images);
                $update_sql = "UPDATE Sanpham SET Hinh_ChiTiet = '$images_string' WHERE id_sp = '$id_sp'";
                mysqli_query($link, $update_sql);
            }
        }
        echo json_encode(['success' => true]);

    } 
    else {
        echo json_encode(['success' => false, 'error' => mysqli_error($link)]);
        exit();
    }
} else {
    $response['error'] = 'Thiếu dữ liệu cần thiết.';
}

// Trả về JSON hợp lệ
echo json_encode($response);
