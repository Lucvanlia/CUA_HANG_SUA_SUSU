<?php
    include ('../../ketnoi/conndb.php');

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            print_r($_FILES);
            // Xử lý thêm sản phẩm
            $Tensp = $_POST['Tensp'];
            $MoTa = $_POST['MoTa'];
            $gia = $_POST['gia'];
            $SoLuong = $_POST['SoLuong'];
            $hinh = $_FILES['hinh']['name'];
            $Hinh_ChiTiet = ""; // Chưa upload hình chi tiết

            // Upload ảnh chính
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($hinh);
            move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file);

            // Lưu sản phẩm vào CSDL
            $sql = "INSERT INTO dmsp (Tensp, MoTa, gia, SoLuong, hinh, Hinh_ChiTiet) 
                    VALUES ('$Tensp', '$MoTa', '$gia', '$SoLuong', '$hinh', '$Hinh_ChiTiet')";
            if (mysqli_query($conn, $sql)) {
                echo "Thêm sản phẩm thành công!";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
            break;

        case 'delete':
            // Xử lý xóa sản phẩm
            $id_sp = $_POST['id_sp'];
            $sql = "DELETE FROM dmsp WHERE id_sp = $id_sp";
            if (mysqli_query($conn, $sql)) {
                echo "Xóa sản phẩm thành công!";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
            break;

        case 'edit':
            // Xử lý sửa sản phẩm
            $id_sp = $_POST['id_sp'];
            $Tensp = $_POST['Tensp'];
            $MoTa = $_POST['MoTa'];
            $gia = $_POST['gia'];
            $SoLuong = $_POST['SoLuong'];

            $sql = "UPDATE dmsp SET Tensp = '$Tensp', MoTa = '$MoTa', gia = '$gia', SoLuong = '$SoLuong' WHERE id_sp = $id_sp";
            if (mysqli_query($conn, $sql)) {
                echo "Cập nhật sản phẩm thành công!";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
            break;

        case 'upload_images':
            // Xử lý upload ảnh chi tiết
            $uploadedFiles = [];
            foreach ($_FILES as $file) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($file['name']);
                move_uploaded_file($file['tmp_name'], $target_file);
                $uploadedFiles[] = $file['name'];
            }
            echo json_encode($uploadedFiles);
            break;

        default:
            echo "Hành động không hợp lệ!";
    }
} else {
    echo "Không có hành động nào!";
}

mysqli_close($conn);
?>
