<?php
// Kết nối đến cơ sở dữ liệu
include('ketnoi/conndb.php');

// Kiểm tra xem dữ liệu từ AJAX đã được gửi đến hay chưa
if (isset($_POST['id_hd']) && isset($_POST['trang_thai'])) {
    $orderId = $_POST['id_hd'];
    $status = $_POST['trang_thai'];

    // Cập nhật trạng thái đơn hàng trong cơ sở dữ liệu
    $sql = "UPDATE hoadon SET TrangThai = ? WHERE id_hd = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);

    // Kiểm tra xem cập nhật có thành công không
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Đóng kết nối
    $stmt->close();
} else {
    echo 'error';
}

// Đóng kết nối đến cơ sở dữ liệu
$link->close();
?>
