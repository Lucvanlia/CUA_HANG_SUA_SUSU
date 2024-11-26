<?php
session_start();
include "../ketnoi/conndb.php";
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Bật báo lỗi chi tiết
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array('success' => false); // Khởi tạo phản hồi mặc định

if (isset($_POST['ten_sp'])) {
    $created_at = time();
    $name = $_POST['ten_sp'];
    $loai = $_POST['loai'];
    $xuatxu = $_POST['xuatxu'];
    $hang = $_POST['hang'];
    $soluong = $_POST['soluong'];
    $gia = $_POST['gia'];
    $hinh_nen = null; // Khởi tạo biến cho ảnh đại diện
    // Sử dụng câu lệnh chuẩn bị để kiểm tra tên sản phẩm
    $sql_check = "SELECT 1 FROM dmsp WHERE Tensp = ?";
    $stmt = mysqli_prepare($link, $sql_check);

    // Kiểm tra xem câu lệnh đã chuẩn bị thành công chưa
    if ($stmt) {
        // Gán giá trị cho tham số ? trong câu truy vấn
        mysqli_stmt_bind_param($stmt, "s", $name);

        // Thực thi câu truy vấn
        mysqli_stmt_execute($stmt);

        // Lấy kết quả truy vấn
        mysqli_stmt_store_result($stmt);

        // Kiểm tra xem có bản ghi nào trả về không
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $response['error'] = "Tên sản phẩm đã tồn tại";
            echo json_encode($response);
            exit();
        }

        // Đóng câu lệnh
        mysqli_stmt_close($stmt);
    } else {
        $response['error'] = "Lỗi truy vấn kiểm tra tên sản phẩm";
        echo json_encode($response);
        exit();
    }
    // Đảm bảo đường dẫn tồn tại
    $target_dir = "modul/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Xử lý ảnh đại diện
    if (isset($_FILES['hinh_nen']) && $_FILES['hinh_nen']['error'] == 0) {
        $avatar_name = $_FILES['hinh_nen']['name'];
        $target_avatar = $target_dir . basename($avatar_name);
        if (move_uploaded_file($_FILES['hinh_nen']['tmp_name'], $target_avatar)) {
            $hinh_nen = $avatar_name;
        } else {
            $response['error'] = "Không thể tải lên ảnh đại diện.";
            echo json_encode($response);
            exit();
        }
    }

    // Thực hiện lưu sản phẩm với ảnh đại diện
    $sql = "INSERT INTO dmsp (Tensp, id_xuatxu, id_hang, id_loai, hinh,gia,SoLuong) 
            VALUES ('$name', '$xuatxu', '$hang', '$loai', '$hinh_nen','$gia','$soluong')";

    if (mysqli_query($link, $sql)) {
        $id_sp = mysqli_insert_id($link); // Lấy ID của sản phẩm

        // Xử lý upload các ảnh chi tiết từ Dropzone
        if (isset($_FILES['hinh_chi_tiet'])) {
            $images = [];
            foreach ($_FILES['hinh_chi_tiet']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['hinh_chi_tiet']['error'][$key] == 0) {
                    $file_name = $_FILES['hinh_chi_tiet']['name'][$key];
                    $file_tmp = $_FILES['hinh_chi_tiet']['tmp_name'][$key];
                    $target_file = $target_dir . basename($file_name);

                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $images[] = $file_name; // Thêm tên file vào mảng
                    } else {
                        $response['error'] = "Không thể tải ảnh lên cho file $file_name.";
                        echo json_encode($response);
                        exit();
                    }
                }
            }

            // Cập nhật các ảnh chi tiết vào cơ sở dữ liệu
            if (!empty($images)) {
                $images_string = implode(',', $images);
                $update_sql = "UPDATE dmsp SET Hinh_ChiTiet = '$images_string' WHERE id_sp = '$id_sp'";
                if (!mysqli_query($link, $update_sql)) {
                    $response['error'] = "Truy vấn chi tiết không thể thực hiện.";
                    echo json_encode($response);
                    exit();
                }
            }
        }

        $response['success'] = true; // Thành công
    } else {
        $response['error'] = 'Lỗi cơ sở dữ liệu: ' . mysqli_error($link);
    }
} else {
    $response['error'] = 'Thiếu dữ liệu cần thiết.';
}

// Trả về JSON hợp lệ
echo json_encode($response);
